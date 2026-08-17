---
title: Reservation
permalink: /reservation
layout: default
---
@php
    $theme = $this->theme;
    $location = \Igniter\Local\Facades\Location::currentOrDefault();
    try {
        $isOpen = (bool)$location?->getSchedule()?->isOpen();
    } catch (\Throwable $e) {
        $isOpen = true;
    }
    $logo = maries_assets('img/logo/logo.jpeg');
@endphp

<!-- ======= Reservation Page (Resto Casa style) ======= -->
<div class="container pt-4 pb-5">
    <div class="mb-3">
        <a class="hero-back d-inline-flex align-items-center gap-2 text-decoration-none" href="{{ url('/') }}">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="reservation-hero mb-4">
        <div class="hero-body">
            <img class="hero-logo" src="{{ $logo }}" alt="{{ $theme->site_name }}" />
            <div class="hero-meta">
                <h1 class="fw-bolder mb-1">{{ $theme->site_name }}</h1>
                <div class="hero-address">
                    <i class="bi bi-geo-alt me-1 opacity-75"></i>
                    {{ $theme->address }}
                </div>
                <div class="hero-chips">
                    <span class="status-pill @if ($isOpen) is-open @else is-closed @endif">
                        <span class="dot"></span>
                        <span class="@if ($isOpen) text-success @else text-danger @endif">
                            {{ $isOpen ? 'We are open' : 'We are closed' }}
                        </span>
                    </span>
                    <a class="hero-chip" href="{{ url('/') }}#contact">
                        <i class="bi bi-info-circle"></i>
                        More info
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card reservation-booking-card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <div class="alert alert-light border d-flex align-items-center justify-content-between rounded-3 mb-4 py-3">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ url('/login') }}" class="fw-bold text-primary text-decoration-none">Login Here</a>
            </div>

            <livewire:maries::booking />
        </div>
    </div>
</div>
<!-- End Reservation Page -->
