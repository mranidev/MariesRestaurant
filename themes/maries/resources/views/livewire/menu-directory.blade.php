<div class="menu-directory-livewire">
    <!-- Sticky category strip (anchor pills) -->
    <div class="sticky-top bg-white border-bottom border-1 menu-categories-strip" style="top: 70px !important; z-index: 1020;">
        <div class="container">
            <ul id="navbar-categories" class="nav nav-pills nav-inline flex-nowrap py-3 w-100" style="overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none;">
                <li class="nav-item">
                    <a class="nav-link rounded py-1 text-nowrap active" href="#category-all-heading">
                        <i class="bi bi-grid-3x3-gap me-1 text-primary"></i>All Categories
                    </a>
                </li>
                @foreach ($categories as $category)
                    <li class="nav-item">
                        <a
                            class="nav-link rounded py-1 text-nowrap"
                            href="#category-{{ $category->permalink_slug }}-heading"
                        >{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="container py-4">
        <!-- All categories group -->
        <div class="menu-group" id="category-all-group">
            <div class="category-header" id="category-all-heading" role="tab">
                <div class="d-flex align-items-center justify-content-between pt-3 pb-2 mb-0 border-bottom">
                    <h4 class="menu-group-toggle flex-grow-1 mb-0 py-2 fw-bold text-dark" style="font-family: 'Rubik', sans-serif;">
                        All Categories
                    </h4>
                    <span class="text-muted small">{{ $menus->count() }} items</span>
                </div>
            </div>
            <div class="category-items show">
                <div class="menu-category">
                    <div class="menu-items row g-3 list-view">
                        @foreach ($menus as $menu)
                            @include('maries::livewire.menu-directory-row', ['menu' => $menu, 'image' => $menuImages[$menu->menu_id] ?? null])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Per-category groups -->
        @foreach ($categories as $category)
            <div class="menu-group" id="category-{{ $category->permalink_slug }}-group">
                <div class="category-header" id="category-{{ $category->permalink_slug }}-heading" role="tab">
                    <div class="d-flex align-items-center justify-content-between pt-3 pb-2 mb-0 border-bottom">
                        <h4
                            class="menu-group-toggle flex-grow-1 mb-0 py-2 fw-bold text-dark"
                            style="font-family: 'Rubik', sans-serif; cursor: pointer;"
                            data-bs-toggle="collapse"
                            data-bs-target="#category-{{ $category->permalink_slug }}-collapse"
                            aria-expanded="false"
                            aria-controls="category-{{ $category->permalink_slug }}-heading"
                        >{{ $category->name }}</h4>
                        <span class="text-muted small">{{ $category->menus_count }} items</span>
                    </div>
                </div>
                <div
                    id="category-{{ $category->permalink_slug }}-collapse"
                    class="category-items collapse show"
                    role="tabpanel"
                    aria-labelledby="{{ $category->permalink_slug }}"
                >
                    <div class="menu-category">
                        <div class="menu-items row g-3 list-view">
                            @forelse ($groups[$category->permalink_slug] ?? [] as $menu)
                                @include('maries::livewire.menu-directory-row', ['menu' => $menu, 'image' => $menuImages[$menu->menu_id] ?? null])
                            @empty
                                <p class="text-muted small p-3">No dishes in this category yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
