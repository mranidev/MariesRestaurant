<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\Cart\Models\Category;
use Igniter\Cart\Models\Menu;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class MenuList extends Component
{
    /**
     * Permalink slug of the active category filter, or null for "All".
     */
    public ?string $activeCategory = null;

    public function filterByCategory(?string $slug = null): void
    {
        $this->activeCategory = $slug && $slug !== 'all' ? $slug : null;
    }

    /**
     * Maps a menu item slug to its image in the theme's published assets.
     * The Maries demo menu ships with photographs in public/img/menu/.
     */
    protected function menuItemImages(): array
    {
        $dir = __DIR__.'/../../public/img/menu';

        if (!is_dir($dir)) {
            return [];
        }

        $map = [];
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'gif'];
        foreach (glob($dir.'/*') ?: [] as $file) {
            if (!is_file($file)) {
                continue;
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, $extensions, true)) {
                continue;
            }

            $name = pathinfo($file, PATHINFO_FILENAME);
            $map[$name] = asset('vendor/maries/img/menu/'.$name.'.'.$ext);
        }

        return $map;
    }

    /**
     * Resolves the best available photo for a menu item by fuzzy-matching its
     * name against the filenames shipped in public/img/menu/.
     */
    protected function menuImage(string $menuName, array $imageMap): ?string
    {
        $slug = \Illuminate\Support\Str::slug($menuName);

        if (isset($imageMap[$slug])) {
            return $imageMap[$slug];
        }

        // Looser match: the slug contains the image key (e.g. "caesar-salad"
        // vs the "caesar" file) or vice versa.
        foreach ($imageMap as $key => $url) {
            if ($key && (str_contains($slug, $key) || str_contains($key, $slug))) {
                return $url;
            }
        }

        return null;
    }

    public function render(): View
    {
        $query = Menu::query()
            ->with(['categories', 'media'])
            ->withCount('menu_options')
            ->where('menu_status', 1);

        if ($this->activeCategory) {
            $query->whereHas('categories', fn ($q) => $q->where('permalink_slug', $this->activeCategory));
        }

        $menus = $query
            ->orderBy('menu_priority')
            ->orderBy('menu_name')
            ->get();

        $categories = Category::query()
            ->whereIsEnabled()
            ->whereHas('menus', fn ($q) => $q->where('menu_status', 1))
            ->withCount(['menus' => fn ($q) => $q->where('menu_status', 1)])
            ->orderBy('priority')
            ->get();

        $imageMap = $this->menuItemImages();

        $menuImages = [];
        foreach ($menus as $menu) {
            $menuImages[$menu->menu_id] = $this->menuImage($menu->menu_name, $imageMap);
        }

        // One representative image per category: the photo of its first dish.
        $categoryImages = [];
        foreach ($menus as $menu) {
            foreach ($menu->categories as $category) {
                $categoryImages[$category->permalink_slug] ??= $menuImages[$menu->menu_id];
            }
        }

        return view('maries::livewire.menu-list', [
            'menus' => $menus,
            'categories' => $categories,
            'imageMap' => $imageMap,
            'menuImages' => $menuImages,
            'categoryImages' => $categoryImages,
        ]);
    }
}
