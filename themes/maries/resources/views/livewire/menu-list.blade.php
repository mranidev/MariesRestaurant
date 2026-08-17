<div class="menu-list-livewire" wire:loading.class="is-loading">
    <div class="av-category-section">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <span
                    class="text-primary fw-bold text-uppercase mb-1 d-block"
                    style="color: #f97316 !important; font-size: 0.75rem; letter-spacing: 0.1rem;"
                >What's on your mind?</span>
                <h2 class="h2 fw-bold mb-0 text-dark" style="font-family: 'Rubik', sans-serif; font-size: 2rem;">
                    Popular Dishes
                </h2>
            </div>
        </div>

        <!-- Category slider (horizontal scroll, filters the carte live) -->
        <div class="av-cat-slider-container position-relative mb-4">
            <div
                class="av-cat-slider-wrapper d-flex gap-3 pb-1"
                style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;"
            >
                <div class="av-cat-slide flex-shrink-0" style="width: 140px; scroll-snap-align: start;">
                    <button
                        type="button"
                        class="av-cat-card text-decoration-none d-block h-100 w-100 position-relative border-0 text-start {{ $activeCategory === null ? 'av-cat-active' : '' }}"
                        wire:click="filterByCategory('all')"
                        style="background: #f8f9fa; border-radius: 20px;"
                    >
                        <div class="av-cat-bg-shape"></div>
                        <div class="card border-0 rounded-4 h-100 transition-all overflow-hidden text-center p-3" style="background: transparent;">
                            <div
                                class="av-cat-img-wrapper mx-auto position-relative mb-3"
                                style="width: 90px; height: 90px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.05); z-index: 2;"
                            >
                                <img
                                    src="{{ $categoryImages['all'] ?? maries_assets('img/menu/juicy.jpg') }}"
                                    class="img-fluid w-100 h-100 object-fit-cover"
                                    alt="All dishes"
                                />
                            </div>
                            <h6
                                class="fw-bold mb-1 text-dark av-cat-title text-truncate px-1"
                                style="font-size: 0.95rem; letter-spacing: -0.01rem; z-index: 2; position: relative;"
                            >All</h6>
                            <div class="text-muted" style="font-size: 0.75rem; z-index: 2; position: relative;">
                                {{ $menus->count() }} items
                            </div>
                        </div>
                    </button>
                </div>

                @foreach ($categories as $category)
                    <div class="av-cat-slide flex-shrink-0" style="width: 140px; scroll-snap-align: start;">
                        <button
                            type="button"
                            class="av-cat-card text-decoration-none d-block h-100 w-100 position-relative border-0 text-start {{ $activeCategory === $category->permalink_slug ? 'av-cat-active' : '' }}"
                            wire:click="filterByCategory('{{ $category->permalink_slug }}')"
                            style="background: #f8f9fa; border-radius: 20px;"
                        >
                            <div class="av-cat-bg-shape"></div>
                            <div class="card border-0 rounded-4 h-100 transition-all overflow-hidden text-center p-3" style="background: transparent;">
                                <div
                                    class="av-cat-img-wrapper mx-auto position-relative mb-3"
                                    style="width: 90px; height: 90px; overflow: hidden; box-shadow: 0 8px 20px rgba(0,0,0,0.05); z-index: 2;"
                                >
                                    @if (!empty($categoryImages[$category->permalink_slug]))
                                        <img
                                            src="{{ $categoryImages[$category->permalink_slug] }}"
                                            class="img-fluid w-100 h-100 object-fit-cover"
                                            alt="{{ $category->name }}"
                                        />
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-primary" style="font-size: 2rem;">
                                            <i class="bi bi-egg-fried"></i>
                                        </div>
                                    @endif
                                </div>
                                <h6
                                    class="fw-bold mb-1 text-dark av-cat-title text-truncate px-1"
                                    style="font-size: 0.95rem; letter-spacing: -0.01rem; z-index: 2; position: relative;"
                                >{{ $category->name }}</h6>
                                <div class="text-muted" style="font-size: 0.75rem; z-index: 2; position: relative;">
                                    {{ $category->menus_count }} items
                                </div>
                            </div>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($menus->isEmpty())
            <p class="menu-empty">@lang('igniter.local::default.text_empty_menus')</p>
        @else
            <!-- Product cards grid -->
            <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4">
                @foreach ($menus as $menu)
                    <div class="col">
                        <article
                            class="product-item bg-white overflow-hidden h-100 cursor-pointer"
                            wire:key="menu-{{ $menu->menu_id }}"
                            wire:click="$dispatch('show-item-modal', {menuId: {{ $menu->menu_id }}})"
                            role="button"
                            tabindex="0"
                        >
                            <div class="product-transition text-center">
                                <div class="product-image d-flex justify-content-center align-items-center" style="height: 170px;">
                                    @if (!empty($menuImages[$menu->menu_id]))
                                        <img
                                            src="{{ $menuImages[$menu->menu_id] }}"
                                            alt="{{ $menu->menu_name }}"
                                            class="img-fluid position-relative z-2"
                                            style="max-height: 150px; object-fit: contain; filter: drop-shadow(0 15px 15px rgba(0,0,0,0.15));"
                                        />
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-primary" style="font-size: 3rem;">
                                            <i class="bi bi-egg-fried"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="product-caption px-3 pt-2 pb-3 d-flex flex-column" style="min-height: 150px;">
                                <div class="menu-content text-center flex-grow-1">
                                    <h5 class="menu-name fw-bold mb-1" style="font-size: 1.05rem;">{{ $menu->menu_name }}</h5>
                                    @if ($menu->menu_description)
                                        <p
                                            class="menu-desc text-muted mx-auto"
                                            style="font-size: 0.85rem; max-width: 90%; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; line-height: 1.4;"
                                        >{{ $menu->menu_description }}</p>
                                    @endif
                                </div>

                                <div class="price-container d-flex justify-content-center align-items-center mt-3 gap-3">
                                    <span class="price d-flex align-items-center">
                                        <ins class="fw-bold text-decoration-none menu-price" style="font-size: 1.25rem; color: #f97316;">
                                            @if ($menu->menu_price > 0)
                                                {{ currency_format($menu->menu_price) }}
                                            @else
                                                @lang('igniter::main.text_free')
                                            @endif
                                        </ins>
                                    </span>

                                    <button
                                        type="button"
                                        class="btn btn-cart d-flex align-items-center justify-content-center p-0 rounded-circle menu-add"
                                        style="width: 40px; height: 40px; background-color: #f97316; border: 2px solid #f97316; transition: transform 0.2s, background-color 0.2s; color: #fff;"
                                        aria-label="Add {{ $menu->menu_name }} to order"
                                        @if ($menu->menu_options_count)
                                            disabled
                                            title="@lang('igniter.local::default.text_menu_option_required')"
                                        @else
                                            wire:click.stop="$dispatch('cart-box:add-item', {menuId: {{ $menu->menu_id }}, quantity: {{ max($menu->minimum_qty, 1) }}})"
                                        @endif
                                        wire:loading.attr="disabled"
                                    ><i class="bi bi-plus-lg fs-5 fw-bold"></i></button>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
