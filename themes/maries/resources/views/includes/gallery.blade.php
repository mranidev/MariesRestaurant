<!-- ======= Gallery Section ======= -->
<section id="gallery" class="gallery">
    <div class="container-fluid max-width-container">
        <div class="section-title">
            <h2>Some photos from <span>Our Restaurant</span></h2>
        </div>
        <div class="row g-2">
            @for ($i = 1; $i <= 8; $i++)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="gallery-item">
                        <a href="{{ maries_assets('img/gallery/gallery-'.$i.'.jpg') }}">
                            <img
                                src="{{ maries_assets('img/gallery/gallery-'.$i.'.jpg') }}"
                                alt=""
                                class="img-fluid"
                            />
                        </a>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
<!-- End Gallery Section -->
