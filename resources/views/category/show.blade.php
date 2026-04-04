@extends('app')

@section('content')
    <main id="MainContent" class="content-for-layout focus-none" role="main" tabindex="-1">
        <div id="shopify-section-template--17315346808947__header-collection" class="shopify-section">

            <div data-section-id="template--17315346808947__header-collection" data-section-type="header-collection"
                id="HeaderCollectionSection-template--17315346808947__header-collection"
                data-section="template--17315346808947__header-collection" class="halo-page-padding"
                style="
        --padding-top: 0px;
        --padding-bottom: 0px;
        --padding-top-tablet: 0px;
        --padding-bottom-tablet: 0px;
        --padding-top-mobile: 0px;
        --padding-bottom-mobile: 0px;
        padding-left:20px;
      ">

             <nav class="breadcrumb breadcrumb-left breadcrumb-full disable-scrollbar" role="navigation" aria-label="breadcrumbs">
        <a class="link" href="/">Inicio</a><span class="separate" aria-hidden="true"></span>
                <span class="bd-title">
                    <span><a href="/collections/pulseras-tejidas" title="">Pulseras Tejidas</a></span>
                </span></nav>
                <div class="collection-content collection-content-1"></div>
            </div>
        </div>
        </div>
        <div id="shopify-section-template--17315346808947__product-grid" class="shopify-section">

            <div data-section-id="template--17315346808947__product-grid" data-section-type="collection"
                id="CollectionSection-template--17315346808947__product-grid"
                data-section="template--17315346808947__product-grid" class="halo-page-padding"
                style="
        --padding-top: 25px;
        --padding-bottom: 0px;
        --padding-top-tablet: 25px;
        --padding-bottom-tablet: 0px;
        --padding-top-mobile: 25px;
        --padding-bottom-mobile: 0px;
      ">
                <div class="container container-1770">
                    <div class="halo-collection-content halo-grid-content sidebar--layout_horizontal">
                        <div class="page-content" id="CollectionProductGrid">


                            <toolbar-item class="toolbar" data-toolbar=""
                                data-id="template--17315346808947__product-grid">
                                <div class="toolbar-wrapper toolbar-mobile">
                                    <mobile-sidebar-button class="toolbar-item toolbar-sidebar" data-sidebar="">
                                        <span class="toolbar-icon icon-filter">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 368.167 368.167"
                                                aria-hidden="true" focusable="false" role="presentation"
                                                class="icon icon-sidebar">
                                                <path
                                                    d="M248.084,96.684h12c4.4,0,8-3.6,8-8c0-4.4-3.6-8-8-8h-12c-4.4,0-8,3.6-8,8C240.084,93.084,243.684,96.684,248.084,96.684     z">
                                                </path>
                                                <path
                                                    d="M366.484,25.484c-2.8-5.6-8.4-8.8-14.4-8.8h-336c-6,0-11.6,3.6-14.4,8.8c-2.8,5.6-2,12,1.6,16.8l141.2,177.6v115.6     c0,6,3.2,11.2,8.4,14c2.4,1.2,4.8,2,7.6,2c3.2,0,6.4-0.8,9.2-2.8l44.4-30.8c6.4-4.8,10-12,10-19.6v-78.8l140.8-177.2     C368.484,37.484,369.284,31.084,366.484,25.484z M209.684,211.884c-0.8,1.2-1.6,2.8-1.6,4.8v81.2c0,2.8-1.2,5.2-3.2,6.8     l-44.4,30.8v-118.8c0-2.8-1.2-5.2-3.2-6.4l-90.4-113.6h145.2c4.4,0,8-3.6,8-8c0-4.4-3.6-8-8-8h-156c-0.4,0-1.2,0-1.6,0l-38.4-48     h336L209.684,211.884z">
                                                </path>
                                            </svg>
                                        </span>
                                    </mobile-sidebar-button>
                                </div>
                                <div class="toolbar-wrapper">
                                    <div class="toolbar-col toolbar-colLeft">
                                        <div class="toolbar-item toolbar-viewAs clearfix" data-view-as="">
                                            <span class="toolbar-icon icon-mode icon-mode-grid grid-5 active"
                                                data-col="5" role="button" aria-label="Grid 5" tabindex="0"></span>
                                            <span class="toolbar-icon icon-mode icon-mode-grid grid-4" data-col="4"
                                                role="button" aria-label="Grid 4" tabindex="0"></span>
                                            <span class="toolbar-icon icon-mode icon-mode-grid grid-3" data-col="3"
                                                role="button" aria-label="Grid 3" tabindex="0"></span>
                                            <span class="toolbar-icon icon-mode icon-mode-grid grid-2" data-col="2"
                                                role="button" aria-label="Grid 2" tabindex="0"></span>
                                            <span class="toolbar-icon icon-mode icon-mode-list" data-col="1"
                                                role="button" aria-label="List" tabindex="0"></span>
                                        </div>
                                    </div>
                                    <div class="toolbar-col toolbar-colRight">
                                        <div class="toolbar-item toolbar-sort clearfix" data-sorting="">
                                            <label class="toolbar-label" data-ur="">
                                                Ordenar por
                                            </label>
                                            @php
    $sortLabels = [
        'manual' => 'Características',
        'best-selling' => 'Más vendidos',
        'title-ascending' => 'Alfabéticamente, A-Z',
        'title-descending' => 'Alfabéticamente, Z-A',
        'price-ascending' => 'Precio, menor a mayor',
        'price-descending' => 'Precio, mayor a menor',
        'created-ascending' => 'Fecha: antiguo(a) a reciente',
        'created-descending' => 'Fecha: reciente a antiguo(a)',
    ];
@endphp

<div class="toolbar-dropdown filter-sortby">
    <div class="label-tab" data-toggle="dropdown" aria-expanded="false" role="button" tabindex="0">
        <span class="label-text">
            {{ $sortLabels[$sortBy] ?? 'Fecha: reciente a antiguo(a)' }}
        </span>
        <span class="halo-icon-dropdown icon-dropdown" role="none"></span>
    </div>

    <ul class="dropdown-menu list-unstyled">
        @foreach ($sortLabels as $value => $label)
            <li class="{{ $sortBy === $value ? 'is-active' : '' }}" data-sort-by-item>
                <span class="text"
                      data-href="{{ $value }}"
                      data-value="{{ $value }}">
                    {{ $label }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="toolbar-wrapper toolbar-mobile">
                                    <div class="toolbar-item toolbar-viewAs clearfix" data-view-as-mobile="">
                                        <span class="toolbar-icon icon-mode icon-mode-grid grid-5 active" data-col="5"
                                            role="button" aria-label="Grid 5" tabindex="0"></span>
                                        <span class="toolbar-icon icon-mode icon-mode-grid grid-4" data-col="4"
                                            role="button" aria-label="Grid 4" tabindex="0"></span>
                                        <span class="toolbar-icon icon-mode icon-mode-grid grid-3" data-col="3"
                                            role="button" aria-label="Grid 3" tabindex="0"></span>
                                        <span class="toolbar-icon icon-mode icon-mode-grid grid-2" data-col="2"
                                            role="button" aria-label="Grid 2" tabindex="0"></span>
                                        <span class="toolbar-icon icon-mode icon-mode-list" data-col="1" role="button"
                                            aria-label="List" tabindex="0"></span>
                                    </div>
                                </div>
                            </toolbar-item>


                            <div class="collection">
                                <ul class="productListing productGrid column-5 list-5 col-narrow list-unstyled"
                                    id="main-collection-product-grid">
                                    @foreach ($products as $product)
                                        @php
                                            $productUrl = route('producto.show', $product->id);
                                            $image = $product->main_image ?: asset('images/no-image.png');
                                            $price = $product->variants->first()?->price ?? null;
                                            $formattedPrice =
                                                $price !== null
                                                    ? '$' . number_format((float) $price, 0, ',', '.')
                                                    : null;
                                            $summary = $product->description ?: $product->short_description;
                                            $badgeLabels = is_array($product->badge_labels)
                                                ? $product->badge_labels
                                                : [];
                                            $statusBadges = is_array($product->status_badges)
                                                ? $product->status_badges
                                                : [];
                                        @endphp

                                        <li class="product">
                                            <div class="product-item"
                                                data-product-id="{{ $product->shopify_id ?? $product->id }}">
                                                <div class="card style-6">
                                                    <div class="card-product">
                                                        <div class="card-product__wrapper">

                                                            @if (!empty($statusBadges))
                                                                <div
                                                                    class="card__badge badge-left halo-productBadges halo-productBadges--left style-2">
                                                                    @foreach ($statusBadges as $badge)
                                                                        <span class="badge new-badge" aria-hidden="true">
                                                                            {{ $badge }}
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @endif

                                                            <a class="card-media card-media--square media--hover-effect pl-parent"
                                                                href="{{ $productUrl }}" style="padding-bottom: 100%"
                                                                draggable="false" data-image-adapt="100.0">
                                                                <img src="{{ $image }}" width="1280"
                                                                    height="1280" alt="{{ $product->name }}"
                                                                    loading="lazy">
                                                                <div class="pl-collection pl-container"
                                                                    data-label-product-handle="{{ $product->shopify_handle ?? $product->slug }}">
                                                                </div>
                                                            </a>

                                                            <div
                                                                class="card-product__group group-right  group-visible group-bottom card-visible">
                                                                <div class="card-product__group-item card-wishlist">
                                                                    <a class="wishlist-icon" href="#"
                                                                        data-wishlist=""
                                                                        data-wishlist-handle="pulsera-negra-1-2gr-4mm-bolas-lisas-diamantada-oro-amarillo-18k-66691"
                                                                        data-product-id="7900244902003" role="button">
                                                                        <span class="visually-hidden">Agregar a lista de
                                                                            deseos
                                                                        </span><svg xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 32 32" aria-hidden="true"
                                                                            focusable="false" role="presentation"
                                                                            class="icon icon-wishlist">
                                                                            <path
                                                                                d="M 9.5 5 C 5.363281 5 2 8.402344 2 12.5 C 2 13.929688 2.648438 15.167969 3.25 16.0625 C 3.851563 16.957031 4.46875 17.53125 4.46875 17.53125 L 15.28125 28.375 L 16 29.09375 L 16.71875 28.375 L 27.53125 17.53125 C 27.53125 17.53125 30 15.355469 30 12.5 C 30 8.402344 26.636719 5 22.5 5 C 19.066406 5 16.855469 7.066406 16 7.9375 C 15.144531 7.066406 12.933594 5 9.5 5 Z M 9.5 7 C 12.488281 7 15.25 9.90625 15.25 9.90625 L 16 10.75 L 16.75 9.90625 C 16.75 9.90625 19.511719 7 22.5 7 C 25.542969 7 28 9.496094 28 12.5 C 28 14.042969 26.125 16.125 26.125 16.125 L 16 26.25 L 5.875 16.125 C 5.875 16.125 5.390625 15.660156 4.90625 14.9375 C 4.421875 14.214844 4 13.273438 4 12.5 C 4 9.496094 6.457031 7 9.5 7 Z">
                                                                            </path>
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                                <div
                                                                    class="card-product__group-item card-quickview card-quickviewIcon card-mobile__visible card-tablet__visible">
                                                                    <a class="quickview-icon" href="javascript:void(0)"
                                                                        data-product-id="7900244902003"
                                                                        data-open-quick-view-popup=""
                                                                        data-product-handle="pulsera-negra-1-2gr-4mm-bolas-lisas-diamantada-oro-amarillo-18k-66691"
                                                                        role="button">
                                                                        <span class="visually-hidden">Vista
                                                                            rápida</span><svg
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 26 26" aria-hidden="true"
                                                                            focusable="false" role="presentation"
                                                                            class="icon icon-eyes">
                                                                            <path
                                                                                d="M 13 6.15625 C 7.980469 6.15625 3.289063 8.652344 0.46875 12.8125 C -0.0585938 13.59375 0.160156 14.628906 0.9375 15.15625 C 1.230469 15.355469 1.546875 15.46875 1.875 15.46875 C 2.421875 15.46875 2.984375 15.203125 3.3125 14.71875 C 3.417969 14.5625 3.546875 14.429688 3.65625 14.28125 C 4.996094 18.160156 8.664063 20.9375 13 20.9375 C 17.335938 20.9375 21.003906 18.160156 22.34375 14.28125 C 22.453125 14.429688 22.582031 14.5625 22.6875 14.71875 C 23.210938 15.496094 24.28125 15.683594 25.0625 15.15625 C 25.839844 14.628906 26.058594 13.589844 25.53125 12.8125 C 22.714844 8.648438 18.019531 6.15625 13 6.15625 Z M 16.96875 10.25 C 18.636719 10.847656 20.125 11.839844 21.375 13.125 C 20.441406 16.882813 17.042969 19.6875 13 19.6875 C 8.957031 19.6875 5.558594 16.882813 4.625 13.125 C 5.867188 11.847656 7.375 10.882813 9.03125 10.28125 C 8.496094 11.054688 8.1875 11.988281 8.1875 13 C 8.1875 15.65625 10.34375 17.8125 13 17.8125 C 15.65625 17.8125 17.8125 15.65625 17.8125 13 C 17.8125 11.980469 17.511719 11.027344 16.96875 10.25 Z">
                                                                            </path>
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                                <div class="card-product__group-item card-compare card-compareIcon"
                                                                    data-product-compare=""
                                                                    data-product-compare-handle="pulsera-negra-1-2gr-4mm-bolas-lisas-diamantada-oro-amarillo-18k-66691"
                                                                    data-product-compare-id="7900244902003">
                                                                    <div class="compare-icon">
                                                                        <input
                                                                            id="compare-7900244902003-template--17315346808947__product-grid"
                                                                            class="compare-checkbox" type="checkbox"
                                                                            name="compare"
                                                                            value="pulsera-negra-1-2gr-4mm-bolas-lisas-diamantada-oro-amarillo-18k-66691">
                                                                        <label class="compare-label"
                                                                            for="compare-7900244902003-template--17315346808947__product-grid">
                                                                            <span
                                                                                class="visually-hidden">Comparar</span><svg
                                                                                aria-hidden="true" focusable="false"
                                                                                data-prefix="fal" data-icon="random"
                                                                                role="img"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 512 512"
                                                                                class="icon icon-compare">
                                                                                <path
                                                                                    d="M0 128v-8c0-6.6 5.4-12 12-12h105.8c3.3 0 6.5 1.4 8.8 3.9l89.7 97-21.8 23.6L109 140H12c-6.6 0-12-5.4-12-12zm502.6 278.6l-64 64c-20.1 20.1-54.6 5.8-54.6-22.6v-44h-25.7c-3.3 0-6.5-1.4-8.8-3.9l-89.7-97 21.8-23.6L367 372h17v-52c0-28.5 34.5-42.7 54.6-22.6l64 64c12.5 12.5 12.5 32.7 0 45.2zm-19.8-25.4l-64-64c-2.5-2.5-6.8-.7-6.8 2.8v128c0 3.6 4.3 5.4 6.8 2.8l64-64c1.6-1.5 1.6-4.1 0-5.6zm19.8-230.6l-64 64c-20.1 20.1-54.6 5.8-54.6-22.6v-52h-17L126.6 400.1c-2.3 2.5-5.5 3.9-8.8 3.9H12c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h97l240.4-260.1c2.3-2.5 5.5-3.9 8.8-3.9H384V64c0-28.5 34.5-42.7 54.6-22.6l64 64c12.5 12.5 12.5 32.7 0 45.2zm-19.8-25.4l-64-64c-2.5-2.5-6.8-.7-6.8 2.8v128c0 3.6 4.3 5.4 6.8 2.8l64-64c1.6-1.5 1.6-4.1 0-5.6z">
                                                                                </path>
                                                                            </svg>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-information">
                                                        <div
                                                            class="card-information__wrapper text-left pl-badge-container pl-badge-collection">
                                                            <a class="card-title link-underline card-title-ellipsis"
                                                                href="{{ $productUrl }}"
                                                                data-product-title="{{ $product->name }}"
                                                                data-product-url="{{ $productUrl }}">
                                                                <span class="text">{{ $product->name }}</span>
                                                            </a>

                                                            @if (!empty($badgeLabels))
                                                                <div class="deco-collection-below-title deco-badge-stack">
                                                                    <div class="pl-badge-group pl-flex-row"
                                                                        style="gap: 10px; justify-content: left; margin: 0px;">
                                                                        @foreach ($badgeLabels as $label)
                                                                            <div class="pl-animation pl-badge-image pl-text-rectangle"
                                                                                style="position: relative; padding: 5px; display: flex; align-items: center; justify-content: center; background-size: contain; background-repeat: no-repeat; width: 20%; margin: 0px 0px 5px; opacity: 1; animation-name: none-deco; background-color: rgb(249, 249, 249); height: min-content; aspect-ratio: 20 / 5; border: 0px none rgb(0, 0, 0); text-align: left; rotate: 0deg;">
                                                                                <span class="pl-text"
                                                                                    style="color: rgb(73, 73, 73); font-size: 11px; font-weight: normal; font-style: normal; text-decoration: none; font-family: Poppins; letter-spacing: 2px; text-align: left; word-break: break-word;">
                                                                                    {{ $label }}
                                                                                </span>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="card-price"
                                                                id="card-price-{{ $product->shopify_id ?? $product->id }}">
                                                                <div class="price">
                                                                    <dl>
                                                                        <div class="price__regular">
                                                                            <dt>
                                                                                <span
                                                                                    class="visually-hidden visually-hidden--inline">Precio
                                                                                    habitual</span>
                                                                            </dt>
                                                                            <dd class="price__last">
                                                                                <span
                                                                                    class="price-item price-item--regular">
                                                                                    {{ $formattedPrice }}
                                                                                </span>
                                                                            </dd>
                                                                        </div>

                                                                        <div class="price__sale">
                                                                            <dt>
                                                                                <span
                                                                                    class="visually-hidden visually-hidden--inline">Precio
                                                                                    de venta</span>
                                                                            </dt>
                                                                            <dd class="price__last">
                                                                                <span class="price-item price-item--sale">
                                                                                    {{ $formattedPrice }}
                                                                                </span>
                                                                            </dd>
                                                                        </div>
                                                                    </dl>
                                                                </div>
                                                            </div>

                                                            @if ($summary)
                                                                <div class="card-summmary card-list__hidden">
                                                                    {{ $summary }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="card-action__group card-list__hidden">
                                                            <div class="card-action">
                                                                <form action="javascript:void(0)" method="post"
                                                                    class="variants">
                                                                    <a class="button button-style button-ATC"
                                                                        href="{{ $productUrl }}" role="button">
                                                                        Agregar al Carrito
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div class="card-action card-grid__hidden">
                                                            <form action="javascript:void(0)" method="post"
                                                                class="variants">
                                                                <a class="button button-ATC" href="{{ $productUrl }}"
                                                                    role="button">
                                                                    Agregar al Carrito
                                                                </a>
                                                            </form>
                                                            <div class="variants-popup custom-scrollbar">
                                                                <template></template>
                                                            </div>
                                                        </div>

                                                        <div class="card-popup card-list__hidden custom-scrollbar">
                                                            <a href="javascript:void(0)" title="Cerrar" role="button">
                                                                <span class="visually-hidden">Cerrar</span>
                                                            </a>
                                                            <div class="card-popup-content"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>


                                <div class="pagination-wrapper text-center">
                                    <nav class="pagination pagination-infinite style-1" role="navigation"
                                        aria-label="Paginación">
                                        <div class="pagination-page-item pagination-page-total">
                                            <span></span>
                                            <span data-total-start="">1</span>
                                            <span> - </span><span data-total-end="">50</span><span></span>
                                        </div>
                                        <div class="pagination-page-item pagination-page-infinite">
                                            <a class="link link-underline is-loading"
                                                href="https://napoleonejoyas.co/collections/cadenas?page=2"
                                                data-href="/collections/cadenas?page=2" data-infinite-scrolling=""
                                                data-load-more="Cargar Más" data-loading-more="Cargando más">Cargando
                                                más</a>
                                        </div>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="halo-loader"></div>
            </div>
        </div>
        <div id="shopify-section-template--17315346808947__footer-collection" class="shopify-section"></div>
    </main>
@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sortDropdown = document.querySelector('.toolbar-dropdown.filter-sortby');
    if (!sortDropdown) return;

    const labelTab = sortDropdown.querySelector('.label-tab');
    const labelText = sortDropdown.querySelector('.label-text');
    const menu = sortDropdown.querySelector('.dropdown-menu');
    const items = sortDropdown.querySelectorAll('[data-sort-by-item] .text');

    if (!labelTab || !labelText || !menu || !items.length) return;

    function openDropdown() {
        labelTab.setAttribute('aria-expanded', 'true');
    }

    function closeDropdown() {
        labelTab.setAttribute('aria-expanded', 'false');
    }

    function toggleDropdown() {
        const isOpen = labelTab.getAttribute('aria-expanded') === 'true';
        labelTab.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
    }

    function setActiveItem(sortValue) {
        items.forEach((item) => {
            const li = item.closest('[data-sort-by-item]');
            if (!li) return;

            if (item.dataset.value === sortValue) {
                li.classList.add('is-active');
                labelText.textContent = item.textContent.trim();
            } else {
                li.classList.remove('is-active');
            }
        });
    }

    function getCurrentSortValue() {
        const url = new URL(window.location.href);
        return url.searchParams.get('sort_by') || 'created-descending';
    }

    // Estado inicial según URL
    setActiveItem(getCurrentSortValue());

    // Abrir/cerrar al click
    labelTab.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        toggleDropdown();
    });

    // Abrir/cerrar con teclado
    labelTab.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggleDropdown();
        }

        if (e.key === 'Escape') {
            closeDropdown();
        }
    });

    // Selección de opción
    items.forEach((item) => {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const sortValue = this.dataset.value;
            const sortLabel = this.textContent.trim();

            setActiveItem(sortValue);
            labelText.textContent = sortLabel;
            closeDropdown();

            const url = new URL(window.location.href);
            url.searchParams.set('sort_by', sortValue);
            url.searchParams.delete('page');

            window.location.href = url.toString();
        });
    });

    // Cerrar al click fuera
    document.addEventListener('click', function (e) {
        if (!sortDropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    // Cerrar con Escape global
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDropdown();
        }
    });
});
</script>