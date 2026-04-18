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
        <div class="cart-added-popup__overlay" onclick="closeCartAddedPopup()"></div>
        <div class="cart-added-popup__content">
            <button class="cart-added-popup__close" onclick="closeCartAddedPopup()" aria-label="Cerrar">
                <svg viewBox="0 0 24 24" width="20" height="20"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/></svg>
            </button>
            <div class="cart-added-popup__body">
                <div class="cart-added-popup__image" id="popup-product-image"></div>
                <div class="cart-added-popup__details">
                    <p class="cart-added-popup__title" id="popup-title">Se agregó al carrito</p>
                    <p class="cart-added-popup__name" id="popup-product-name"></p>
                    <p class="cart-added-popup__price" id="popup-product-price"></p>
                </div>
            </div>
            <div class="cart-added-popup__actions">
                <a href="{{ route('cart.index') }}" class="cart-added-popup__btn cart-added-popup__btn--outline">Ver Carrito</a>
                <a href="{{ route('checkout.index') }}" class="cart-added-popup__btn cart-added-popup__btn--primary">Finalizar Pedido</a>
            </div>
        </div>
    </div>

    <style>
    .cart-added-popup {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cart-added-popup__overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.5);
    }
    .cart-added-popup__content {
        position: relative;
        background: #fff;
        width: 90%;
        max-width: 400px;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        animation: popupSlideIn 0.3s ease;
    }
    @keyframes popupSlideIn {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .cart-added-popup__close {
        position: absolute;
        top: 12px;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        color: #666;
    }
    .cart-added-popup__close:hover {
        background: #f5f5f5;
    }
    .cart-added-popup__body {
        display: flex;
        gap: 16px;
        padding: 24px;
        align-items: center;
    }
    .cart-added-popup__image {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        border-radius: 4px;
        overflow: hidden;
        background: #f5f5f5;
    }
    .cart-added-popup__image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cart-added-popup__details {
        flex: 1;
    }
    .cart-added-popup__title {
        font-size: 12px;
        color: #2e7d32;
        font-weight: 600;
        margin: 0 0 4px;
        text-transform: uppercase;
    }
    .cart-added-popup__name {
        font-size: 14px;
        font-weight: 600;
        color: #000;
        margin: 0 0 4px;
    }
    .cart-added-popup__price {
        font-size: 14px;
        font-weight: 600;
        color: #000;
        margin: 0;
    }
    .cart-added-popup__actions {
        display: flex;
        gap: 12px;
        padding: 0 24px 24px;
    }
    .cart-added-popup__btn {
        flex: 1;
        text-align: center;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 4px;
        text-transform: uppercase;
    }
    .cart-added-popup__btn--outline {
        background: transparent;
        color: #000;
        border: 1px solid #000;
    }
    .cart-added-popup__btn--outline:hover {
        background: #f5f5f5;
    }
    .cart-added-popup__btn--primary {
        background: #000;
        color: #fff;
        border: 1px solid #000;
    }
    .cart-added-popup__btn--primary:hover {
        background: #d12442;
        border-color: #d12442;
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
        var priceEl = document.getElementById('popup-product-price');
        
        if (nameEl) nameEl.textContent = productName || '';
        if (priceEl) priceEl.textContent = productPrice || '';
        
        if (imageEl) {
            if (productImage) {
                imageEl.innerHTML = '<img src="' + productImage + '" alt="' + (productName || '') + '">';
            } else {
                imageEl.innerHTML = '';
            }
        }
        
        popup.style.display = 'flex';
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
