@extends('app')

@section('title', 'Finalizar Pedido - Napoleone Joyas')

@section('content')
@php
$formatPrice = fn ($price) => '$' . number_format((float) $price, 0, ',', '.');
@endphp

<main id="MainContent" class="content-for-layout focus-none" role="main" tabindex="-1">
    <div class="page-width page-width--narrow page-width--flush-small">
        <div class="section-header">
            <h1 class="section-header__title">
                Finalizar Pedido
            </h1>
        </div>

        <nav class="breadcrumb" role="navigation" aria-label="breadcrumbs">
            <a href="{{ url('/') }}" class="link">Inicio</a>
            <span class="separate" aria-hidden="true"></span>
            <a href="{{ route('cart.index') }}" class="link">Tu Carrito</a>
            <span class="separate" aria-hidden="true"></span>
            <span>Finalizar Pedido</span>
        </nav>

        <div class="checkout-page">
            <div class="checkout-grid">
                <div class="checkout-main">
                    <div class="checkout-step" data-step="contact-information">
                        <div class="checkout-step__header">
                            <h2 class="checkout-step__title">
                                <span class="step-number">1</span>
                                Información de Contacto
                            </h2>
                        </div>
                        <div class="checkout-step__content">
                            <div class="form-field">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-input"
                                    placeholder="tu@email.com"
                                >
                            </div>
                            <div class="form-field">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    class="form-input"
                                    placeholder="3101234567"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="checkout-step" data-step="shipping-address">
                        <div class="checkout-step__header">
                            <h2 class="checkout-step__title">
                                <span class="step-number">2</span>
                                Información de Envío
                            </h2>
                        </div>
                        <div class="checkout-step__content">
                            <div class="form-field">
                                <label for="first_name" class="form-label">Nombres</label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    class="form-input"
                                    placeholder="Juan"
                                >
                            </div>
                            <div class="form-field">
                                <label for="last_name" class="form-label">Apellidos</label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    class="form-input"
                                    placeholder="Pérez"
                                >
                            </div>
                            <div class="form-field">
                                <label for="address" class="form-label">Dirección</label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address" 
                                    class="form-input"
                                    placeholder="Carrera 50 #12-34"
                                >
                            </div>
                            <div class="form-field">
                                <label for="city" class="form-label">Ciudad</label>
                                <input 
                                    type="text" 
                                    id="city" 
                                    name="city" 
                                    class="form-input"
                                    placeholder="Medellín"
                                >
                            </div>
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="department" class="form-label">Departamento</label>
                                    <input 
                                        type="text" 
                                        id="department" 
                                        name="department" 
                                        class="form-input"
                                        placeholder="Antioquia"
                                    >
                                </div>
                                <div class="form-field">
                                    <label for="zip" class="form-label">Código Postal</label>
                                    <input 
                                        type="text" 
                                        id="zip" 
                                        name="zip" 
                                        class="form-input"
                                        placeholder="050001"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-step checkout-step--disabled" data-step="payment">
                        <div class="checkout-step__header">
                            <h2 class="checkout-step__title">
                                <span class="step-number">3</span>
                                Método de Pago
                            </h2>
                        </div>
                        <div class="checkout-step__content">
                            <div class="payment-methods">
                                <div class="payment-method payment-method--disabled">
                                    <div class="payment-method__header">
                                        <input 
                                            type="radio" 
                                            name="payment_method" 
                                            id="payment_credit_card"
                                            disabled
                                        >
                                        <label for="payment_credit_card" class="payment-method__label">
                                            <span class="payment-method__icon">
                                                <svg viewBox="0 0 24 24" class="icon icon-credit-card">
                                                    <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8h16v10zm-2-8h-4v4h4v-4z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <span class="payment-method__text">Tarjeta de Crédito/Débito</span>
                                        </label>
                                    </div>
                                    <div class="payment-method__content">
                                        <p class="payment-method__note">Próximamente disponible</p>
                                    </div>
                                </div>

                                <div class="payment-method payment-method--disabled">
                                    <div class="payment-method__header">
                                        <input 
                                            type="radio" 
                                            name="payment_method" 
                                            id="payment_mercado_pago"
                                            disabled
                                        >
                                        <label for="payment_mercado_pago" class="payment-method__label">
                                            <span class="payment-method__icon">
                                                <svg viewBox="0 0 24 24" class="icon icon-wallet">
                                                    <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <span class="payment-method__text">Mercado Pago</span>
                                        </label>
                                    </div>
                                    <div class="payment-method__content">
                                        <p class="payment-method__note">Próximamente disponible</p>
                                    </div>
                                </div>

                                <div class="payment-method payment-method--whatsapp">
                                    <div class="payment-method__header">
                                        <input 
                                            type="radio" 
                                            name="payment_method" 
                                            id="payment_whatsapp"
                                            value="whatsapp"
                                            checked
                                        >
                                        <label for="payment_whatsapp" class="payment-method__label">
                                            <span class="payment-method__icon">
                                                <svg viewBox="0 0 24 24" class="icon icon-whatsapp">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.197-.1-.495-.349-.945-.683-.741-.626-1.154-1.436-1.155-1.491v-.029c0-.015 0-.018-.013-.023-.297-.074-1.617-.393-3.064-1.153-.074-.049-.124-.073-.177.025-.052.099-.18.297-.225.358-.045.061-.15.074-.318.025-.297-.074-.608-.249-.938-.497-.055-.042-.093-.063-.134.063l-.867.867c.297.149.595.297.852.447.071.043.134.074.233.124.321.164.642.272 1.233.149.099-.025.198-.063.297-.124.297-.149 1.758-.867 2.03-.967.273-.099.471-.148.67.15.197.297.767.966.94 1.164.173.199.347.223.644.075.297-.1.495-.349.945-.683l.867-.867c.149-.149.297-.297.426-.446.065-.074.124-.148.173-.223.149-.074.347-.096.527-.049l.527.198c.074.049.148.124.223.272l1.154 2.372c.099.198.049.446-.025.612zM12.711 9.078c-.549-.274-1.282-.477-2.031-.264-.323.091-.654.203-.926.339-.084.037-.148.062-.234.062-.085 0-.17-.024-.234-.062-.595-.297-1.236-.512-1.984-.608-.297-.037-.64.025-.916.149l-.468.297c-.173.124-.37.223-.595.347-.15.086-.297.173-.446.273-.595.397-1.088.892-1.108 1.867-.025 1.133.818 2.136 1.523 2.978.705.841 1.631 1.481 2.806 1.808 1.182.327 2.254.273 3.114.164l.891-.124c.371-.074.694-.272.892-.496.198-.224.347-.495.446-.802l.099-.521c.025-.173-.025-.372-.124-.527l-.297-.447c-.074-.124-.17-.248-.272-.372-.099-.124-.198-.272-.297-.396l-.099-.124c-.149-.249-.297-.521-.495-.771l-.446-.521c-.198-.248-.396-.496-.644-.743l-.272-.272c-.297-.297-.595-.595-.916-.868l-.347-.297c-.124-.124-.224-.173-.272-.224l-.074-.198c0-.124.025-.248.124-.372l.297-.595c.099-.224.173-.447.247-.67l.025-.074c.074-.223.149-.445.223-.668l.074-.124c.074-.173.149-.347.223-.521l.173-.322c.074-.149.123-.274.173-.396l.099-.224c.048-.124.099-.198.149-.297l.099-.149c.074-.124.124-.224.198-.347l.149-.224c.049-.123.074-.198.123-.297l.025-.049c.124-.198.173-.372.272-.595l.074-.124c.074-.124.124-.198.173-.297.099-.198.198-.422.347-.668l.198-.322c.074-.124.149-.273.272-.446l.173-.272c.049-.074.074-.149.124-.223l.173-.272c.099-.149.173-.322.297-.521l.173-.223c.099-.173.224-.372.371-.595l.124-.173c.099-.198.224-.422.396-.668l.149-.223c.124-.198.272-.422.471-.668l.198-.223c.124-.149.272-.322.495-.521l.173-.149c.149-.124.347-.297.595-.447l.272-.174c.173-.124.371-.272.587-.421l.149-.099c.198-.149.421-.322.694-.471l.173-.099c.198-.124.446-.272.744-.372l.198-.074c.223-.074.47-.149.743-.198l.224-.049c.221-.049.471-.074.72-.074.249 0 .495.025.716.074l.224.049c.272.049.52.124.743.198l.198.074c.198.1.445.248.744.372l.173.099c.273.149.496.322.694.471l.149.099c.216.149.414.297.587.421l.272.174c.248.15.446.323.595.447l.173.149c.223.199.371.372.495.521l.198.223c.199.246.347.47.471.668l.149.223c.172.246.297.47.396.668l.124.173c.147.223.272.422.371.595l.173.223c.124.199.198.372.297.521l.173.272c.049.074.074.149.124.223l.272.272c.248.247.446.495.644.743l.446.521c.198.25.346.522.495.771l.099.124c.099.124.198.272.297.396.099.124.198.248.272.372l.297.447c.099.155.149.354.124.527l-.099.521c-.099.307-.248.578-.446.802-.198.224-.521.422-.892.496l-.891.124c-.86.109-1.932.164-3.114-.164-1.175-.327-2.101-1.167-2.806-1.808-.705-.842-1.548-1.845-1.523-2.978.025-.975.513-1.47 1.108-1.867l.446-.273c.198-.124.422-.272.644-.397.297-.174.446-.124.595-.025l-.074.049c.124.223.248.445.347.668.099.173.149.347.223.52l.173.322c.074.174.149.348.223.521l.025.049c.049.099.074.174.123.297l.149.224c.074.123.124.223.198.347l.099.149c.05.099.101.173.149.297l.173.396c.05.122.099.247.173.396l.173.322c.074.176.149.347.223.521l.074.124c.074.223.149.445.272.595.124.198.198.372.272.595l.025.049c.049.099.074.174.123.297l-.025.049c.049.099.074.174.099.149z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <span class="payment-method__text">WhatsApp</span>
                                        </label>
                                    </div>
                                    <div class="payment-method__content">
                                        <p class="payment-method__note">Envía tu pedido por WhatsApp y nuestro equipo te contactará para confirmar el pago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-sidebar">
                    <div class="checkout-summary">
                        <h2 class="checkout-summary__title">
                            Resumen del Pedido
                        </h2>
                        
                        <div class="checkout-items">
                            @foreach($items as $item)
                            <div class="checkout-item">
                                <div class="checkout-item__image">
                                    <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}">
                                    <span class="checkout-item__quantity">{{ $item['quantity'] }}</span>
                                </div>
                                <div class="checkout-item__details">
                                    <span class="checkout-item__name">{{ $item['name'] }}</span>
                                    @if(!empty($item['variant_name']))
                                    <span class="checkout-item__variant">{{ $item['variant_name'] }}</span>
                                    @endif
                                </div>
                                <div class="checkout-item__price">
                                    {{ $formatPrice($item['subtotal']) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="checkout-totals">
                            <div class="checkout-total">
                                <span>Subtotal</span>
                                <span>{{ $formatPrice($subtotal) }}</span>
                            </div>
                            <div class="checkout-total">
                                <span>Envío</span>
                                <span>Calculado en WhatsApp</span>
                            </div>
                            @if($discount > 0)
                            <div class="checkout-total checkout-total--discount">
                                <span>Descuento</span>
                                <span>-{{ $formatPrice($discount) }}</span>
                            </div>
                            @endif
                            <div class="checkout-total checkout-total--final">
                                <span>Total</span>
                                <span>{{ $formatPrice($total > 0 ? $total : $subtotal) }}</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('checkout.whatsapp') }}">
                            @csrf
                            <button type="submit" class="button button--whatsapp button--full">
                                <svg viewBox="0 0 24 24" class="icon icon-whatsapp">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.197-.1-.495-.349-.945-.683-.741-.626-1.154-1.436-1.155-1.491v-.029c0-.015 0-.018-.013-.023-.297-.074-1.617-.393-3.064-1.153-.074-.049-.124-.073-.177.025-.052.099-.18.297-.225.358-.045.061-.15.074-.318.025-.297-.074-.608-.249-.938-.497-.055-.042-.093-.063-.134.063l-.867.867c.297.149.595.297.852.447.071.043.134.074.233.124.321.164.642.272 1.233.149.099-.025.198-.063.297-.124.297-.149 1.758-.867 2.03-.967.273-.099.471-.148.67.15.197.297.767.966.94 1.164.173.199.347.223.644.075.297-.1.495-.349.945-.683l.867-.867c.149-.149.297-.297.426-.446.065-.074.124-.148.173-.223.149-.074.347-.096.527-.049l.527.198c.074.049.148.124.223.272l1.154 2.372c.099.198.049.446-.025.612zM12.711 9.078c-.549-.274-1.282-.477-2.031-.264-.323.091-.654.203-.926.339-.084.037-.148.062-.234.062-.085 0-.17-.024-.234-.062-.595-.297-1.236-.512-1.984-.608-.297-.037-.64.025-.916.149l-.468.297c-.173.124-.37.223-.595.347-.15.086-.297.173-.446.273-.595.397-1.088.892-1.108 1.867-.025 1.133.818 2.136 1.523 2.978.705.841 1.631 1.481 2.806 1.808 1.182.327 2.254.273 3.114.164l.891-.124c.371-.074.694-.272.892-.496.198-.224.347-.495.446-.802l.099-.521c.025-.173-.025-.372-.124-.527l-.297-.447c-.074-.124-.17-.248-.272-.372-.099-.124-.198-.272-.297-.396l-.099-.124c-.149-.249-.297-.521-.495-.771l-.446-.521c-.198-.248-.396-.496-.644-.743l-.272-.272c-.297-.297-.595-.595-.916-.868l-.347-.297c-.124-.124-.224-.173-.272-.224l-.074-.198c0-.124.025-.248.124-.372l.297-.595c.099-.224.173-.447.247-.67l.025-.074c.074-.223.149-.445.223-.668l.074-.124c.074-.173.149-.347.223-.521l.173-.322c.074-.149.123-.274.173-.396l.099-.224c.048-.124.099-.198.149-.297l.099-.149c.074-.124.124-.224.198-.347l.149-.224c.049-.123.074-.198.123-.297l.025-.049c.124-.198.173-.372.272-.595l.074-.124c.074-.124.124-.198.173-.297.099-.198.198-.422.347-.668l.198-.322c.074-.124.149-.273.272-.446l.173-.272c.049-.074.074-.149.124-.223l.173-.272c.099-.149.173-.322.297-.521l.173-.223c.099-.173.224-.372.371-.595l.124-.173c.099-.198.224-.422.396-.668l.149-.223c.124-.198.272-.422.471-.668l.198-.223c.124-.149.272-.322.495-.521l.173-.149c.149-.124.347-.297.595-.447l.272-.174c.173-.124.371-.272.587-.421l.149-.099c.198-.149.421-.322.694-.471l.173-.099c.198-.124.446-.272.744-.372l.198-.074c.223-.074.47-.149.743-.198l.224-.049c.221-.049.471-.074.72-.074.249 0 .495.025.716.074l.224.049c.272.049.52.124.743.198l.198.074c.198.1.445.248.744.372l.173.099c.273.149.496.322.694.471l.149.099c.216.149.414.297.587.421l.272.174c.248.15.446.323.595.447l.173.149c.223.199.371.372.495.521l.198.223c.199.246.347.47.471.668l.149.223c.172.246.297.47.396.668l.124.173c.147.223.272.422.371.595l.173.223c.124.199.198.372.297.521l.173.272c.049.074.074.149.124.223l.272.272c.248.247.446.495.644.743l.446.521c.198.25.346.522.495.771l.099.124c.099.124.198.272.297.396.099.124.198.248.272.372l.297.447c.099.155.149.354.124.527l-.099.521c-.099.307-.248.578-.446.802-.198.224-.521.422-.892.496l-.891.124c-.86.109-1.932.164-3.114-.164-1.175-.327-2.101-1.167-2.806-1.808-.705-.842-1.548-1.845-1.523-2.978.025-.975.513-1.47 1.108-1.867l.446-.273c.198-.124.422-.272.644-.397.297-.174.446-.124.595-.025z" fill="currentColor"/>
                                </svg>
                                Enviar Pedido por WhatsApp
                            </button>
                        </form>

                        <p class="checkout-summary__note">
                            Al hacer clic en "Enviar Pedido por WhatsApp", serás redirigido a WhatsApp para completar tu pedido con nuestro equipo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

<style>
.checkout-page {
    padding: 30px 0;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 40px;
}

@media (min-width: 1024px) {
    .checkout-grid {
        grid-template-columns: 1fr 400px;
    }

    .checkout-main {
        order: 1;
    }

    .checkout-sidebar {
        order: 2;
    }
}

.section-header {
    text-align: center;
    padding: 30px 0 20px;
}

.section-header__title {
    font-size: 32px;
    font-weight: 700;
    color: #000;
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

.checkout-step {
    border: 1px solid #e5e5e5;
    margin-bottom: 20px;
}

.checkout-step--disabled {
    opacity: 0.6;
}

.checkout-step__header {
    padding: 16px 20px;
    border-bottom: 1px solid #e5e5e5;
    background: #f9f9f9;
}

.checkout-step__title {
    font-size: 16px;
    font-weight: 600;
    color: #000;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: #000;
    color: #fff;
    border-radius: 50%;
    font-size: 12px;
    font-weight: 600;
}

.checkout-step__content {
    padding: 20px;
}

.form-field {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #000;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #e5e5e5;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #000;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-method {
    border: 1px solid #e5e5e5;
    border-radius: 4px;
    overflow: hidden;
}

.payment-method--disabled {
    opacity: 0.5;
    pointer-events: none;
}

.payment-method__header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    cursor: pointer;
}

.payment-method__header input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #000;
}

.payment-method__label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    flex: 1;
}

.payment-method__icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f5f5f5;
    border-radius: 4px;
}

.payment-method__icon svg {
    width: 20px;
    height: 20px;
    fill: #505050;
}

.payment-method__text {
    font-size: 14px;
    font-weight: 500;
    color: #000;
}

.payment-method__content {
    padding: 0 16px 16px;
}

.payment-method__note {
    font-size: 13px;
    color: #777;
    font-style: italic;
    margin: 0;
}

.checkout-summary {
    background: #f9f9f9;
    padding: 24px;
    border-radius: 4px;
}

.checkout-summary__title {
    font-size: 18px;
    font-weight: 600;
    color: #000;
    margin: 0 0 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e5e5;
}

.checkout-items {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e5e5;
}

.checkout-item {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
    align-items: center;
}

.checkout-item:last-child {
    margin-bottom: 0;
}

.checkout-item__image {
    position: relative;
    width: 64px;
    height: 64px;
    flex-shrink: 0;
}

.checkout-item__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e5e5e5;
}

.checkout-item__quantity {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 20px;
    height: 20px;
    background: #d12442;
    color: #fff;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

.checkout-item__details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.checkout-item__name {
    font-size: 13px;
    font-weight: 500;
    color: #000;
}

.checkout-item__variant {
    font-size: 12px;
    color: #666;
}

.checkout-item__price {
    font-size: 13px;
    font-weight: 600;
    color: #000;
}

.checkout-totals {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e5e5e5;
}

.checkout-total {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    font-size: 14px;
    color: #505050;
    margin-bottom: 12px;
}

.checkout-total:last-child {
    margin-bottom: 0;
}

.checkout-total--discount {
    color: #2e7d32;
}

.checkout-total--final {
    font-size: 18px;
    font-weight: 700;
    color: #000;
    padding-top: 12px;
    border-top: 1px solid #e5e5e5;
    margin-top: 12px;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 32px;
    font-size: 14px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
}

.button--whatsapp {
    background: #25D366;
    color: #fff;
    border-color: #25D366;
}

.button--whatsapp:hover {
    background: #128C7E;
    border-color: #128C7E;
}

.button--full {
    width: 100%;
}

.checkout-summary__note {
    font-size: 12px;
    color: #666;
    margin-top: 16px;
    text-align: center;
}

@media (max-width: 767px) {
    .section-header__title {
        font-size: 24px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .checkout-summary {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 16px;
        z-index: 100;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
    }

    .checkout-grid {
        gap: 0;
    }

    .checkout-sidebar {
        display: none;
    }
}
</style>
