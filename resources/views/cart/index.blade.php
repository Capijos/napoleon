@extends('app')

@section('title', 'Carrito de Compras - Napoleone Joyas')

@section('content')
@php
$isEmpty = empty($items) || count($items) === 0;
$formatPrice = fn ($price) => '$' . number_format((float) $price, 0, ',', '.');
@endphp

<main id="MainContent" class="content-for-layout focus-none" role="main" tabindex="-1">
    <div class="page-width page-width--narrow page-width--flush-small">
        <div class="section-header">
            <h1 class="section-header__title">
                Tu Carrito
            </h1>
        </div>

        <div class="cart-wrapper">
            @if($isEmpty)
            <div class="cart-empty">
                <div class="cart-empty__message">
                    <p class="cart-empty__text">Tu carrito está vacío</p>
                </div>
                <div class="cart-empty__actions">
                    <a href="{{ route('home') }}" class="button button--primary">
                        Continuar Comprando
                    </a>
                </div>
            </div>
            @else
            <div class="cart-page">
                <nav class="breadcrumb" role="navigation" aria-label="breadcrumbs">
                    <a href="{{ url('/') }}" class="link">Inicio</a>
                    <span class="separate" aria-hidden="true"></span>
                    <span>Tu Carrito</span>
                </nav>

                <form id="cart" class="cart__contents" action="{{ rtrim(request()->getBaseUrl(), '/') }}/api/cart/update" method="POST">
                    @csrf
                    <div class="cart__items" id="main-cart-items">
                        <div class="cart__items-wrapper">
                            <div class="cart__items-heading">
                                <span class="visually-hidden">Imagen del producto</span>
                            </div>
                            <div class="cart__items-title-heading">
                                <span class="visually-hidden">Información del producto</span>
                            </div>
                            <div class="cart__items-price-heading">
                                <span class="visually-hidden">Precio</span>
                            </div>
                            <div class="cart__items-quantity-heading">
                                <span class="visually-hidden">Cantidad</span>
                            </div>
                            <div class="cart__items-total-heading">
                                <span class="visually-hidden">Total</span>
                            </div>
                            <div class="cart__items-action-heading">
                                <span class="visually-hidden">Eliminar</span>
                            </div>
                        </div>

                        @foreach($items as $index => $item)
                        <div class="cart-item cart-item--small" data-cart-item data-item-id="{{ $item['item_id'] }}">
                            <div class="cart-item__media">
                                <a href="{{ route('producto.show', $item['product_id']) }}" class="cart-item__link">
                                    <img 
                                        src="{{ $item['image'] ?? 'https://napoleonejoyas.co/cdn/shop/javascript:void(0);' }}" 
                                        alt="{{ $item['name'] }}"
                                        class="cart-item__image"
                                        loading="lazy"
                                    >
                                </a>
                            </div>

                            <div class="cart-item__details">
                                <a href="{{ route('producto.show', $item['product_id']) }}" class="cart-item__title">
                                    {{ $item['name'] }}
                                </a>
                                @if(!empty($item['variant_name']))
                                <div class="cart-item__variant">
                                    {{ $item['variant_name'] }}
                                </div>
                                @endif
                                @if(!empty($item['sku']))
                                <div class="cart-item__sku">
                                    SKU: {{ $item['sku'] }}
                                </div>
                                @endif
                            </div>

                            <div class="cart-item__price">
                                <span class="price-item price-item--regular">
                                    {{ $formatPrice($item['unit_price']) }}
                                </span>
                            </div>

                            <div class="cart-item__quantity">
                                <quantity-input class="quantity quantity--small">
                                    <button class="quantity-button" name="minus" type="button">
                                        <span class="visually-hidden">Decrease quantity for {{ $item['name'] }}</span>
                                        <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-plus">
                                            <path d="M.282 10.532h19.436V.282H.282v10.25z" fill="currentColor"></path>
                                        </svg>
                                    </button>
                                    <input
                                        class="quantity-input"
                                        type="number"
                                        name="quantity[{{ $item['item_id'] }}]"
                                        value="{{ $item['quantity'] }}"
                                        min="0"
                                        aria-label="{{ $item['name'] }}"
                                        data-item-id="{{ $item['item_id'] }}"
                                    >
                                    <button class="quantity-button" name="plus" type="button">
                                        <span class="visually-hidden">Increase quantity for {{ $item['name'] }}</span>
                                        <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-plus">
                                            <path d="M10.532.282v19.436H.282V.282h10.25z" fill="currentColor"></path>
                                            <path d="M.282 10.532h19.436V.282H.282v10.25z" fill="currentColor"></path>
                                        </svg>
                                    </button>
                                </quantity-input>
                            </div>

                            <div class="cart-item__total">
                                <span class="price-item price-item--regular">
                                    {{ $formatPrice($item['subtotal']) }}
                                </span>
                            </div>

                            <div class="cart-item__remove">
                                <button 
                                    type="button" 
                                    class="cart-item__remove-button"
                                    data-item-id="{{ $item['item_id'] }}"
                                    aria-label="Eliminar {{ $item['name'] }}"
                                >
                                    <svg aria-hidden="true" focusable="false" role="presentation" class="icon icon-remove">
                                        <path d="M.282 10.532h19.436V.282H.282v10.25z" fill="currentColor"></path>
                                        <path d="M.282 10.532h19.436V.282H.282v10.25z" fill="currentColor" transform="rotate(-90 .282 10.532)"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="cart__footer">
                        <div class="cart__notes">
                            <details class="cart-note">
                                <summary class="cart-note__summary">
                                    <span>Agregar nota al pedido</span>
                                </summary>
                                <div class="cart-note__content">
                                    <textarea 
                                        name="note" 
                                        class="form-input cart-note__input" 
                                        placeholder="Instrucciones especiales para el pedido..."
                                        rows="4"
                                    ></textarea>
                                </div>
                            </details>
                        </div>

                        <div class="cart__summary">
                            <div class="cart-summary__blocks">
                                <div class="cart-summary__block">
                                    <div class="cart-summary__row">
                                        <span>Subtotal</span>
                                        <span class="price">{{ $formatPrice($subtotal) }}</span>
                                    </div>
                                    <div class="cart-summary__row cart-summary__row--total">
                                        <span>Total</span>
                                        <span class="price">{{ $formatPrice($subtotal) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="cart__buttons">
                                <a href="{{ route('checkout.index') }}" class="button button--primary button--full">
                                    Proceder al Checkout
                                </a>
                            </div>

                            <div class="cart__continue">
                                <a href="{{ route('home') }}" class="link link--underline">
                                    Continuar Comprando
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="cart-recommendations">
            <div class="cart-recommendations__title">
                <h2>También te puede gustar</h2>
            </div>
            <div class="cart-recommendations__grid">
                {{-- Recommendations will be loaded here if available --}}
            </div>
        </div>
    </div>
</main>

<style>
.cart-wrapper {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 20px;
}

.cart-empty {
    text-align: center;
    padding: 60px 0;
}

.cart-empty__message {
    margin-bottom: 24px;
}

.cart-empty__text {
    font-size: 18px;
    color: #505050;
}

.cart-empty__actions {
    margin-top: 24px;
}

.section-header {
    text-align: center;
    padding: 40px 0 30px;
}

.section-header__title {
    font-size: 32px;
    font-weight: 700;
    color: #000;
}

.cart-page {
    display: block;
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 0 20px;
    flex-wrap: wrap;
}

.breadcrumb .link {
    color: #505050;
    text-decoration: none;
    font-size: 14px;
}

.breadcrumb .link:hover {
    color: #000;
    text-decoration: underline;
}

.breadcrumb .separate {
    display: inline-block;
    width: 4px;
    height: 4px;
    background: #ccc;
    border-radius: 50%;
}

.cart__contents {
    display: block;
}

.cart__items {
    border: 1px solid #e5e5e5;
}

.cart__items-heading {
    display: none;
}

.cart__items-wrapper {
    display: none;
}

.cart__items-title-heading,
.cart__items-price-heading,
.cart__items-quantity-heading,
.cart__items-total-heading,
.cart__items-action-heading {
    display: none;
}

.cart-item {
    display: grid;
    grid-template-columns: 100px 1fr;
    grid-template-rows: auto auto;
    gap: 16px;
    padding: 20px;
    border-bottom: 1px solid #e5e5e5;
    align-items: start;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item__media {
    grid-row: 1;
}

.cart-item__link {
    display: block;
}

.cart-item__image {
    width: 100%;
    height: auto;
    max-width: 100px;
    object-fit: cover;
}

.cart-item__details {
    grid-row: 1;
}

.cart-item__title {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #000;
    text-decoration: none;
    line-height: 1.4;
}

.cart-item__title:hover {
    text-decoration: underline;
}

.cart-item__variant,
.cart-item__sku {
    font-size: 12px;
    color: #505050;
    margin-top: 4px;
}

.cart-item__price {
    grid-row: 2;
    font-size: 14px;
    color: #505050;
}

.cart-item__quantity {
    grid-row: 2;
    display: flex;
    align-items: center;
}

.quantity {
    display: flex;
    align-items: center;
    position: relative;
    max-width: 100px;
}

.quantity-input {
    width: 100%;
    height: 40px;
    text-align: center;
    border: 1px solid #e5e5e5;
    font-size: 14px;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-button {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.quantity-button[name="minus"] {
    left: 0;
}

.quantity-button[name="plus"] {
    right: 0;
}

.quantity-button svg {
    width: 10px;
    height: 10px;
    fill: #505050;
}

.cart-item__total {
    grid-row: 2;
    font-size: 14px;
    font-weight: 600;
    color: #000;
}

.cart-item__remove {
    grid-row: 1 / 3;
    justify-self: end;
}

.cart-item__remove-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

.cart-item__remove-button svg {
    width: 14px;
    height: 14px;
    fill: #505050;
}

.cart-item__remove-button:hover svg {
    fill: #d12442;
}

.cart__footer {
    display: flex;
    flex-direction: column;
    gap: 24px;
    margin-top: 24px;
}

.cart__notes {
    border: 1px solid #e5e5e5;
}

.cart-note summary {
    padding: 16px 20px;
    cursor: pointer;
    font-size: 14px;
    color: #505050;
    list-style: none;
}

.cart-note summary::-webkit-details-marker {
    display: none;
}

.cart-note__content {
    padding: 0 20px 20px;
}

.cart-note__input {
    width: 100%;
    border: 1px solid #e5e5e5;
    padding: 12px;
    font-size: 14px;
    resize: vertical;
}

.cart__summary {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 16px;
}

.cart-summary__row {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    font-size: 14px;
    color: #505050;
    padding: 8px 0;
    width: 100%;
    max-width: 300px;
}

.cart-summary__row--total {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    border-top: 1px solid #e5e5e5;
    margin-top: 8px;
    padding-top: 16px;
}

.cart-summary__row .price {
    font-weight: 600;
    color: #000;
}

.cart__buttons {
    width: 100%;
    max-width: 300px;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 32px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
}

.button--primary {
    background: #000;
    color: #fff;
    border-color: #000;
}

.button--primary:hover {
    background: #d12442;
    border-color: #d12442;
}

.button--full {
    width: 100%;
}

.cart__continue {
    width: 100%;
    max-width: 300px;
    text-align: center;
}

.link {
    color: #000;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.link--underline {
    text-decoration: underline;
}

.cart-recommendations {
    padding: 60px 0;
}

.cart-recommendations__title {
    text-align: center;
    margin-bottom: 32px;
}

.cart-recommendations__title h2 {
    font-size: 24px;
    font-weight: 700;
    color: #000;
}

.cart-recommendations__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 24px;
}

@media (min-width: 768px) {
    .cart-item {
        grid-template-columns: 100px 1fr auto auto auto auto;
        grid-template-rows: auto;
    }

    .cart-item__media {
        grid-row: 1;
    }

    .cart-item__details {
        grid-row: 1;
    }

    .cart-item__price {
        grid-row: 1;
    }

    .cart-item__quantity {
        grid-row: 1;
    }

    .cart-item__total {
        grid-row: 1;
    }

    .cart-item__remove {
        grid-row: 1;
    }
}

@media (max-width: 767px) {
    .cart-wrapper {
        padding: 0 16px;
    }

    .section-header__title {
        font-size: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const removeButtons = document.querySelectorAll('.cart-item__remove-button');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            removeFromCart(this.dataset.itemId);
        });
    });

    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const quantity = parseInt(this.value) || 0;
            updateQuantity(this.dataset.itemId, quantity);
        });
    });

    document.querySelectorAll('.quantity-button[name="minus"]').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentQty = parseInt(input.value) || 0;
            if (currentQty > 0) {
                input.value = currentQty - 1;
                input.dispatchEvent(new Event('change'));
            }
        });
    });

    document.querySelectorAll('.quantity-button[name="plus"]').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentQty = parseInt(input.value) || 0;
            input.value = currentQty + 1;
            input.dispatchEvent(new Event('change'));
        });
    });
});

function removeFromCart(itemId) {
    fetch(window.napoleonUrl('/api/cart/remove'), {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            item_id: itemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.items_count);
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateQuantity(itemId, quantity) {
    fetch(window.napoleonUrl('/api/cart/update'), {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            item_id: itemId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateCartCount(data.items_count);
            if (quantity === 0) {
                location.reload();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('[data-cart-count]');
    cartCountElements.forEach(el => {
        el.textContent = count;
    });

    if (count === 0) {
        window.location.href = '{{ route("cart.index") }}';
    }
}
</script>
@endsection
