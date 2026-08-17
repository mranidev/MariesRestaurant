<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials">
    <div class="container position-relative max-width-container">
        <div
            class="testimonials-slider swiper"
            data-aos="fade-up"
            data-aos-delay="100"
        >
            <div class="swiper-wrapper">
                @foreach ([
                    ['bachir', 'Bechir B', 'Ceo &amp; Founder', 'The best pasta in Tunis. Very good dishes, greedy portions, appetizing presentations, to be repeated and I strongly advise to all lovers of good pasta.'],
                    ['sywar', 'Sywar Jmayel', 'Designer', 'Freshness, variety, quality and impeccable service I will certainly come back.'],
                    ['rahma', 'Rahma Rh', 'Store Owner', 'For pasta lovers 🍝 Maries Is the best address a fast service, nice setting and the dishes are too too good .👌🏻 I highly recommend it.'],
                    ['oussama', 'Oussama Hamed', 'Freelancer', 'A real treat at Maries ❤️ This dish is called &quot;Hasta la Vistas Baby&quot; 😅 and I chose tagliatelle as the pasta ❤️ Price: 25DT Rating: 10/10.'],
                    ['mehdi', 'Mehdi Jelassi', 'Entrepreneur', 'For years the quality is excellent this place does not change but the menu is constantly enriched.'],
                ] as [$file, $name, $role, $quote])
                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img
                                src="{{ maries_assets('img/testimonials/'.$file.'.jpg') }}"
                                class="testimonial-img"
                                alt=""
                            />
                            <h3>{{ $name }}</h3>
                            <h4>{{ $role }}</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i
                                ><i class="bi bi-star-fill"></i
                                ><i class="bi bi-star-fill"></i
                                ><i class="bi bi-star-fill"></i
                                ><i class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                                {!! $quote !!}
                                <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                            </p>
                        </div>
                    </div>
                    <!-- End testimonial item -->
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
<!-- End Testimonials Section -->
