<div class="mini-cart-overlay" onclick="closeMiniCart()" aria-hidden="true" tabindex="-1"></div>

<div class="mini-cart-drawer" id="mini-cart-drawer" role="dialog" aria-modal="true" aria-labelledby="mini-cart-title" aria-hidden="true">
    <div class="mini-cart-header">
        <h3 class="mini-cart-title" id="mini-cart-title">Tu Carrito</h3>
        <button class="mini-cart-close" onclick="closeMiniCart()" aria-label="Cerrar carrito" id="mini-cart-close-btn">
            <svg viewBox="0 0 24 24" class="icon">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill="currentColor"/>
            </svg>
        </button>
    </div>

    <div class="mini-cart-content" id="mini-cart-content">
        @include('components.mini-cart-content', [
            'items' => $items ?? [],
            'subtotal' => $subtotal ?? 0,
        ])
    </div>
</div>

<style>
.mini-cart-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
}

.mini-cart-overlay.is-open {
    opacity: 1;
    visibility: visible;
}

.mini-cart-drawer {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 400px;
    background: #fff;
    z-index: 9999;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
}

.mini-cart-drawer.is-open {
    transform: translateX(0);
}

.mini-cart-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e5e5;
}

.mini-cart-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.mini-cart-close {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
    border-radius: 50%;
}

.mini-cart-close:hover {
    background: #f5f5f5;
}

.mini-cart-close .icon {
    width: 20px;
    height: 20px;
    fill: #505050;
}

.mini-cart-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.mini-cart-empty {
    text-align: center;
    padding: 40px 20px;
}

.mini-cart-empty p {
    color: #505050;
    margin-bottom: 20px;
}

.mini-cart-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.mini-cart-item {
    display: flex;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f0f0f0;
}

.mini-cart-item__image,
.mini-cart-item__image-placeholder {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}

.mini-cart-item__image img,
.mini-cart-item__image-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e5e5e5;
    background: #f5f5f5;
}

.mini-cart-item__details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.mini-cart-item__name {
    font-size: 14px;
    font-weight: 500;
    color: #000;
}

.mini-cart-item__variant {
    font-size: 12px;
    color: #666;
}

.mini-cart-item__price {
    font-size: 14px;
    font-weight: 600;
    color: #000;
}

.mini-cart-item__actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.mini-cart-item__quantity {
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid #e5e5e5;
    border-radius: 4px;
}

.mini-cart-item__quantity .qty-btn,
.mini-cart-item__remove {
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    cursor: pointer;
}

.mini-cart-item__quantity .qty-btn:hover {
    background: #f5f5f5;
}

.mini-cart-item__quantity span {
    min-width: 24px;
    text-align: center;
    font-size: 14px;
}

.mini-cart-item__remove .icon {
    width: 16px;
    height: 16px;
    fill: #888;
}

.mini-cart-item__remove:hover .icon {
    fill: #d12442;
}

.mini-cart-footer {
    padding-top: 16px;
    border-top: 1px solid #e5e5e5;
    margin-top: auto;
}

.mini-cart-subtotal {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
}

.mini-cart-view-cart {
    display: block;
    text-align: center;
    margin-top: 12px;
    color: #000;
    text-decoration: underline;
    font-size: 14px;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 24px;
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

.mini-cart-toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: #f8d7da;
    color: #721c24;
    padding: 12px 20px;
    border-radius: 4px;
    font-size: 14px;
    z-index: 10001;
    opacity: 0;
    transition: transform 0.3s, opacity 0.3s;
}

.mini-cart-toast.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
}

.qty-btn:disabled,
.mini-cart-item__remove:disabled {
    opacity: 0.5;
    pointer-events: none;
}

@media (max-width: 480px) {
    .mini-cart-drawer {
        max-width: 100%;
    }
}
</style>

<script>
(function() {
    var miniCartRequestLock = false;
    var previousFocus = null;

    function fetchJson(url, options) {
        return fetch(url, Object.assign({
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }, options || {})).then(function(response) {
            return response.json().then(function(data) {
                if (!response.ok) {
                    throw new Error(data.message || 'Error en carrito');
                }

                return data;
            });
        });
    }

    function showMiniCartError(message) {
        var toast = document.createElement('div');
        toast.className = 'mini-cart-toast error';
        toast.textContent = message;
        toast.setAttribute('role', 'alert');
        document.body.appendChild(toast);

        setTimeout(function() {
            toast.classList.add('show');
        }, 10);

        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }

    window.updateCartCount = function(count) {
        document.querySelectorAll('[data-cart-count]').forEach(function(el) {
            el.textContent = count || 0;
        });
    };

    window.refreshCartCount = function() {
        return fetch(window.napoleonUrl('/api/cart/count'), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                window.updateCartCount(data.count || 0);
                return data;
            });
    };

    window.reloadMiniCart = function() {
        return fetch(window.napoleonUrl('/api/cart/mini'), {
            credentials: 'same-origin',
            headers: { 'Accept': 'text/html' }
        })
            .then(function(response) { return response.text(); })
            .then(function(html) {
                var content = document.getElementById('mini-cart-content');
                if (content) {
                    content.innerHTML = html;
                }

                return window.refreshCartCount();
            })
            .catch(function(error) {
                console.error('Error reloadMiniCart:', error);
            });
    };

    window.openMiniCart = function() {
        var drawer = document.getElementById('mini-cart-drawer');
        var overlay = document.querySelector('.mini-cart-overlay');

        if (!drawer || !overlay) {
            return;
        }

        previousFocus = document.activeElement;
        drawer.classList.add('is-open');
        overlay.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        window.reloadMiniCart();

        setTimeout(function() {
            var closeBtn = document.getElementById('mini-cart-close-btn');
            if (closeBtn) {
                closeBtn.focus();
            }
        }, 100);
    };

    window.closeMiniCart = function() {
        var drawer = document.getElementById('mini-cart-drawer');
        var overlay = document.querySelector('.mini-cart-overlay');

        if (drawer) {
            drawer.classList.remove('is-open');
            drawer.setAttribute('aria-hidden', 'true');
        }

        if (overlay) {
            overlay.classList.remove('is-open');
            overlay.setAttribute('aria-hidden', 'true');
        }

        document.body.style.overflow = '';

        if (previousFocus) {
            previousFocus.focus();
            previousFocus = null;
        }
    };

    window.updateMiniCartItem = function(itemId, quantity, buttonEl) {
        if (miniCartRequestLock) {
            return;
        }

        miniCartRequestLock = true;
        if (buttonEl) {
            buttonEl.disabled = true;
        }

        fetchJson(window.napoleonUrl('/api/cart/update'), {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId, quantity: quantity })
        })
            .then(function() {
                return window.reloadMiniCart();
            })
            .catch(function(error) {
                showMiniCartError(error.message || 'Error al actualizar');
            })
            .finally(function() {
                miniCartRequestLock = false;
                if (buttonEl) {
                    buttonEl.disabled = false;
                }
            });
    };

    window.removeMiniCartItem = function(itemId, buttonEl) {
        if (miniCartRequestLock) {
            return;
        }

        miniCartRequestLock = true;
        if (buttonEl) {
            buttonEl.disabled = true;
        }

        fetchJson(window.napoleonUrl('/api/cart/remove'), {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId })
        })
            .then(function() {
                return window.reloadMiniCart();
            })
            .catch(function(error) {
                showMiniCartError(error.message || 'Error al eliminar');
            })
            .finally(function() {
                miniCartRequestLock = false;
                if (buttonEl) {
                    buttonEl.disabled = false;
                }
            });
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            var drawer = document.getElementById('mini-cart-drawer');
            if (drawer && drawer.classList.contains('is-open')) {
                closeMiniCart();
            }
        }
    });

    document.addEventListener('click', function(e) {
        var qtyBtn = e.target.closest('.qty-btn');
        if (qtyBtn) {
            e.preventDefault();
            var itemEl = qtyBtn.closest('.mini-cart-item');
            var qtySpan = itemEl ? itemEl.querySelector('.mini-cart-item__quantity span') : null;
            var quantity = parseInt(qtySpan ? qtySpan.textContent : '1', 10) || 1;

            quantity = qtyBtn.classList.contains('plus') ? quantity + 1 : Math.max(1, quantity - 1);

            window.updateMiniCartItem(qtyBtn.dataset.itemId, quantity, qtyBtn);
            return;
        }

        var removeBtn = e.target.closest('.mini-cart-item__remove');
        if (removeBtn) {
            e.preventDefault();
            window.removeMiniCartItem(removeBtn.dataset.itemId, removeBtn);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        window.refreshCartCount();
    });
})();
</script>
