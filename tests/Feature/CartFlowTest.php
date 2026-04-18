<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartFlowTest extends TestCase
{
    private array $createdProductIds = [];

    private array $createdCartIds = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function tearDown(): void
    {
        if ($this->createdCartIds !== []) {
            Cart::whereIn('_id', $this->createdCartIds)->delete();
        }

        if ($this->createdProductIds !== []) {
            Product::whereIn('_id', $this->createdProductIds)->delete();
        }

        parent::tearDown();
    }

    public function test_add_endpoint_returns_backend_resolved_cart_contract(): void
    {
        $product = $this->makeProduct();

        $response = $this->postJson('/api/cart/add', [
            'product_id' => (string) $product->getKey(),
            'quantity' => 2,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('item.product_id', (string) $product->getKey())
            ->assertJsonPath('item.variant_id', (string) $product->default_variant['variant_id'])
            ->assertJsonPath('item.name', $product->name)
            ->assertJsonPath('item.sku', $product->default_variant['sku'])
            ->assertJsonPath('item.quantity', 2)
            ->assertJsonPath('items_count', 2)
            ->assertJsonPath('subtotal', 240000);

        $cookie = $this->extractCartCookie($response);

        $this->assertSame('napoleon_cart', $cookie['name']);
        $this->assertSame($response->json('cart_id'), $cookie['value']);
    }

    public function test_update_and_remove_operate_by_item_id(): void
    {
        $product = $this->makeProduct();
        $addResponse = $this->postJson('/api/cart/add', [
            'product_id' => (string) $product->getKey(),
            'quantity' => 1,
        ]);

        $cookie = $this->extractCartCookie($addResponse);
        $itemId = $addResponse->json('item.item_id');

        $updateResponse = $this->postJsonWithCartCookie('/api/cart/update', $cookie, [
            'item_id' => $itemId,
            'quantity' => 3,
        ]);

        $updateResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('items_count', 3)
            ->assertJsonPath('subtotal', 360000);

        $removeResponse = $this->postJsonWithCartCookie('/api/cart/remove', $cookie, [
            'item_id' => $itemId,
        ]);

        $removeResponse->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('items_count', 0)
            ->assertJsonPath('subtotal', 0);
    }

    public function test_count_mini_cart_page_and_checkout_share_same_cart_snapshot(): void
    {
        $product = $this->makeProduct(name: 'Producto Snapshot');

        $addResponse = $this->postJson('/api/cart/add', [
            'product_id' => (string) $product->getKey(),
            'quantity' => 2,
        ]);

        $cookie = $this->extractCartCookie($addResponse);

        $this->getJsonWithCartCookie('/api/cart/count', $cookie)
            ->assertOk()
            ->assertJson(['count' => 2]);

        $this->getWithCartCookie('/api/cart/mini', $cookie)
            ->assertOk()
            ->assertSee('Producto Snapshot')
            ->assertSee('Finalizar pedido');

        $this->getWithCartCookie('/cart', $cookie)
            ->assertOk()
            ->assertSee('Producto Snapshot')
            ->assertSee('$240.000');

        $this->getWithCartCookie('/checkout', $cookie)
            ->assertOk()
            ->assertSee('Producto Snapshot')
            ->assertSee('Resumen del Pedido');
    }

    public function test_cart_persists_across_reload_and_is_isolated_between_sessions(): void
    {
        $productA = $this->makeProduct(name: 'Producto Sesion A', price: 50000, variantId: 'variant-a');
        $productB = $this->makeProduct(name: 'Producto Sesion B', price: 70000, variantId: 'variant-b');

        $sessionA = $this->postJson('/api/cart/add', [
            'product_id' => (string) $productA->getKey(),
            'quantity' => 1,
        ]);
        $cookieA = $this->extractCartCookie($sessionA);

        $sessionB = $this->postJson('/api/cart/add', [
            'product_id' => (string) $productB->getKey(),
            'quantity' => 1,
        ]);
        $cookieB = $this->extractCartCookie($sessionB);

        $this->assertNotSame($cookieA['value'], $cookieB['value']);

        $this->getWithCartCookie('/cart', $cookieA)
            ->assertOk()
            ->assertSee('Producto Sesion A')
            ->assertDontSee('Producto Sesion B');

        $this->getWithCartCookie('/cart', $cookieB)
            ->assertOk()
            ->assertSee('Producto Sesion B')
            ->assertDontSee('Producto Sesion A');
    }

    private function makeProduct(
        string $name = 'Producto Test',
        float $price = 120000,
        string $variantId = 'variant-default'
    ): Product {
        $product = Product::create([
            'shopify_id' => random_int(9000000000000, 9999999999999),
            'shopify_handle' => Str::slug($name) . '-' . Str::lower(Str::random(6)),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(6)),
            'description' => 'Producto de prueba para carrito',
            'short_description' => 'Producto de prueba',
            'main_image' => 'https://example.com/' . Str::slug($name) . '.jpg',
            'status' => 'active',
            'variants' => [[
                'variant_id' => $variantId,
                'title' => 'Default Title',
                'sku' => 'SKU-' . Str::upper(Str::random(6)),
                'price' => $price,
                'available' => true,
            ]],
            'images' => [],
            'category_ids' => [],
            'category_slugs' => [],
            'category_names' => [],
            'promotion_ids' => [],
            'badge_labels' => [],
            'status_badges' => [],
            'raw_product' => [],
        ]);

        $this->createdProductIds[] = (string) $product->getKey();

        return $product;
    }

    private function getWithCartCookie(string $uri, array $cookie)
    {
        return $this->call('GET', $uri, [], [$cookie['name'] => $cookie['value']]);
    }

    private function getJsonWithCartCookie(string $uri, array $cookie)
    {
        return $this->call('GET', $uri, [], [$cookie['name'] => $cookie['value']], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
    }

    private function postJsonWithCartCookie(string $uri, array $cookie, array $payload)
    {
        return $this->call(
            'POST',
            $uri,
            [],
            [$cookie['name'] => $cookie['value']],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($payload, JSON_THROW_ON_ERROR)
        );
    }

    private function extractCartCookie($response): array
    {
        $cookie = collect($response->headers->getCookies())
            ->first(fn ($item) => $item->getName() === 'napoleon_cart');

        $this->assertNotNull($cookie, 'No se encontro la cookie del carrito en la respuesta.');

        $value = trim((string) $cookie->getValue(), '"');

        $this->createdCartIds[] = $value;

        return [
            'name' => $cookie->getName(),
            'value' => $value,
        ];
    }
}
