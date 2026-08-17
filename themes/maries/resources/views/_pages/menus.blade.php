---
title: Our Menu
permalink: /menus
layout: default
---
@php
    $theme = $this->theme;
@endphp

<!-- ======= Menus Page Header ======= -->
<section class="menus-page-header py-5" style="background: linear-gradient(110deg, rgba(10,10,30,0.9) 0%, rgba(30,27,20,0.7) 60%, rgba(10,10,30,0.5) 100%);">
    <div class="container py-3 text-center text-white">
        <span
            class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3 fw-bold"
            style="font-size:0.7rem; letter-spacing:0.12em; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); backdrop-filter:blur(8px);"
        >
            <span style="width:8px;height:8px;border-radius:50%;background:#f97316;display:inline-block;"></span>
            OUR MENU
        </span>
        <h1 class="fw-bolder mb-2" style="font-family:'Rubik',sans-serif; font-size:clamp(1.9rem,4vw,2.8rem);">
            Fresh, handmade, delivered fast
        </h1>
        <p class="mb-0" style="color:rgba(255,255,255,0.85); max-width: 620px; margin-inline:auto;">
            Browse the full carte — pasta, salads, raclette and drinks. Tap a dish for details or add it straight to your order.
        </p>
    </div>
</section>
<!-- End Menus Page Header -->

<!-- ======= Flash Deals (Resto Casa) ======= -->
<section class="py-4">
    <div class="container">
        <livewire:maries::flash-deals />
    </div>
</section>
<!-- End Flash Deals -->

<!-- ======= Menu Directory ======= -->
<section id="menus" class="menus-page-section pb-5">
    <livewire:maries::menu-directory />
</section>
<!-- End Menu Directory -->
