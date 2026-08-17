---
title: Register
permalink: /register
layout: default
---
<section class="auth-page-header py-5" style="background: linear-gradient(110deg, rgba(10,10,30,0.9) 0%, rgba(30,27,20,0.7) 60%, rgba(10,10,30,0.5) 100%);">
    <div class="container py-3 text-center text-white">
        <span
            class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3 fw-bold"
            style="font-size:0.7rem; letter-spacing:0.12em; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); backdrop-filter:blur(8px);"
        >
            <span style="width:8px;height:8px;border-radius:50%;background:#f97316;display:inline-block;"></span>
            YOUR ACCOUNT
        </span>
        <h1 class="fw-bolder mb-2" style="font-family:'Rubik',sans-serif; font-size:clamp(1.9rem,4vw,2.8rem);">
            Join Maries
        </h1>
        <p class="mb-0" style="color:rgba(255,255,255,0.85);">
            Create an account and your details will pre-fill at checkout.
        </p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <livewire:maries::register />
                </div>
            </div>
        </div>
    </div>
</div>
