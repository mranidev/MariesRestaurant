<!-- ======= About Section ======= -->
<section id="about" class="about">
    <div class="container-fluid max-width-container">
        <div class="row">
            <div class="col-lg-5 align-items-stretch video-box">
                <video
                    width="270"
                    height="480"
                    preload="none"
                    controls
                    muted
                    poster="{{ maries_assets('img/logo/logo.jpeg') }}"
                    src="{{ maries_assets('video/about.mp4') }}"
                ></video>
            </div>

            <div
                class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch"
            >
                <div class="content">
                    <h3>Discover <strong>OUR HISTORY</strong></h3>
                    <p>
                        {{ $theme->site_name }} is an Italian restaurant in Tunis, les Berges du
                        Lac I. Embark on a gastronomic journey around the flavors of
                        overseas. Our chef offers a variety of pastas, risottos but
                        also raclette.
                    </p>
                    <p class="fst-italic">WHAT you will taste at our place :</p>
                    <ul>
                        <li>
                            <i class="bx bx-check-double"></i> Pasta with Parmigiano
                            (White Sauce &amp; Red Sauce).
                        </li>
                        <li><i class="bx bx-check-double"></i> Risottos.</li>
                        <li><i class="bx bx-check-double"></i> Salads.</li>
                        <li><i class="bx bx-check-double"></i> Raclette.</li>
                    </ul>
                    <p>
                        Choose your pasta (Spaghetti, Penne, Farfalle, Tagliatelle)
                        and it is served after dipping in a wheel of parmigiano.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End About Section -->
