@php
    $cartItems = $items ?? [];
    $cartSubtotal = $subtotal ?? 0;
@endphp

@if(empty($cartItems))
<div class="mini-cart-empty">
    <p>Tu carrito esta vacio</p>
    <a href="{{ url('/') }}" class="button button--primary">Continuar comprando</a>
</div>
@else
<div class="mini-cart-items">
    @foreach($cartItems as $item)
    <div class="mini-cart-item" data-item-id="{{ $item['item_id'] }}">
        <div class="mini-cart-item__image">
            @if(!empty($item['image']))
            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy">
            @else
            <div class="mini-cart-item__image-placeholder"></div>
            @endif
        </div>
        <div class="mini-cart-item__details">
            <span class="mini-cart-item__name">{{ $item['name'] }}</span>
            @if(!empty($item['variant_name']))
            <span class="mini-cart-item__variant">{{ $item['variant_name'] }}</span>
            @endif
            <span class="mini-cart-item__price">${{ number_format((float) $item['unit_price'], 0, ',', '.') }}</span>
        </div>
        <div class="mini-cart-item__actions">
            <div class="mini-cart-item__quantity">
                <button type="button" class="qty-btn minus" data-item-id="{{ $item['item_id'] }}">-</button>
                <span>{{ $item['quantity'] }}</span>
                <button type="button" class="qty-btn plus" data-item-id="{{ $item['item_id'] }}">+</button>
            </div>
            <button type="button" class="mini-cart-item__remove" data-item-id="{{ $item['item_id'] }}" aria-label="Eliminar {{ $item['name'] }}">
                <svg viewBox="0 0 24 24" class="icon">
                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>
    @endforeach
</div>

<div class="mini-cart-footer">
    <div class="mini-cart-subtotal">
        <span>Subtotal</span>
        <span>${{ number_format((float) $cartSubtotal, 0, ',', '.') }}</span>
    </div>
    <a href="{{ route('checkout.index') }}" class="button button--primary button--full">Finalizar pedido</a>
    <a href="{{ route('cart.index') }}" class="mini-cart-view-cart">Ver carrito completo</a>
</div>
@endif
