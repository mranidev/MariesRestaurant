<div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-2">
    <div
        class="menu-list-item bg-white overflow-hidden mb-3 cursor-pointer rounded-3 border"
        style="border-color: rgba(0,0,0,0.06) !important; transition: box-shadow 0.25s ease, border-color 0.25s ease;"
        wire:key="menu-row-{{ $menu->menu_id }}"
        wire:click="$dispatch('show-item-modal', {menuId: {{ $menu->menu_id }}})"
        role="button"
        tabindex="0"
        data-control="menu-item"
        onmouseover="this.style.borderColor='#f1c40f'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.07)'"
        onmouseout="this.style.borderColor='rgba(0,0,0,0.06)'; this.style.boxShadow='none'"
    >
        <div class="d-flex align-items-center p-3 gap-3">
            <!-- Image Area -->
            <div class="list-item-image-wrapper position-relative flex-shrink-0">
                <div
                    class="list-item-image d-flex align-items-center justify-content-center bg-light rounded-3 shadow-sm position-relative z-2"
                    style="width: 100px; height: 100px; overflow: hidden;"
                >
                    @if ($image)
                        <img
                            src="{{ $image }}"
                            alt="{{ $menu->menu_name }}"
                            class="img-fluid"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;"
                        />
                    @else
                        <i class="bi bi-egg-fried text-primary" style="font-size: 2.4rem;"></i>
                    @endif
                </div>
            </div>

            <!-- Content Area -->
            <div class="list-item-content flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;">{{ $menu->menu_name }}</h5>
                    <div class="list-item-price fw-bold ms-2" style="font-size: 1.2rem; color: #f97316 !important; white-space: nowrap;">
                        @if ($menu->menu_price > 0)
                            {{ currency_format($menu->menu_price) }}
                        @else
                            @lang('igniter::main.text_free')
                        @endif
                    </div>
                </div>

                @if ($menu->menu_description)
                    <p
                        class="text-muted small mb-2"
                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.4; max-width: 90%;"
                    >{{ $menu->menu_description }}</p>
                @endif

                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <button
                            class="btn btn-primary rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm"
                            style="background-color: #f97316; border: none; font-size: 0.85rem; font-weight: 700;"
                            @if ($menu->menu_options_count)
                                wire:click.stop="$dispatch('show-item-modal', {menuId: {{ $menu->menu_id }}})"
                                title="@lang('igniter.local::default.text_menu_option_required')"
                            @else
                                wire:click.stop="$dispatch('cart-box:add-item', {menuId: {{ $menu->menu_id }}, quantity: {{ max($menu->minimum_qty, 1) }}})"
                            @endif
                            wire:loading.attr="disabled"
                            aria-label="Add {{ $menu->menu_name }} to order"
                        >
                            <i class="bi bi-plus-lg small"></i>
                            <span>ADD</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
