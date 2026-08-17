<!-- ======= Header / Navbar (Resto Casa style) ======= -->
<header id="header" class="header fixed-top header-transparent">
    <nav id="mainNavbar" class="navbar navbar-light navbar-top navbar-expand-lg transition-all bg-transparent shadow-none py-sm-2 py-md-3">
        <div class="container-fluid px-4 px-xl-5 main-navbar-row">
            <a class="navbar-brand me-4" href="{{ url('/') }}">
                <img
                    class="img-logo"
                    alt="{{ $theme->site_name }}"
                    src="{{ maries_assets('img/logo/logo.jpeg') }}"
                    style="max-height: 40px;"
                />
            </a>

            <!-- Centered premium pills: MENU / RESERVATION (desktop) -->
            <div class="d-none d-lg-flex align-items-center gap-4 navbar-center-pills">
                <a href="{{ url('/menus') }}" class="nav-link-premium">
                    <i class="bi bi-utensils me-2" style="color: #f97316;"></i>{{ lang('maries::default.menu_menu') }}
                </a>
                <a
                    href="#"
                    class="nav-link-premium"
                    data-bs-toggle="modal"
                    data-bs-target="#reservationModal"
                    aria-haspopup="dialog"
                >
                    <i class="bi bi-calendar me-2" style="color: #f97316;"></i>{{ lang('maries::default.menu_reservation') }}
                </a>
            </div>

            <div class="d-flex align-items-center ms-auto gap-2 gap-lg-3 navbar-right-cluster">
                <!-- Location pill (desktop) -->
                <div class="d-none d-md-flex align-items-center">
                    <a href="{{ url('/') }}#contact" class="location-pill-glass scrollto">
                        <i class="bi bi-geo-alt me-2" style="color: #f97316;"></i>
                        <span class="location-pill-text text-truncate">
                            {{ $theme->address ?: lang('maries::default.text_set_location') }}
                        </span>
                    </a>
                </div>

                <!-- Phone (glass) -->
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $theme->phone ?? '') }}"
                   class="phone-glass-btn d-none d-xl-flex align-items-center gap-2 rounded-pill px-3 py-2 text-decoration-none">
                    <i class="bi bi-telephone"></i>
                    <span>{{ $theme->phone }}</span>
                </a>

                <!-- Order cart (header icon + live count badge + running total) -->
                <button
                    type="button"
                    class="header-cart-btn btn-cart-premium d-flex align-items-center gap-2 rounded-pill border px-3 py-2"
                    data-cart-toggle
                    onclick="toggleMariesCart()"
                    aria-label="{{ lang('maries::default.nav_your_order') }}"
                    aria-expanded="false"
                >
                    <span class="position-relative">
                        <i class="bi bi-basket2 fs-5"></i>
                        <span class="cart-badge header-cart-count" hidden></span>
                    </span>
                    <span class="header-cart-total fw-bold" style="font-size: 0.85rem;">{{ currency_format(0) }}</span>
                </button>

                <!-- Language switch (EN / AR, Resto Casa style) -->
                <div class="d-flex align-items-center">
                    <div class="btn-locale-premium d-flex align-items-center gap-2 rounded-pill border px-3 py-2">
                        <i class="bi bi-globe2" style="font-size: 0.9rem; color: #f97316;"></i>
                        <select
                            class="form-select form-select-sm border-0 shadow-none bg-transparent fw-bold px-1 py-0 w-auto locale-switch"
                            aria-label="Choose language"
                            style="cursor: pointer; font-size: 0.75rem; color: inherit;"
                            onchange="switchMariesLocale(this.value)"
                        >
                            <option value="en" @if(app()->getLocale() === 'en') selected @endif>EN</option>
                            <option value="ar" @if(app()->getLocale() === 'ar') selected @endif>AR</option>
                        </select>
                    </div>
                </div>

                <!-- User -->
                <a href="{{ url('/login') }}" class="btn-user-premium d-flex align-items-center justify-content-center rounded-circle border text-decoration-none"
                   aria-label="Account">
                    <i class="bi bi-person fs-5"></i>
                </a>

                <!-- Mobile toggler -->
                <button
                    type="button"
                    class="mobile-nav-toggle border-0 bg-transparent p-2"
                    aria-label="Toggle navigation"
                    aria-expanded="false"
                ><i class="bi bi-list fs-3"></i></button>
            </div>
        </div>

        <!-- Mobile slide-down menu -->
        <div id="navbar" class="navbar-mobile-panel">
            <ul class="navbar-links">
                <li><a class="nav-link scrollto" href="{{ url('/') }}">{{ lang('maries::default.nav_home') }}</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/') }}#about">{{ lang('maries::default.nav_about') }}</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/') }}#menu">{{ lang('maries::default.nav_menu') }}</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/') }}#events">{{ lang('maries::default.nav_events') }}</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/') }}#gallery">{{ lang('maries::default.nav_gallery') }}</a></li>
                <li><a class="nav-link scrollto" href="{{ url('/') }}#contact">{{ lang('maries::default.nav_contact') }}</a></li>
            </ul>
        </div>
    </nav>
</header>
<!-- End Header -->
