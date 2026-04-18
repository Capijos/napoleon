<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as HttpCookie;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    private const CART_COOKIE = 'napoleon_cart';
    private const CART_COOKIE_MINUTES = 43200;

    protected function resolveCartId(Request $request): ?string
    {
        $cartId = $request->cookie(self::CART_COOKIE);

        return $cartId ? (string) $cartId : null;
    }

    protected function loadCartFromRequest(Request $request): ?Cart
    {
        $cartId = $this->resolveCartId($request);

        if (!$cartId) {
            return null;
        }

        $cart = Cart::find($cartId);

        if (!$cart || $cart->status !== Cart::STATUS_ACTIVE) {
            return null;
        }

        return $cart;
    }

    protected function queueCartCookie(Request $request, Cart $cart): void
    {
        Cookie::queue($this->makeCartCookie($request, $cart));
    }

    protected function queueCartForget(Request $request): void
    {
        Cookie::queue(Cookie::forget(self::CART_COOKIE));
    }

    protected function makeCartCookie(Request $request, Cart $cart): HttpCookie
    {
        return Cookie::make(
            self::CART_COOKIE,
            (string) $cart->getKey(),
            self::CART_COOKIE_MINUTES,
            null,
            null,
            $request->isSecure(),
            true,
            false,
            'lax'
        );
    }

    protected function withCartCookie($response, Request $request, ?Cart $cart)
    {
        if (!$cart) {
            return $response;
        }

        return $response->withCookie($this->makeCartCookie($request, $cart));
    }

    protected function getOrCreateCart(Request $request): Cart
    {
        $cart = $this->loadCartFromRequest($request);

        if ($cart) {
            $this->queueCartCookie($request, $cart);

            return $cart;
        }

        $cart = Cart::create([
            'user_id' => 'guest_' . Str::uuid()->toString(),
            'status' => Cart::STATUS_ACTIVE,
            'items' => [],
            'subtotal' => 0,
            'discount' => 0,
            'shipping_cost' => 0,
            'tax' => 0,
            'total' => 0,
            'currency' => 'PEN',
        ]);

        $this->queueCartCookie($request, $cart);

        return $cart;
    }

    protected function findActiveProduct(string $productId): ?Product
    {
        return Product::where('status', 'active')
            ->where('_id', $productId)
            ->first();
    }

    protected function mapCartItem(array $item): array
    {
        return [
            'item_id' => (string) ($item['item_id'] ?? ''),
            'product_id' => (string) ($item['product_id'] ?? ''),
            'variant_id' => isset($item['variant_id']) && $item['variant_id'] !== '' ? (string) $item['variant_id'] : null,
            'name' => (string) ($item['name'] ?? 'Producto'),
            'image' => $item['image'] ?? null,
            'sku' => $item['sku'] ?? null,
            'variant_name' => $item['variant_name'] ?? null,
            'unit_price' => round((float) ($item['unit_price'] ?? 0), 2),
            'quantity' => max(0, (int) ($item['quantity'] ?? 0)),
            'subtotal' => round((float) ($item['subtotal'] ?? 0), 2),
        ];
    }

    protected function buildCartData(?Cart $cart): array
    {
        $items = collect($cart?->items ?? [])
            ->map(fn ($item) => $this->mapCartItem((array) $item))
            ->values()
            ->all();

        return [
            'cart' => $cart,
            'items' => $items,
            'itemsCount' => (int) collect($items)->sum('quantity'),
            'subtotal' => round((float) ($cart?->subtotal ?? 0), 2),
            'discount' => round((float) ($cart?->discount ?? 0), 2),
            'shippingCost' => round((float) ($cart?->shipping_cost ?? 0), 2),
            'tax' => round((float) ($cart?->tax ?? 0), 2),
            'total' => round((float) ($cart?->total ?? 0), 2),
        ];
    }

    protected function summaryResponse(?Cart $cart): array
    {
        $data = $this->buildCartData($cart);

        return [
            'items' => $data['items'],
            'items_count' => $data['itemsCount'],
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'],
            'shipping_cost' => $data['shippingCost'],
            'tax' => $data['tax'],
            'total' => $data['total'],
        ];
    }

    public function index(Request $request)
    {
        return view('cart.index', $this->buildCartData($this->loadCartFromRequest($request)));
    }

    public function add(Request $request)
    {
        $payload = $request->validate([
            'product_id' => ['required', 'string'],
            'variant_id' => ['nullable', 'string'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = $this->findActiveProduct($payload['product_id']);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado.',
            ], 404);
        }

        $variantId = $payload['variant_id'] ?? null;

        if ($variantId !== null && !$product->resolveVariant($variantId)) {
            return response()->json([
                'success' => false,
                'message' => 'Variante no encontrada para este producto.',
            ], 422);
        }

        $cart = $this->getOrCreateCart($request);
        $cartItemData = $product->toCartItemData($variantId);

        $cart->addItem(array_merge(
            $cartItemData,
            ['quantity' => (int) ($payload['quantity'] ?? 1)]
        ));
        $cart->save();

        $summary = $this->summaryResponse($cart);
        $addedItem = collect($cart->items ?? [])
            ->map(fn ($item) => $this->mapCartItem((array) $item))
            ->first(function ($item) use ($product, $cartItemData) {
                return $item['product_id'] === (string) $product->getKey()
                    && (string) ($item['variant_id'] ?? '') === (string) ($cartItemData['variant_id'] ?? '');
            });

        return $this->withCartCookie(response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito.',
            'cart_id' => (string) $cart->getKey(),
            'item' => $addedItem,
        ] + $summary), $request, $cart);
    }

    public function update(Request $request)
    {
        $payload = $request->validate([
            'item_id' => ['required', 'string'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = $this->loadCartFromRequest($request);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Carrito no encontrado.',
            ], 404);
        }

        if (!$cart->updateItemQuantity($payload['item_id'], (int) $payload['quantity'])) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en el carrito.',
            ], 404);
        }

        $cart->save();

        return $this->withCartCookie(response()->json([
            'success' => true,
            'message' => 'Carrito actualizado.',
        ] + $this->summaryResponse($cart)), $request, $cart);
    }

    public function remove(Request $request)
    {
        $payload = $request->validate([
            'item_id' => ['required', 'string'],
        ]);

        $cart = $this->loadCartFromRequest($request);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Carrito no encontrado.',
            ], 404);
        }

        $beforeCount = $cart->itemsCount();
        $cart->removeItemById($payload['item_id']);

        if ($beforeCount === $cart->itemsCount()) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en el carrito.',
            ], 404);
        }

        $cart->save();

        return $this->withCartCookie(response()->json([
            'success' => true,
            'message' => 'Item eliminado del carrito.',
        ] + $this->summaryResponse($cart)), $request, $cart);
    }

    public function clear(Request $request)
    {
        $cart = $this->loadCartFromRequest($request);

        if ($cart) {
            $cart->clear();
            $cart->save();
        }

        return $this->withCartCookie(response()->json([
            'success' => true,
            'message' => 'Carrito vaciado.',
        ] + $this->summaryResponse($cart)), $request, $cart);
    }

    public function getCount(Request $request)
    {
        return $this->withCartCookie(response()->json([
            'count' => $this->buildCartData($this->loadCartFromRequest($request))['itemsCount'],
        ]), $request, $this->loadCartFromRequest($request));
    }

    public function mini(Request $request)
    {
        $data = $this->buildCartData($this->loadCartFromRequest($request));

        return $this->withCartCookie(response()->view('components.mini-cart-content', [
            'items' => $data['items'],
            'subtotal' => $data['subtotal'],
            'itemsCount' => $data['itemsCount'],
        ]), $request, $data['cart']);
    }

    public function summary(Request $request)
    {
        $cart = $this->loadCartFromRequest($request);

        return $this->withCartCookie(response()->json($this->summaryResponse($cart)), $request, $cart);
    }

    public function checkout(Request $request)
    {
        $data = $this->buildCartData($this->loadCartFromRequest($request));

        if (empty($data['items'])) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        return view('checkout.index', $data);
    }

    public function sendToWhatsApp(Request $request)
    {
        $cart = $this->loadCartFromRequest($request);
        $data = $this->buildCartData($cart);

        if (empty($data['items'])) {
            $this->queueCartForget($request);

            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $itemsText = '';

        foreach ($data['items'] as $index => $item) {
            $name = $item['name'];

            if (!empty($item['variant_name'])) {
                $name .= ' (' . $item['variant_name'] . ')';
            }

            $itemsText .= ($index + 1) . '. ' . $name . "%0A";
            $itemsText .= '   Cantidad: ' . $item['quantity'] . ' x $' . number_format($item['unit_price'], 0, ',', '.') . "%0A";
            $itemsText .= '   Subtotal: $' . number_format($item['subtotal'], 0, ',', '.') . "%0A%0A";
        }

        $message = '* Nuevo Pedido - Napoleone Joyas *%0A%0A';
        $message .= $itemsText;
        $message .= '---------------------------%0A';
        $message .= 'Subtotal: $' . number_format($data['subtotal'], 0, ',', '.') . "%0A";

        if ($data['discount'] > 0) {
            $message .= 'Descuento: -$' . number_format($data['discount'], 0, ',', '.') . "%0A";
        }

        if ($data['shippingCost'] > 0) {
            $message .= 'Envío: $' . number_format($data['shippingCost'], 0, ',', '.') . "%0A";
        }

        $message .= '*TOTAL: $' . number_format($data['total'] > 0 ? $data['total'] : $data['subtotal'], 0, ',', '.') . '*%0A%0A';
        $message .= 'Por favor confirmar disponibilidad.';

        return redirect()->away('https://wa.me/573103243890?text=' . $message);
    }
}
