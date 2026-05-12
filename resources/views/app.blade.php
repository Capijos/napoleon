<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Proyecto')</title>

    {{-- CSS y JS globales --}}
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])

    {{-- CSS extra por página --}}
    @stack('styles')
</head>

<body>
    @include('components.header')
    @include('partials.submenu')
    @include('partials.subsearch')
    @include('partials.subaccount')

    <main>
        @yield('content')
    </main>

    @include('components.footer')
    
    @include('components.mini-cart')

    <div id="cart-added-popup" class="cart-added-popup" role="dialog" aria-modal="true" aria-labelledby="popup-title" aria-hidden="true" style="display: none;">
        <div class="cart-added-popup__content">
            <button class="cart-added-popup__close" onclick="closeCartAddedPopup()" aria-label="Cerrar">
                <svg viewBox="0 0 24 24" width="16" height="16"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/></svg>
            </button>
            <div class="cart-added-popup__body">
                <div class="cart-added-popup__image" id="popup-product-image"></div>
                <div class="cart-added-popup__details">
                    <p class="cart-added-popup__name" id="popup-product-name"></p>
                    <p class="cart-added-popup__message">se ha agregado a tu carrito</p>
                    <div class="cart-added-popup__actions">
                        <button class="cart-added-popup__icon-btn cart-added-popup__icon-btn--secondary" onclick="closeCartAddedPopup()" aria-label="Seguir comprando">
                            <svg viewBox="0 0 24 24" width="18" height="18"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z" fill="currentColor"/><path d="M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4z" fill="currentColor"/></svg>
                            <span class="cart-added-popup__tooltip cart-added-popup__tooltip--right">Seguir comprando</span>
                        </button>
                        <a href="{{ route('cart.index') }}" class="cart-added-popup__icon-btn" aria-label="Ver carrito">
                            <svg viewBox="0 0 24 24" width="18" height="18"><path d="M18 6h-2c0-2.21-1.79-4-4-4S8 3.79 8 6H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-6-2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6 16H6V8h2v2c0 .55.45 1 1 1s1-.45 1-1V8h4v2c0 .55.45 1 1 1s1-.45 1-1V8h2v12z" fill="currentColor"/></svg>
                            <span class="cart-added-popup__tooltip">Ver carrito</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .cart-added-popup {
        position: fixed;
        top: 35px;
        right: 12px;
        z-index: 10000;
        display: none;
    }
    .cart-added-popup[aria-hidden="false"] {
        display: block;
    }
    .cart-added-popup__content {
        position: relative;
        background: #fff;
        border-radius: 0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        animation: popupSlideIn 0.3s ease;
        overflow: visible;
        display: flex;
        flex-direction: column;
        width: 380px;
        padding: 4px 0;
    }
    @keyframes popupSlideIn {
        from { transform: translateX(20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .cart-added-popup__close {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 18px;
        height: 18px;
        background: transparent;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #888;
        transition: color 0.2s;
        z-index: 2;
    }
    .cart-added-popup__close:hover {
        color: #333;
    }
    .cart-added-popup__body {
        display: flex;
        gap: 14px;
        padding: 12px 16px 10px;
        padding-right: 32px;
        align-items: flex-start;
    }
    .cart-added-popup__image {
        width: 110px;
        height: 110px;
        flex-shrink: 0;
        border-radius: 0;
        overflow: hidden;
        background: #f0f0f0;
    }
    .cart-added-popup__details {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .cart-added-popup__name {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin: 0;
        line-height: 1.3;
    }
    .cart-added-popup__message {
        font-size: 14px;
        font-weight: 500;
        color: #555;
        margin: 0;
    }
    .cart-added-popup__actions {
        display: flex;
        gap: 8px;
        margin-top: 4px;
    }
.cart-added-popup__icon-btn {
        position: relative;
        width: 40px;
        height: 40px;
        background: #d12442;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        flex-shrink: 0;
    }
    .cart-added-popup__icon-btn svg {
        transition: none;
    }
    .cart-added-popup__icon-btn--secondary {
        background: #d12442;
    }
    .cart-added-popup__tooltip {
        position: absolute;
        left: calc(100% + 8px);
        top: 50%;
        transform: translateY(-50%);
        background: #d12442;
        color: #fff;
        padding: 10px 18px;
        border-radius: 0;
        font-size: 16px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
        pointer-events: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .cart-added-popup__tooltip::after {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 8px solid transparent;
        border-right-color: #d12442;
    }
    .cart-added-popup__tooltip--right {
        left: calc(100% + 8px);
        z-index: 10001;
    }
    .cart-added-popup__tooltip--right::after {
        content: '';
        position: absolute;
        left: -16px;
        top: 50%;
        transform: translateY(-50%);
        border: 8px solid transparent;
        border-right-color: #d12442;
    }
    .cart-added-popup__icon-btn:hover .cart-added-popup__tooltip {
        opacity: 1;
        visibility: visible;
    }
    @media (max-width: 480px) {
        .cart-added-popup {
            top: auto;
            bottom: 20px;
            right: 10px;
            left: 10px;
        }
        .cart-added-popup__content {
            width: 100%;
        }
        .cart-added-popup__tooltip {
            display: none;
        }
    }
</style>

    <script>
    window.napoleonBasePath = '{{ rtrim(request()->getBaseUrl(), '/') }}';
    window.napoleonUrl = function(path) {
        var basePath = window.napoleonBasePath || '';
        return basePath + path;
    };

    window.closeCartAddedPopup = function() {
        var popup = document.getElementById('cart-added-popup');
        if (popup) {
            popup.style.display = 'none';
            popup.setAttribute('aria-hidden', 'true');
        }
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var popup = document.getElementById('cart-added-popup');
            if (popup && popup.style.display !== 'none') {
                window.closeCartAddedPopup();
            }
        }
    });

    document.addEventListener('click', function(e) {
        var popup = document.getElementById('cart-added-popup');
        if (popup && popup.style.display !== 'none') {
            var content = popup.querySelector('.cart-added-popup__content');
            if (content && !content.contains(e.target) && e.target.closest('.cart-added-popup__content') === null) {
                window.closeCartAddedPopup();
            }
        }
    });

    window.showCartAddedPopup = function(productName, productImage, productPrice) {
        var popup = document.getElementById('cart-added-popup');
        if (!popup) return;
        
        var nameEl = document.getElementById('popup-product-name');
        var imageEl = document.getElementById('popup-product-image');
        
        if (nameEl) nameEl.textContent = productName || '';
        
        if (imageEl) {
            if (productImage) {
                imageEl.innerHTML = '<img src="' + productImage + '" alt="' + (productName || '') + '">';
            } else {
                imageEl.innerHTML = '';
            }
        }
        
        popup.style.display = 'block';
        popup.setAttribute('aria-hidden', 'false');
        
        setTimeout(function() {
            var closeBtn = popup.querySelector('.cart-added-popup__close');
            if (closeBtn) closeBtn.focus();
        }, 100);
    };

    window.addToCart = function(options) {
        return new Promise(function(resolve, reject) {
            var productId = options.productId;
            var variantId = options.variantId || null;
            var quantity = options.quantity || 1;
            var buttonEl = options.buttonEl;

            fetch(window.napoleonUrl('/api/cart/add'), {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity
                })
            })
            .then(function(response) {
                return response.json().then(function(data) {
                    if (!response.ok) {
                        throw new Error(data.message || ('HTTP ' + response.status));
                    }

                    return data;
                });
            })
            .then(function(data) {
                if (buttonEl) {
                    buttonEl.classList.remove('loading');
                    buttonEl.disabled = false;
                    buttonEl.textContent = 'Agregar al Carrito';
                }

                if (data.success) {
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount(data.items_count);
                    }
                    if (typeof window.reloadMiniCart === 'function') {
                        window.reloadMiniCart();
                    }

                    var item = data.item || {};
                    var formattedPrice = '$' + Number(item.unit_price || 0).toLocaleString('es-CO');
                    window.showCartAddedPopup(item.name || '', item.image || '', formattedPrice);
                    resolve(data);
                } else {
                    reject(new Error(data.message || 'Error al agregar'));
                }
            })
            .catch(function(error) {
                if (buttonEl) {
                    buttonEl.classList.remove('loading');
                    buttonEl.disabled = false;
                    buttonEl.textContent = 'Agregar al Carrito';
                }
                reject(error);
            });
        });
    };

    document.addEventListener('click', function(e) {
        var atcBtn = e.target.closest('[data-cart-add]');
        if (atcBtn) {
            e.preventDefault();
            e.stopPropagation();

            var productId = atcBtn.dataset.productId;
            var variantId = atcBtn.dataset.variantId || null;
            var quantity = parseInt(atcBtn.dataset.quantity || '1', 10) || 1;

            if (atcBtn.dataset.quantityTarget) {
                var quantityInput = document.querySelector(atcBtn.dataset.quantityTarget);
                quantity = parseInt(quantityInput ? quantityInput.value : quantity, 10) || quantity;
            }

            if (!productId) return;

            atcBtn.classList.add('loading');
            atcBtn.textContent = 'Agregando al carrito...';

            if (typeof window.addToCart === 'function') {
                window.addToCart({
                    productId: productId,
                    variantId: variantId,
                    quantity: quantity,
                    buttonEl: atcBtn
                }).catch(function(err) {
                    console.error('Error:', err);
                });
            }
        }
    });
    </script>

    {{-- JS extra por página --}}
    @stack('scripts')
</body>

</html>
