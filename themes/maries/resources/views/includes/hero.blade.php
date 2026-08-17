<!-- ======= Hero Section (Resto Casa style) ======= -->
<section id="hero" class="av-hero-section">
    <div
        id="heroCarousel"
        data-bs-interval="6000"
        class="carousel slide carousel-fade position-absolute top-0 start-0 w-100 h-100"
        data-bs-ride="carousel"
        style="z-index:0;"
    >
        <div class="carousel-inner h-100" role="listbox">
            <!-- Slide 1 (Ken Burns) -->
            <div
                class="carousel-item h-100 active av-ken-burns-bg"
                style="background-image: url({{ maries_assets('img/slide/slide-1.jpg') }})"
            ></div>
            <!-- Slide 2 -->
            <div
                class="carousel-item h-100 av-ken-burns-bg"
                style="background-image: url({{ maries_assets('img/slide/slide-2.jpg') }})"
            ></div>
            <!-- Slide 3 -->
            <div
                class="carousel-item h-100 av-ken-burns-bg"
                style="background-image: url({{ maries_assets('img/slide/slide-3.jpg') }})"
            ></div>
        </div>
    </div>

    <!-- Dark gradient overlay -->
    <div
        class="position-absolute top-0 start-0 w-100 h-100"
        style="z-index:1; background: linear-gradient(110deg, rgba(10,10,30,0.85) 0%, rgba(10,10,30,0.65) 50%, rgba(10,10,30,0.25) 100%);"
    ></div>

    <div class="container position-relative py-3" style="z-index:2;">
        <div class="row align-items-center av-hero-row" style="padding:50px 0 30px;">
            <div class="col-lg-6 text-white py-4">
                <div
                    class="av-hero-badge d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4 fw-bold"
                    style="font-size:0.72rem; letter-spacing:0.12em; background:rgba(255,255,255,0.18); border:1px solid rgba(255,255,255,0.35); backdrop-filter:blur(8px);"
                >
                    <span
                        style="width:8px;height:8px;border-radius:50%;background:#f97316;display:inline-block;"
                    ></span>
                    BEST CHOICE
                </div>

                <h1
                    class="fw-bolder mb-4 lh-sm av-hero-title"
                    style="font-family:'Rubik',sans-serif; font-size:clamp(2.2rem,4.5vw,3.4rem); text-shadow:2px 3px 12px rgba(0,0,0,0.4); line-height:1.15;"
                >
                    The Fastest Food Delivery in your city
                </h1>

                <p
                    class="mb-5 opacity-85 av-hero-subtitle"
                    style="font-size:1.1rem; max-width:480px; text-shadow:1px 1px 6px rgba(0,0,0,0.35); line-height:1.7;"
                >
                    Order from the best restaurants and get it delivered in minutes.
                </p>

                <div class="av-hero-search-wrap mb-4" style="max-width:560px;">
                    <div class="av-hero-search-inline position-relative">
                        <div class="hero-search">
                            <i class="bi bi-geo-alt"></i>
                            <span>Enter your delivery address or postcode</span>
                            <a href="{{ url('/menus') }}" class="btn-find scrollto">Find</a>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mt-3">
                    <div class="d-flex">
                        <img
                            src="{{ maries_assets('img/testimonials/bachir.jpg') }}"
                            class="img-customer border border-2 border-white"
                            alt="Happy customer"
                        />
                        <img
                            src="{{ maries_assets('img/testimonials/mehdi.jpg') }}"
                            class="img-customer border border-2 border-white ms-n3"
                            alt="Happy customer"
                        />
                        <img
                            src="{{ maries_assets('img/testimonials/oussama.jpg') }}"
                            class="img-customer border border-2 border-white ms-n3"
                            alt="Happy customer"
                        />
                    </div>
                    <span class="text-white fw-bold small" style="text-shadow:1px 1px 4px rgba(0,0,0,0.4);">
                        500k+ Happy Customers
                    </span>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                <div class="av-food-showcase position-relative">
                    <div class="av-food-main-card">
                        <div class="av-food-image-container">
                            <img
                                src="{{ maries_assets('img/menu/juicy.jpg') }}"
                                alt="Gourmet dish of the day"
                            />
                            <div class="av-food-overlay-gradient"></div>
                        </div>
                        <div class="av-food-info">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span
                                    class="badge bg-success rounded-pill px-2 py-1"
                                    style="font-size: 0.65rem;"
                                >FRESH &amp; HOT</span>
                                <div class="text-warning" style="font-size: 0.8rem;">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;">
                                Gourmet Dish of Day
                            </h5>
                        </div>
                    </div>

                    <div class="av-floating-badge badge-rating">
                        <div class="badge-icon"><i class="bi bi-heart-fill text-danger"></i></div>
                        <div class="badge-content">
                            <span class="badge-value">2.4k</span>
                            <span class="badge-label">Favorites</span>
                        </div>
                    </div>

                    <div class="av-floating-badge badge-time">
                        <div class="badge-icon"><i class="bi bi-clock text-primary"></i></div>
                        <div class="badge-content">
                            <span class="badge-value">25-35</span>
                            <span class="badge-label">Min Delivery</span>
                        </div>
                    </div>

                    <div class="av-floating-badge badge-offers">
                        <div class="badge-icon"><i class="bi bi-percent text-success"></i></div>
                        <div class="badge-content">
                            <span class="badge-value">50% OFF</span>
                            <span class="badge-label">First Order</span>
                        </div>
                    </div>

                    <div class="av-showcase-bg-circles">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>
</section>
<!-- End Hero -->
