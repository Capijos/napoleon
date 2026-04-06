@extends('app')

@section('content')
@php
    $productHandle = $product->shopify_handle ?? $product->slug;
    $productId = $product->shopify_id ?? $product->id;
    $variantId = $defaultVariant['variant_id'] ?? '';
    $sku = $defaultVariant['sku'] ?? null;
    $inventoryQty = $availableVariants->count();

    $formattedBasePrice = '$' . number_format((float) $basePrice, 0, ',', '.');
    $formattedFinalPrice = '$' . number_format((float) $finalPrice, 0, ',', '.');

    $comparePrice = $defaultVariant['compare_price'] ?? null;
    $formattedComparePrice = $comparePrice
        ? '$' . number_format((float) $comparePrice, 0, ',', '.')
        : null;

    $gallery = is_array($gallery ?? null) ? $gallery : [];
@endphp

<main
    id="MainContent"
    class="content-for-layout focus-none"
    role="main"
    tabindex="-1"
>
    <div
        id="shopify-section-template--17315347726451__main"
        class="shopify-section"
    >
        <div
            data-section-id="template--17315347726451__main"
            data-section-type="product"
            id="ProductSection-template--17315347726451__main"
            class="halo-page-padding"
            style="
                --padding-top: 0px;
                --padding-bottom: 0px;
                --padding-top-tablet: 0px;
                --padding-bottom-tablet: 0px;
                --padding-top-mobile: 0px;
                --padding-bottom-mobile: 0px;

                --add-to-cart-font: var(--font-1-family);

                --add-to-cart-font-size: 16px;
                --add-to-cart-font-weight: 700;
                --add-to-cart-text-transform: uppercase;
                --add-to-cart-bg: #000000;
                --add-to-cart-color: #ffffff;
                --add-to-cart-border: #000000;
                --add-to-cart-bg-hover: #d12442;
                --add-to-cart-color-hover: #ffffff;
                --add-to-cart-border-hover: #d12442;
                --buy-it-now-bg: #e5e5e5;
                --buy-it-now-color: #000000;
                --buy-it-now-border: #e5e5e5;
                --buy-it-now-bg-hover: #d12442;
                --buy-it-now-color-hover: #ffffff;
                --buy-it-now-border-hover: #d12442;
                --main-wishlist-border-radius: 0px;
                --main-wishlist-bg: #ffffff;
                --main-wishlist-color: #000000;
                --main-wishlist-border: #c7c7c7;
                --main-wishlist-bg-hover: #d12442;
                --main-wishlist-color-hover: #ffffff;
                --main-wishlist-border-hover: #d12442;
                --product-image-object-fit: fill;
                --btn-min-height: 60px;
            "
        >
            <div class="container container-1770">
                <nav
                    class="breadcrumb breadcrumb-left breadcrumb-full disable-scrollbar"
                    role="navigation"
                    aria-label="breadcrumbs"
                >
                    @foreach ($breadcrumbs as $index => $crumb)
                        @if (!empty($crumb['url']))
                            <a class="link" href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                        @else
                            <span>{{ $crumb['label'] }}</span>
                        @endif

                        @if ($index < count($breadcrumbs) - 1)
                            <span class="separate" aria-hidden="true"></span>
                        @endif
                    @endforeach
                </nav>

                <div
                    class="productView halo-productView layout-3 productView-sticky"
                    data-product-handle="{{ $productHandle }}"
                    data-product-detail=""
                    data-variant-image-grouped="true"
                >
                    <div class="productView-top">
                        <div
                            class="halo-productView-left productView-images clearfix"
                            data-image-gallery=""
                        >
                            <div class="productView-images-wrapper">
                                <div class="productView-image-wrapper">
                                    <div
                                        class="productView-badge badge-left halo-productBadges halo-productBadges--left style-2"
                                        data-new-badge-number="30"
                                    ></div>

                                    <div
                                        class="productView-nav slick-initialized slick-slider"
                                        data-image-gallery-main=""
                                        data-arrow="false"
                                        data-dot="false"
                                    >
                                        <div class="slick-list draggable">
                                            <div class="slick-track" style="opacity: 1;">
                                                @foreach ($gallery as $index => $image)
                                                    <div
                                                        class="productView-image is-image productView-image-square productView-image-zoomed slick-slide {{ $index === 0 ? 'slick-current slick-active' : '' }} pl-parent"
                                                        data-index="{{ $index + 1 }}"
                                                        data-slick-index="{{ $index }}"
                                                        aria-hidden="{{ $index === 0 ? 'false' : 'true' }}"
                                                        tabindex="{{ $index === 0 ? '0' : '-1' }}"
                                                    >
                                                        <div
                                                            class="productView-img-container product-single__media"
                                                            style="padding-bottom: 100%"
                                                        >
                                                            <div
                                                                class="media"
                                                                data-zoom-image="{{ $image }}"
                                                                data-fancybox="images"
                                                                href="{{ $image }}"
                                                                style="position: absolute; overflow: hidden"
                                                            >
                                                                <img
                                                                    id="product-featured-image-{{ $index }}"
                                                                    src="{{ $image }}"
                                                                    alt="{{ $product->name }}"
                                                                    title="{{ $product->name }}"
                                                                    sizes="(min-width: 1200px) 1200px, (min-width: 768px) calc((100vw - 30px) / 2), calc(100vw - 30px)"
                                                                    width="1280"
                                                                    height="1280"
                                                                    data-main-image=""
                                                                    data-index="{{ $index + 1 }}"
                                                                    data-cursor-image=""
                                                                    @if($index > 0) loading="lazy" @endif
                                                                />
                                                            </div>
                                                        </div>
                                                        <div class="pl-container pl-product"></div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <share-button
                                        class="share-button share-button__absolute"
                                        id="Share-template--17315347726451__main"
                                    >
                                        <button class="share-button__button button">
                                            <svg
                                                viewBox="0 0 227.216 227.216"
                                                class="icon icon-share"
                                                fill="none"
                                                xmlns="http://www.w3.org/2000/svg"
                                                aria-hidden="true"
                                                focusable="false"
                                            >
                                                <path
                                                    d="M175.897,141.476c-13.249,0-25.11,6.044-32.98,15.518l-51.194-29.066c1.592-4.48,2.467-9.297,2.467-14.317c0-5.019-0.875-9.836-2.467-14.316l51.19-29.073c7.869,9.477,19.732,15.523,32.982,15.523c23.634,0,42.862-19.235,42.862-42.879C218.759,19.229,199.531,0,175.897,0C152.26,0,133.03,19.229,133.03,42.865c0,5.02,0.874,9.838,2.467,14.319L84.304,86.258c-7.869-9.472-19.729-15.514-32.975-15.514c-23.64,0-42.873,19.229-42.873,42.866c0,23.636,19.233,42.865,42.873,42.865c13.246,0,25.105-6.042,32.974-15.513l51.194,29.067c-1.593,4.481-2.468,9.3-2.468,14.321c0,23.636,19.23,42.865,42.867,42.865c23.634,0,42.862-19.23,42.862-42.865C218.759,160.71,199.531,141.476,175.897,141.476z M175.897,15c15.363,0,27.862,12.5,27.862,27.865c0,15.373-12.499,27.879-27.862,27.879c-15.366,0-27.867-12.506-27.867-27.879C148.03,27.5,160.531,15,175.897,15z M51.33,141.476c-15.369,0-27.873-12.501-27.873-27.865c0-15.366,12.504-27.866,27.873-27.866c15.363,0,27.861,12.5,27.861,27.866C79.191,128.975,66.692,141.476,51.33,141.476z M175.897,212.216c-15.366,0-27.867-12.501-27.867-27.865c0-15.37,12.501-27.875,27.867-27.875c15.363,0,27.862,12.505,27.862,27.875C203.759,199.715,191.26,212.216,175.897,212.216z"
                                                ></path>
                                            </svg>
                                            Compartir
                                        </button>

                                        <details id="Details-template--17315347726451__main">
                                            <summary class="share-button__button">
                                                <svg
                                                    viewBox="0 0 227.216 227.216"
                                                    class="icon icon-share"
                                                    fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    aria-hidden="true"
                                                    focusable="false"
                                                >
                                                    <path
                                                        d="M175.897,141.476c-13.249,0-25.11,6.044-32.98,15.518l-51.194-29.066c1.592-4.48,2.467-9.297,2.467-14.317c0-5.019-0.875-9.836-2.467-14.316l51.19-29.073c7.869,9.477,19.732,15.523,32.982,15.523c23.634,0,42.862-19.235,42.862-42.879C218.759,19.229,199.531,0,175.897,0C152.26,0,133.03,19.229,133.03,42.865c0,5.02,0.874,9.838,2.467,14.319L84.304,86.258c-7.869-9.472-19.729-15.514-32.975-15.514c-23.64,0-42.873,19.229-42.873,42.866c0,23.636,19.233,42.865,42.873,42.865c13.246,0,25.105-6.042,32.974-15.513l51.194,29.067c-1.593,4.481-2.468,9.3-2.468,14.321c0,23.636,19.23,42.865,42.867,42.865c23.634,0,42.862-19.23,42.862-42.865C218.759,160.71,199.531,141.476,175.897,141.476z M175.897,15c15.363,0,27.862,12.5,27.862,27.865c0,15.373-12.499,27.879-27.862,27.879c-15.366,0-27.867-12.506-27.867-27.879C148.03,27.5,160.531,15,175.897,15z M51.33,141.476c-15.369,0-27.873-12.501-27.873-27.865c0-15.366,12.504-27.866,27.873-27.866c15.363,0,27.861,12.5,27.861,27.866C79.191,128.975,66.692,141.476,51.33,141.476z M175.897,212.216c-15.366,0-27.867-12.501-27.867-27.865c0-15.37,12.501-27.875,27.867-27.875c15.363,0,27.862,12.505,27.862,27.875C203.759,199.715,191.26,212.216,175.897,212.216z"
                                                    ></path>
                                                </svg>
                                                Compartir
                                            </summary>

                                            <div
                                                id="Article-share-template--17315347726451__main"
                                                class="share-button__fallback motion-reduce"
                                            >
                                                <div class="form-field">
                                                    <span
                                                        id="ShareMessage-template--17315347726451__main"
                                                        class="share-button__message hidden"
                                                        role="status"
                                                    ></span>
                                                    <input
                                                        type="text"
                                                        class="field__input"
                                                        id="url"
                                                        value="{{ url()->current() }}"
                                                        placeholder="Compartir URL"
                                                        onclick="this.select()"
                                                        readonly=""
                                                    />
                                                    <label class="field__label hiddenLabels" for="url">
                                                        Compartir URL
                                                    </label>
                                                </div>
                                                <button class="share-button__close hidden no-js-hidden">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 48 48"
                                                        aria-hidden="true"
                                                        focusable="false"
                                                        role="presentation"
                                                    >
                                                        <path
                                                            d="M 38.982422 6.9707031 A 2.0002 2.0002 0 0 0 37.585938 7.5859375 L 24 21.171875 L 10.414062 7.5859375 A 2.0002 2.0002 0 0 0 8.9785156 6.9804688 A 2.0002 2.0002 0 0 0 7.5859375 10.414062 L 21.171875 24 L 7.5859375 37.585938 A 2.0002 2.0002 0 1 0 10.414062 40.414062 L 24 26.828125 L 37.585938 40.414062 A 2.0002 2.0002 0 1 0 40.414062 37.585938 L 26.828125 24 L 40.414062 10.414062 A 2.0002 2.0002 0 0 0 38.982422 6.9707031 z"
                                                        ></path>
                                                    </svg>
                                                    <span class="visually-hidden">Cerrar</span>
                                                </button>
                                                <button class="share-button__copy no-js-hidden">
                                                    <svg
                                                        class="icon icon-clipboard"
                                                        width="11"
                                                        height="13"
                                                        fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        aria-hidden="true"
                                                        focusable="false"
                                                    >
                                                        <path
                                                            fill-rule="evenodd"
                                                            clip-rule="evenodd"
                                                            d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z"
                                                            fill="currentColor"
                                                        ></path>
                                                    </svg>
                                                    <span class="visually-hidden">Copiar URL al portapapeles</span>
                                                </button>
                                            </div>
                                        </details>
                                    </share-button>
                                </div>

                                <div class="productView-thumbnail-wrapper">
                                    <div class="productView-for clearfix slick-initialized slick-slider">
                                        <div class="slick-list draggable">
                                            <div class="slick-track" style="opacity: 1;">
                                                @foreach ($gallery as $index => $image)
                                                    <div
                                                        class="productView-thumbnail slick-slide {{ $index === 0 ? 'slick-current slick-active' : 'slick-active' }}"
                                                        data-index="{{ $index + 1 }}"
                                                        style="width: 112px"
                                                        tabindex="0"
                                                        data-slick-index="{{ $index }}"
                                                        aria-hidden="false"
                                                    >
                                                        <a
                                                            class="productView-thumbnail-link"
                                                            href="javascript:void(0)"
                                                            data-image="{{ $image }}"
                                                            role="button"
                                                            style="--padding-bottom: 100%"
                                                            tabindex="0"
                                                        >
                                                            <img
                                                                src="{{ $image }}"
                                                                alt="{{ $product->name }}"
                                                                title="{{ $product->name }}"
                                                                data-index="{{ $index + 1 }}"
                                                                data-sizes="auto"
                                                                @if($index > 0) loading="lazy" @endif
                                                            />
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="halo-productView-right productView-details clearfix">
                            <div class="productView-product clearfix">
                                <div class="productView-options productView-options-2 pl-badge-container pl-badge-product">
                                    <h1
                                        class="productView-title element-spacing"
                                        style="
                                            --spacing-top: 0px;
                                            --spacing-bottom: 14px;
                                            --spacing-top-mobile: 0px;
                                            --spacing-bottom-mobile: 7px;
                                            --spacing-top-tablet: 0px;
                                            --spacing-bottom-tablet: 11px;

                                            --font-family: var(--font-2-family);

                                            --font-size: 26px;
                                            --font-weight: 700;
                                            --text-transform: none;
                                            --text-color: #000000;
                                        "
                                    >
                                        {{ $product->name }}
                                    </h1>

                                    <div
                                        class="productView-promotion clearfix"
                                        style="
                                            --spacing-top: 0px;
                                            --spacing-bottom: 0px;
                                            --spacing-top-mobile: 0px;
                                            --spacing-bottom-mobile: 0px;
                                            --spacing-top-tablet: 0px;
                                            --spacing-bottom-tablet: 0px;
                                        "
                                    >
                                        <div class="productView-promotionItem">
                                            <product-rating
                                                class="productView-rating halo-productReview"
                                                data-target="#halo-product-review"
                                            >
                                                <span class="spr-badge" id="spr_badge_{{ $productId }}" data-rating="5.0">
                                                    <span class="spr-starrating spr-badge-starrating">
                                                        <i class="spr-icon spr-icon-star" role="none"></i>
                                                        <i class="spr-icon spr-icon-star" role="none"></i>
                                                        <i class="spr-icon spr-icon-star" role="none"></i>
                                                        <i class="spr-icon spr-icon-star" role="none"></i>
                                                        <i class="spr-icon spr-icon-star" role="none"></i>
                                                    </span>
                                                    <span class="spr-badge-caption">1 reviews</span>
                                                </span>
                                            </product-rating>
                                        </div>
                                    </div>

                                    <div
                                        class="productView-info element-spacing"
                                        style="
                                            --spacing-top: 8px;
                                            --spacing-bottom: 0px;
                                            --spacing-top-mobile: 4px;
                                            --spacing-bottom-mobile: 0px;
                                            --spacing-top-tablet: 6px;
                                            --spacing-bottom-tablet: 0px;

                                            --font-family: var(--font-1-family);

                                            --font-size: 16px;
                                            --font-weight: 400;
                                            --text-transform: none;
                                            --text-color: #505050;
                                            --text-color-2: #000000;
                                        "
                                    >
                                        @if($sku)
                                            <div class="productView-info-item" data-sku="">
                                                <span class="productView-info-name">SKU:</span>
                                                <span class="productView-info-value">{{ $sku }}</span>
                                            </div>
                                        @endif

                                        <div class="productView-info-item" data-inventory="">
                                            <span class="productView-info-name">Disponibilidad:</span>
                                            <span class="productView-info-value">{{ $stockStatus }}</span>
                                        </div>
                                    </div>

                                    <div
                                        class="productView-price no-js-hidden clearfix element-spacing"
                                        id="product-price-template--17315347726451__main-{{ $productId }}"
                                        style="
                                            --spacing-top: 5px;
                                            --spacing-bottom: 15px;
                                            --spacing-top-mobile: 3px;
                                            --spacing-bottom-mobile: 8px;
                                            --spacing-top-tablet: 4px;
                                            --spacing-bottom-tablet: 11px;
                                        "
                                    >
                                        <div class="price price--large">
                                            <dl>
                                                <div class="price__regular">
                                                    <dt>
                                                        <span class="visually-hidden visually-hidden--inline">Precio habitual</span>
                                                    </dt>
                                                    <dd class="price__last">
                                                        <span class="price-item price-item--regular">
                                                            {{ $formattedBasePrice }}
                                                        </span>
                                                    </dd>
                                                </div>

                                                <div class="price__sale">
                                                    <dt class="price__compare">
                                                        <span class="visually-hidden visually-hidden--inline">Precio habitual</span>
                                                    </dt>
                                                    <dd class="price__compare">
                                                        <s class="price-item price-item--regular">
                                                            {{ $formattedComparePrice }}
                                                        </s>
                                                    </dd>
                                                    <dt>
                                                        <span class="visually-hidden visually-hidden--inline">Precio de venta</span>
                                                    </dt>
                                                    <dd class="price__last">
                                                        <span class="price-item price-item--sale">
                                                            {{ $formattedFinalPrice }}
                                                        </span>
                                                    </dd>
                                                </div>

                                                <small class="unit-price caption hidden">
                                                    <dt class="visually-hidden">Precio unitario</dt>
                                                    <dd class="price__last">
                                                        <span role="none"></span>
                                                        <span aria-hidden="true">/</span>
                                                        <span class="visually-hidden">&nbsp;por&nbsp;</span>
                                                        <span role="none"></span>
                                                    </dd>
                                                </small>
                                            </dl>
                                        </div>
                                    </div>

                                    @if($lowStockMessage)
                                        <div
                                            class="productView-inventory element-spacing"
                                            style="
                                                --spacing-top: 0px;
                                                --spacing-bottom: 35px;
                                                --spacing-top-mobile: 0px;
                                                --spacing-bottom-mobile: 18px;
                                                --spacing-top-tablet: 0px;
                                                --spacing-bottom-tablet: 26px;
                                                --text-color: #d12442;
                                                --bg-color: #d2d5d9;
                                                --icon-color: ;
                                                --progress-bg-color: linear-gradient(
                                                    96deg,
                                                    rgba(214, 154, 72, 1),
                                                    rgba(249, 226, 133, 1) 52%,
                                                    rgba(237, 181, 65, 1) 78%,
                                                    rgba(214, 180, 79, 1) 100%
                                                );
                                                --progress-height: 5px;
                                            "
                                            id="product-inventory-template--17315347726451__main-{{ $productId }}"
                                        >
                                            <div class="productView-inventory-wrapper">
                                                <div class="text">
                                                    {{ $lowStockMessage }}
                                                </div>
                                                <div
                                                    class="progress"
                                                    data-stock="{{ $inventoryQty }}"
                                                    data-max-stock="3"
                                                    data-progress-percent="{{ min(($inventoryQty / 3) * 100, 100) }}%"
                                                    style="--progress-percent: {{ min(($inventoryQty / 3) * 100, 100) }}%"
                                                ></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div
                                        id="shopify-block-AZy8raFhQWmFrSTdld__dealeasy_cvg_progress_bar_XtqcQP"
                                        class="shopify-block shopify-app-block"
                                    >
                                        <div id="lb-cart-progress-bar"></div>
                                    </div>

                                    <div class="productView-sizeChart">
                                        <custom-modal-opener
                                            class="link link-underline"
                                            data-modal="#Modal-SizeChart"
                                        >
                                            <span class="link link-underline clearfix" data-modal-opener="">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 80 80"
                                                    aria-hidden="true"
                                                    focusable="false"
                                                    role="presentation"
                                                    class="icon icon-size-chart"
                                                >
                                                    <path d="M 55 5.585938 L 5.585938 55 L 6.292969 55.707031 L 25 74.417969 L 74.414063 25 Z M 55 8.414063 L 71.585938 25 L 25 71.585938 L 8.414063 55 L 11.0625 52.347656 C 11.210938 52.742188 11.582031 53 12 53 C 12.550781 53 13 52.550781 13 52 C 13 51.582031 12.742188 51.210938 12.347656 51.0625 L 16.0625 47.347656 C 16.210938 47.742188 16.582031 48 17 48 C 17.550781 48 18 47.550781 18 47 C 18 46.582031 17.742188 46.210938 17.347656 46.0625 L 21.0625 42.347656 C 21.210938 42.742188 21.582031 43 22 43 C 22.550781 43 23 42.550781 23 42 C 23 41.582031 22.742188 41.210938 22.347656 41.0625 L 26.0625 37.347656 C 26.210938 37.742188 26.582031 38 27 38 C 27.550781 38 28 37.550781 28 37 C 28 36.582031 27.742188 36.210938 27.347656 36.0625 L 31.0625 32.347656 C 31.210938 32.742188 31.582031 33 32 33 C 32.550781 33 33 32.550781 33 32 C 33 31.582031 32.742188 31.210938 32.347656 31.0625 L 36.0625 27.347656 C 36.210938 27.742188 36.582031 28 37 28 C 37.550781 28 38 27.550781 38 27 C 38 26.582031 37.742188 26.210938 37.347656 26.0625 L 41.0625 22.347656 C 41.210938 22.742188 41.582031 23 42 23 C 42.550781 23 43 22.550781 43 22 C 43 21.582031 42.742188 21.210938 42.347656 21.0625 L 46.0625 17.347656 C 46.210938 17.742188 46.582031 18 47 18 C 47.550781 18 48 17.550781 48 17 C 48 16.582031 47.742188 16.210938 47.347656 16.0625 L 51.0625 12.347656 C 51.210938 12.742188 51.582031 13 52 13 C 52.550781 13 53 12.550781 53 12 C 53 11.582031 52.742188 11.210938 52.347656 11.0625 Z"></path>
                                                </svg>
                                                <span class="text"> Guía de Tallas </span>
                                            </span>
                                        </custom-modal-opener>
                                    </div>

                                    <product-form
                                        class="productView-form product-form element-spacing"
                                        style="
                                            --spacing-top: 16px;
                                            --spacing-bottom: 0px;
                                            --spacing-top-mobile: 8px;
                                            --spacing-bottom-mobile: 0px;
                                            --spacing-top-tablet: 12px;
                                            --spacing-bottom-tablet: 0px;
                                        "
                                    >
                                        <form
                                            method="post"
                                            action="javascript:void(0)"
                                            id="product-form-template--17315347726451__main-{{ $productId }}"
                                            accept-charset="UTF-8"
                                            class="form"
                                            enctype="multipart/form-data"
                                            novalidate="novalidate"
                                            data-type="add-to-cart-form"
                                        >
                                            @csrf

                                            <div
                                                class="productView-message"
                                                id="product-message-template--17315347726451__main-{{ $productId }}"
                                                style="display: none"
                                            ></div>

                                            <div class="productView-group">
                                                <div class="productView-groupTop">
                                                    <quantity-input class="productView-quantity quantity__group clearfix style-1">
                                                        <label
                                                            class="form-label quantity__label hiddenLabels"
                                                            for="quantity-template--17315347726451__main-{{ $productId }}"
                                                        >
                                                            Cantidad:
                                                        </label>

                                                        <input
                                                            class="form-input quantity__input"
                                                            type="number"
                                                            aria-live="polite"
                                                            name="quantity"
                                                            aria-label="quantity"
                                                            min="1"
                                                            value="1"
                                                            inputmode="numeric"
                                                            pattern="[0-9]*"
                                                            id="quantity-template--17315347726451__main-{{ $productId }}"
                                                            data-product="{{ $productId }}"
                                                            data-section="template--17315347726451__main"
                                                            data-price="{{ (int) round($finalPrice * 100) }}"
                                                            data-update-cart="false"
                                                            data-inventory-quantity="{{ $inventoryQty }}"
                                                        />
                                                    </quantity-input>

                                                    <div class="productView-action">
                                                        <input type="hidden" name="id" value="{{ $variantId }}">
                                                        <input type="hidden" name="product-id" value="{{ $productId }}">
                                                        <input type="hidden" name="section-id" value="template--17315347726451__main">

                                                        <div class="product-form__buttons">
                                                            <button
                                                                type="submit"
                                                                name="add"
                                                                data-btn-addtocart=""
                                                                class="product-form__submit button-height button button-style button-shake-affect"
                                                                id="product-add-to-cart"
                                                                {{ !$product->is_available ? 'disabled' : '' }}
                                                            >
                                                                {{ $product->is_available ? 'Agregar al Carrito' : 'Agotado' }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="productView-wishlist clearfix">
                                                        <a
                                                            data-wishlist=""
                                                            href="javascript:void(0)"
                                                            data-wishlist-handle="{{ $productHandle }}"
                                                            data-product-id="{{ $productId }}"
                                                            role="button"
                                                        >
                                                            <span class="visually-hidden">Agregar a lista de deseos</span>
                                                            <svg
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                viewBox="0 0 32 32"
                                                                aria-hidden="true"
                                                                focusable="false"
                                                                role="presentation"
                                                                class="icon icon-wishlist"
                                                            >
                                                                <path
                                                                    d="M 9.5 5 C 5.363281 5 2 8.402344 2 12.5 C 2 13.929688 2.648438 15.167969 3.25 16.0625 C 3.851563 16.957031 4.46875 17.53125 4.46875 17.53125 L 15.28125 28.375 L 16 29.09375 L 16.71875 28.375 L 27.53125 17.53125 C 27.53125 17.53125 30 15.355469 30 12.5 C 30 8.402344 26.636719 5 22.5 5 C 19.066406 5 16.855469 7.066406 16 7.9375 C 15.144531 7.066406 12.933594 5 9.5 5 Z M 9.5 7 C 12.488281 7 15.25 9.90625 15.25 9.90625 L 16 10.75 L 16.75 9.90625 C 16.75 9.90625 19.511719 7 22.5 7 C 25.542969 7 28 9.496094 28 12.5 C 28 14.042969 26.125 16.125 26.125 16.125 L 16 26.25 L 5.875 16.125 C 5.875 16.125 5.390625 15.660156 4.90625 14.9375 C 4.421875 14.214844 4 13.273438 4 12.5 C 4 9.496094 6.457031 7 9.5 7 Z"
                                                                ></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </product-form>

                                    {{-- Todo lo demás que no depende de valores fijos de Shopify lo puedes dejar igual debajo de este punto --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    const productViews = document.querySelectorAll('.productView');

    productViews.forEach((productView) => {
        const nav = productView.querySelector('.productView-nav');
        const thumbWrap = productView.querySelector('.productView-for');

        if (!nav) return;

        const slides = Array.from(nav.querySelectorAll('.productView-image'));
        const thumbs = thumbWrap
            ? Array.from(thumbWrap.querySelectorAll('.productView-thumbnail'))
            : [];

        if (!slides.length) return;

        let currentIndex = 0;
        let intervalId = null;
        const delay = 5000;

        function setActive(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;

            currentIndex = index;

            slides.forEach((slide, i) => {
                const active = i === index;

                slide.classList.toggle('is-active', active);
                slide.classList.toggle('slick-current', active);
                slide.classList.toggle('slick-active', active);
                slide.setAttribute('aria-hidden', active ? 'false' : 'true');
                slide.setAttribute('tabindex', active ? '0' : '-1');
            });

            thumbs.forEach((thumb, i) => {
                const active = i === index;

                thumb.classList.toggle('is-active', active);
                thumb.classList.toggle('slick-current', active);
                thumb.classList.toggle('slick-active', true);
            });
        }

        function nextSlide() {
            setActive(currentIndex + 1);
        }

        function startAuto() {
            stopAuto();
            intervalId = setInterval(nextSlide, delay);
        }

        function stopAuto() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        thumbs.forEach((thumb, index) => {
            const trigger = thumb.querySelector('.productView-thumbnail-link') || thumb;

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                setActive(index);
                startAuto();
            });
        });

        slides.forEach((slide) => {
            slide.addEventListener('mouseenter', function () {
                slide.classList.add('is-hovering');
                stopAuto();
            });

            slide.addEventListener('mouseleave', function () {
                slide.classList.remove('is-hovering');
                startAuto();
            });
        });

        if (thumbWrap) {
            thumbWrap.addEventListener('mouseenter', stopAuto);
            thumbWrap.addEventListener('mouseleave', startAuto);
        }

        setActive(0);
        startAuto();
    });
});
</script>
<style>
.productView-image-wrapper .productView-nav {
    position: relative;
}

.productView-image-wrapper .productView-nav .productView-image {
    position: absolute;
    inset: 0;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    z-index: 1;
}

.productView-image-wrapper .productView-nav .productView-image.is-active {
    position: relative;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    z-index: 2;
}

.productView-thumbnail.is-active .productView-thumbnail-link img {
    opacity: 1 !important;
}

.productView-image .productView-img-container {
    overflow: hidden;
}

.productView-image .productView-img-container .media img:not(.zoomImg) {
    transition: transform .45s ease, opacity .35s ease;
    will-change: transform;
}

.productView-image.is-hovering .productView-img-container .media img:not(.zoomImg) {
    transform: translate(-50%, -50%) scale(1.12);
}

@media (min-width: 1025px) {
    .productView-image.is-hovering.productView-image-zoomed:hover .productView-img-container img:not(.zoomImg) {
        opacity: 1 !important;
    }
}
</style>

<style>
.productView-image-wrapper .productView-nav {
    position: relative;
    overflow: hidden;
    width: 100%;
}

.productView-image-wrapper .productView-nav .slick-list,
.productView-image-wrapper .productView-nav .slick-track {
    position: relative;
    width: 100%;
    height: 100%;
}

.productView-image-wrapper .productView-nav .productView-image {
    width: 100%;
}

@media (max-width: 1024px) {
    .productView-image-wrapper .productView-nav {
        min-height: 0;
    }

    .productView-image-wrapper .productView-nav .productView-image {
        position: absolute !important;
        inset: 0;
        display: block !important;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        z-index: 1;
    }

    .productView-image-wrapper .productView-nav .productView-image.is-active {
        position: relative !important;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        z-index: 2;
    }

    .productView-image-wrapper .productView-nav .productView-image .productView-img-container {
        width: 100%;
        padding-bottom: 100% !important;
        overflow: hidden;
    }

    .productView-thumbnail-wrapper {
        margin-top: 12px;
    }

    .productView-thumbnail-wrapper .productView-for {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 0;
        gap: 6px;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
    }

    .productView-thumbnail-wrapper .productView-for::-webkit-scrollbar {
        display: none;
    }

    .productView-thumbnail-wrapper .productView-thumbnail {
        flex: 0 0 72px;
        width: 72px;
        padding: 0;
    }

    .productView-thumbnail-wrapper .productView-thumbnail .productView-thumbnail-link {
        width: 72px;
        height: 72px;
    }
}
</style>

<style>
/* CONTENEDOR GENERAL */
.productView-images-wrapper {
    display: block !important;
}

.productView-image-wrapper {
    width: 100% !important;
}

.productView-thumbnail-wrapper {
    width: 100% !important;
    margin: 16px 0 0 !important;
    display: block !important;
    padding: 0 !important;
}

/* IMAGEN PRINCIPAL */
.productView-image-wrapper .productView-nav {
    position: relative !important;
    width: 100% !important;
    overflow: hidden !important;
}

.productView-image-wrapper .productView-nav .slick-list,
.productView-image-wrapper .productView-nav .slick-track {
    position: relative !important;
    width: 100% !important;
    height: 100% !important;
}

.productView-image-wrapper .productView-nav .productView-image {
    position: absolute !important;
    inset: 0 !important;
    width: 100% !important;
    opacity: 0 !important;
    visibility: hidden !important;
    pointer-events: none !important;
    z-index: 1 !important;
    margin: 0 !important;
}

.productView-image-wrapper .productView-nav .productView-image.is-active {
    position: relative !important;
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
    z-index: 2 !important;
}

.productView-image .productView-img-container {
    width: 100% !important;
    padding-bottom: 100% !important;
    overflow: hidden !important;
}

/* THUMBNAILS EN FILA HORIZONTAL */
.productView-thumbnail-wrapper .productView-for {
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 8px !important;
    padding: 0 !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    white-space: nowrap !important;
    width: 100% !important;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.productView-thumbnail-wrapper .productView-for::-webkit-scrollbar {
    display: none;
}

.productView-thumbnail-wrapper .productView-for .slick-list,
.productView-thumbnail-wrapper .productView-for .slick-track {
    display: contents !important;
}

.productView-thumbnail-wrapper .productView-thumbnail {
    flex: 0 0 72px !important;
    width: 72px !important;
    min-width: 72px !important;
    max-width: 72px !important;
    padding: 0 !important;
    margin: 0 !important;
    display: block !important;
}

.productView-thumbnail-wrapper .productView-thumbnail .productView-thumbnail-link {
    display: block !important;
    width: 72px !important;
    height: 72px !important;
    overflow: hidden !important;
}

.productView-thumbnail-wrapper .productView-thumbnail .productView-thumbnail-link img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

.productView-thumbnail.is-active .productView-thumbnail-link img,
.productView-thumbnail.slick-current .productView-thumbnail-link img {
    opacity: 1 !important;
}

/* DESKTOP: también horizontal debajo si quieres mantenerlo así */
@media (min-width: 1200px) {
    .productView.layout-1 .productView-images-wrapper,
    .productView.layout-2 .productView-images-wrapper,
    .productView.layout-3 .productView-images-wrapper {
        display: block !important;
    }

    .productView.layout-1 .productView-thumbnail-wrapper,
    .productView.layout-2 .productView-thumbnail-wrapper,
    .productView.layout-3 .productView-thumbnail-wrapper {
        width: 100% !important;
        margin-top: 16px !important;
        padding: 0 !important;
    }

    .productView.layout-1 .productView-thumbnail-wrapper .productView-for,
    .productView.layout-2 .productView-thumbnail-wrapper .productView-for,
    .productView.layout-3 .productView-thumbnail-wrapper .productView-for {
        padding: 0 !important;
    }
}
</style>