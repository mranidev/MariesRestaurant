<!-- ======= Banners Section (Resto Casa style) ======= -->
<section id="banners" class="av-block-section av-block-type-banner py-4">
    <div class="container">
        <div class="av-banners-section py-3 scroll-animate">
            <div class="row g-4">
                <div class="col-md-6 col-12">
                    <div
                        class="av-banner-card position-relative overflow-hidden rounded-4 shadow-sm d-flex align-items-center"
                        style="min-height: 250px; background: url('{{ maries_assets('img/ref/banner-1.png') }}') center/cover no-repeat;"
                    >
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                        <div class="position-relative p-4 p-md-5 text-white w-100" style="z-index: 2;">
                            <h3
                                class="display-6 fw-bold mb-2"
                                style="font-family: 'Rubik', sans-serif; text-shadow: 1px 1px 4px rgba(0,0,0,0.5); font-size: clamp(1.6rem, 3vw, 2.4rem);"
                            >
                                Order delicious food online
                            </h3>
                            <p class="lead mb-4 opacity-90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); font-size: 1rem;">
                                Fresh ingredients, made with love.
                            </p>
                            <a href="{{ url('/menus') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm scrollto" style="background-color: #f97316; border-color: #f97316;">
                                Order Now
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div
                        class="av-banner-card position-relative overflow-hidden rounded-4 shadow-sm d-flex align-items-center"
                        style="min-height: 250px; background: url('{{ maries_assets('img/ref/banner-2.png') }}') center/cover no-repeat;"
                    >
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-25"></div>

                        <div class="position-relative p-4 p-md-5 text-white w-100" style="z-index: 2;">
                            <h3
                                class="display-6 fw-bold mb-2"
                                style="font-family: 'Rubik', sans-serif; text-shadow: 1px 1px 4px rgba(0,0,0,0.5); font-size: clamp(1.6rem, 3vw, 2.4rem);"
                            >
                                Reserve your table
                            </h3>
                            <p class="lead mb-4 opacity-90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5); font-size: 1rem;">
                                Plan your evening with us.
                            </p>
                            <a href="#" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#reservationModal">
                                Book a Table
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banners Section -->
