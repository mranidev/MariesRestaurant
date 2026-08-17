---
title: Maries Restaurant
permalink: /
layout: default
---
@php
    // Expose the theme settings to the partials included below.
    $theme = $this->theme;
@endphp

@include('maries::includes.hero')

@include('maries::includes.menu')

<livewire:maries::flash-deals />

@include('maries::includes.banners')

@include('maries::includes.gallery')

@include('maries::includes.events')

@include('maries::includes.book')

@include('maries::includes.testimonials')

@include('maries::includes.contact')
