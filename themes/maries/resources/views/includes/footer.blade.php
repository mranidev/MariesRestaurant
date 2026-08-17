<!-- ======= Footer (Resto Casa style) ======= -->
<footer id="footer" class="footer mt-auto">
    <div class="footer-main py-5">
        <div class="container">
            <div class="row g-4">

                <div class="col-lg-3 col-md-6">
                    <div class="footer-brand mb-3">
                        <h3 class="footer-site-name mb-2">{{ $theme->site_name }}</h3>
                    </div>
                    <p class="footer-tagline mb-3">
                        {{ $theme->site_name }} is specialized in Pasta with Parmigiano, Risotto,
                        Salads &amp; Raclette
                    </p>
                    @if ($theme->address)
                        <p class="footer-info mb-1">
                            <i class="bi bi-geo-alt me-2"></i>{{ $theme->address }}
                        </p>
                    @endif
                    @if ($theme->phone)
                        <p class="footer-info mb-1">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $theme->phone) }}" class="footer-link">{{ $theme->phone }}</a>
                        </p>
                    @endif
                    @if ($theme->email)
                        <p class="footer-info mb-0">
                            <i class="bi bi-envelope me-2"></i>{{ $theme->email }}
                        </p>
                    @endif
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Restaurant</h6>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-1"><a href="{{ url('/menus') }}" class="footer-link text-decoration-none scrollto">MENU</a></li>
                        <li class="mb-1"><a href="#" class="footer-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#reservationModal">RESERVATION</a></li>
                        <li class="mb-1"><a href="#contact" class="footer-link text-decoration-none scrollto">Our Location</a></li>
                    </ul>
                    <h6 class="footer-title">Information</h6>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-1"><a href="#contact" class="footer-link text-decoration-none scrollto">Contact Us</a></li>
                        <li class="mb-1"><a href="#about" class="footer-link text-decoration-none scrollto">About Us</a></li>
                        <li class="mb-1"><a href="#gallery" class="footer-link text-decoration-none scrollto">Gallery</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title mb-3">Follow us on</h6>
                    <ul class="social-icons list-inline d-flex gap-2">
                        @if ($theme->facebook_url)
                            <li class="list-inline-item">
                                <a href="{{ $theme->facebook_url }}" class="footer-social" target="_blank" rel="noopener" aria-label="Facebook"><i class="bx bxl-facebook"></i></a>
                            </li>
                        @endif
                        @if ($theme->instagram_url)
                            <li class="list-inline-item">
                                <a href="{{ $theme->instagram_url }}" class="footer-social" target="_blank" rel="noopener" aria-label="Instagram"><i class="bx bxl-instagram"></i></a>
                            </li>
                        @endif
                        <li class="list-inline-item">
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $theme->phone ?? '') }}" class="footer-social" aria-label="Call us"><i class="bx bxl-whatsapp"></i></a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div id="newsletter-box">
                        <h6 class="footer-title mb-3">Subscribe to our newsletter</h6>
                        <form class="subscribe-form" onsubmit="event.preventDefault(); this.querySelector('input').value=''; alert('Thanks for subscribing!');">
                            <div class="input-group subscribe-group">
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control rounded"
                                    placeholder="Your email address"
                                    aria-label="Email address"
                                    required
                                />
                                <button type="submit" class="btn btn-light rounded ms-2" aria-label="Subscribe">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    &copy; Copyright <strong>{{ $theme->site_name }}</strong>. All Rights Reserved
                    <span class="footer-credits">
                        · Designed by
                        <a href="https://github.com/mranidev" target="_blank" rel="noopener">AMINE EL ALAOUI MRANI</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->
