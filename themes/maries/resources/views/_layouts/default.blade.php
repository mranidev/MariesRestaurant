---
description: Maries Default Layout
---
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! get_metas() !!}
    <title>{{ $this->page->title }}</title>

    <!-- Fonts: Rubik (display) + Nunito (body), matching the Resto Casa direction -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:wght@500;600;700;800&family=Nunito:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    />
    <!-- Vendor CSS Files -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
        rel="stylesheet"
    />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi"
        crossorigin="anonymous"
    />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"
        rel="stylesheet"
    />
    <link
        href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css"
    />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css"
    />

    @themeStyles
    @livewireStyles
</head>
<body>
@php
    $theme = $this->theme;
    $assetsUrl = asset('vendor/maries');
@endphp

@include('maries::includes.header')

<main id="main">
    @themePage
</main>

@include('maries::includes.footer')

<a
    href="#"
    class="back-to-top d-flex align-items-center justify-content-center"
    ><i class="bi bi-arrow-up-short"></i
></a>

<!-- Cart item detail modal (Resto Casa style) -->
<livewire:maries::cart-item-modal />

<!-- Order cart drawer (Bootstrap offcanvas, Resto Casa style) -->
<div
    class="offcanvas offcanvas-end shadow cart-drawer"
    tabindex="-1"
    id="cartDrawer"
    aria-labelledby="cartDrawerLabel"
    style="width: 400px; border-inline-start: 0;"
>
    <div class="offcanvas-header bg-white border-bottom py-4">
        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="cartDrawerLabel">
            <i class="bi bi-basket2 text-primary fs-4"></i> {{ lang('maries::default.nav_your_order') }}
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body bg-light p-0">
        <livewire:maries::cart-box />
    </div>
</div>

<!-- Reservation modal (Resto Casa style) -->
<div
    class="modal fade"
    id="reservationModal"
    tabindex="-1"
    aria-labelledby="reservationModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="reservationModalLabel">Reserve A Table</h5>
                <button
                    type="button"
                    class="btn-close shadow-none"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body pt-2 pb-4">
                <div class="alert alert-light border d-flex align-items-center justify-content-between rounded-3 mb-4 py-3">
                    <span class="text-muted">Already have an account?</span>
                    <a href="{{ url('/login') }}" class="fw-bold text-primary text-decoration-none">Login Here</a>
                </div>
                <livewire:maries::booking />
            </div>
        </div>
    </div>
</div>

<!-- Mobile bottom nav (Resto Casa signature) -->
<nav class="mobile-bottom-nav" aria-label="Mobile navigation">
    <a class="mbn-link" href="#hero">{{ lang('maries::default.nav_home') }}<i class="bi bi-house-door"></i></a>
    <a class="mbn-link" href="{{ url('/menus') }}">{{ lang('maries::default.nav_menu') }}<i class="bi bi-grid"></i></a>
    <button
        type="button"
        class="mbn-link mbn-cart"
        data-cart-toggle
        onclick="toggleMariesCart()"
        aria-label="{{ lang('maries::default.nav_your_order') }}"
        aria-expanded="false"
    >
        {{ lang('maries::default.nav_your_order') }}<i class="bi bi-bag"></i>
        <span class="mbn-cart-count" hidden></span>
    </button>
</nav>

<!-- Vendor JS Files -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
    crossorigin="anonymous"
></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
<script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>

@themeScripts
@livewireScripts
</body>
</html>
