<div class="cart-item-modal" wire:loading.class="is-loading">
    @if ($open && $menu)
        <div class="modal fade show d-block" id="cartItemModal" tabindex="-1" aria-hidden="false" aria-labelledby="cartItemModalLabel" role="dialog" style="z-index: 1070;">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                    <button
                        type="button"
                        class="btn-close cart-item-modal-close"
                        data-dismiss="cart-item-modal"
                        wire:click="close"
                        aria-label="Close"
                    ></button>

                    <div class="modal-body p-3 p-md-4">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-5 text-center">
                                <div
                                    class="rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 220px; height: 220px; max-width: 100%;"
                                >
                                    @if ($image)
                                        <img
                                            src="{{ $image }}"
                                            alt="{{ $menu->menu_name }}"
                                            class="img-fluid"
                                            style="width: 100%; height: 100%; object-fit: cover;"
                                        />
                                    @else
                                        <i class="bi bi-egg-fried text-primary" style="font-size: 4rem;"></i>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-7">
                                <span
                                    class="text-primary fw-bold text-uppercase d-block mb-1"
                                    style="color: #f97316 !important; font-size: 0.7rem; letter-spacing: 0.1rem;"
                                >{{ $menu->categories->pluck('name')->implode(', ') ?: 'Dish of the day' }}</span>
                                <h3 class="h4 fw-bold mb-2 text-dark" id="cartItemModalLabel" style="font-family: 'Rubik', sans-serif;">
                                    {{ $menu->menu_name }}
                                </h3>

                                @if ($menu->menu_description)
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                        {{ $menu->menu_description }}
                                    </p>
                                @endif

                                <div class="mb-3">
                                    <span class="fw-bold" style="font-size: 1.6rem; color: #f97316; font-family: 'Rubik', sans-serif;">
                                        @if ($menu->menu_price > 0)
                                            {{ currency_format($menu->menu_price) }}
                                        @else
                                            @lang('igniter::main.text_free')
                                        @endif
                                    </span>
                                </div>

                                @if ($options->isNotEmpty())
                                    <div class="mb-3">
                                        @foreach ($options as $option)
                                            <div class="mb-2">
                                                <div class="fw-semibold small mb-1 text-dark">
                                                    {{ $option->option_name }}
                                                    @if ($option->is_required)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($option->menu_option_values as $value)
                                                        <label class="cart-item-option">
                                                            <input
                                                                type="checkbox"
                                                                class="btn-check"
                                                                value="{{ $value->menu_option_value_id }}"
                                                                wire:model="selectedOptions.{{ $option->menu_option_id }}"
                                                            >
                                                            <span class="btn btn-sm btn-outline-secondary rounded-pill">
                                                                {{ $value->name }}
                                                                @if ((float) ($value->price ?? 0) > 0)
                                                                    +{{ currency_format($value->price) }}
                                                                @endif
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="d-flex align-items-center gap-3">
                                    <!-- Quantity stepper -->
                                    <div
                                        class="d-inline-flex align-items-center rounded-pill border"
                                        style="border-color: #e5e7eb !important;"
                                    >
                                        <button
                                            type="button"
                                            class="btn btn-sm border-0 px-3 fw-bold"
                                            style="color: #f97316;"
                                            wire:click="decrement"
                                            aria-label="Decrease quantity"
                                        >−</button>
                                        <span class="px-2 fw-bold" wire:model="quantity" style="min-width: 28px; text-align: center;">{{ $quantity }}</span>
                                        <button
                                            type="button"
                                            class="btn btn-sm border-0 px-3 fw-bold"
                                            style="color: #f97316;"
                                            wire:click="increment"
                                            aria-label="Increase quantity"
                                        >+</button>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn rounded-pill px-4 py-2 fw-bold text-white shadow-sm"
                                        style="background-color: #f97316; border-color: #f97316;"
                                        wire:click="addToCart"
                                        wire:loading.attr="disabled"
                                    >
                                        <i class="bi bi-basket2 me-2"></i>Add to order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" style="z-index: 1065;"></div>
    @endif
</div>
