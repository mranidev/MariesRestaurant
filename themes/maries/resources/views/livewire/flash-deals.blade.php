<div class="flash-deals-livewire">
    <div class="av-category-section scroll-animate">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <span
                    class="text-primary fw-bold text-uppercase mb-1 d-block"
                    style="color: #f97316 !important; font-size: 0.75rem; letter-spacing: 0.1rem;"
                >Limited time offers</span>
                <h2 class="h2 fw-bold mb-0 text-dark" style="font-family: 'Rubik', sans-serif; font-size: 2rem;">
                    Flash Deals
                </h2>
            </div>
        </div>

        <div class="av-flash-deals-wrapper">
            <div class="av-flash-deals-slider-container position-relative">
                <div class="av-flash-deals-slider" style="overflow: hidden;">
                    <div
                        class="av-flash-deals-inner d-flex gap-3"
                        style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; padding-bottom: 4px;"
                    >
                        @foreach ($deals as $deal)
                            @php($menu = $deal['menu'])
                            <div class="av-flash-deal-card-item flex-shrink-0" style="width: 300px; scroll-snap-align: start;">
                                <div
                                    class="special-product-card bg-white overflow-hidden position-relative h-100 cursor-pointer"
                                    wire:click="$dispatch('show-item-modal', {menuId: {{ $menu->menu_id }}})"
                                    role="button"
                                    tabindex="0"
                                >
                                    <!-- Compact Sale Badge -->
                                    <div class="compact-special-badge position-absolute z-3 mt-2 ms-2">
                                        <span
                                            class="badge rounded-pill fw-bold shadow-sm blinking-text"
                                            style="font-size: 0.7rem; padding: 4px 10px; background-color: #ff3e67; color: #fff; box-shadow: 0 4px 12px rgba(255, 62, 103, 0.4); display: inline-flex; align-items: center;"
                                        >
                                            <i class="bi bi-lightning-charge-fill me-1"></i>-{{ $deal['discount'] }}%
                                        </span>
                                    </div>

                                    <div class="d-flex p-3 gap-3">
                                        <!-- Compact Image -->
                                        <div
                                            class="special-card-image flex-shrink-0 d-flex align-items-center justify-content-center bg-light rounded-3"
                                            style="width: 110px; height: 110px; overflow: hidden;"
                                        >
                                            @if ($deal['image'])
                                                <img
                                                    src="{{ $deal['image'] }}"
                                                    alt="{{ $menu->menu_name }}"
                                                    class="img-fluid"
                                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                                                />
                                            @else
                                                <i class="bi bi-egg-fried text-primary" style="font-size: 2.4rem;"></i>
                                            @endif
                                        </div>

                                        <!-- Compact Content -->
                                        <div class="special-card-body flex-grow-1 d-flex flex-column justify-content-between">
                                            <div>
                                                <h6
                                                    class="fw-bold mb-1 text-truncate"
                                                    style="font-size: 1rem; color: #1e293b;"
                                                    title="{{ $menu->menu_name }}"
                                                >{{ $menu->menu_name }}</h6>
                                                @if ($menu->menu_description)
                                                    <p
                                                        class="text-muted small mb-2"
                                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.2; font-size: 0.8rem;"
                                                    >{{ $menu->menu_description }}</p>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                                <div class="special-price-box">
                                                    @if ($deal['original'] > 0)
                                                        <div class="text-muted text-decoration-line-through" style="font-size: 0.75rem; margin-bottom: -2px;">
                                                            {{ currency_format($deal['original']) }}
                                                        </div>
                                                        <div class="fw-bold text-primary" style="font-size: 1.1rem; color: #f97316 !important;">
                                                            {{ currency_format($deal['sale']) }}
                                                        </div>
                                                    @else
                                                        <div class="fw-bold text-primary" style="font-size: 1.1rem; color: #f97316 !important;">
                                                            @lang('igniter::main.text_free')
                                                        </div>
                                                    @endif
                                                </div>

                                                <button
                                                    type="button"
                                                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center p-0 shadow-sm"
                                                    style="width: 34px; height: 34px; transition: all 0.2s; background-color: #f97316; border-color: #f97316; color: #fff;"
                                                    aria-label="Add {{ $menu->menu_name }} to order"
                                                    @if ($menu->menu_options_count)
                                                        disabled
                                                        title="@lang('igniter.local::default.text_menu_option_required')"
                                                    @else
                                                        wire:click.stop="$dispatch('cart-box:add-item', {menuId: {{ $menu->menu_id }}, quantity: {{ max($menu->minimum_qty, 1) }}})"
                                                    @endif
                                                    wire:loading.attr="disabled"
                                                ><i class="bi bi-plus-lg small fw-bold"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
