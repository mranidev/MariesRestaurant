<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\Cart\Models\Category;
use Igniter\Cart\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * Dedicated menus page ("/menus"), matching the Resto Casa reference.
 *
 * Menus are grouped by category (sticky anchor strip + collapsible groups),
 * rendered as horizontal list-view rows: 100px photo, name + price, short
 * description and an ADD button. Clicking a row opens the item detail modal
 * (CartItemModal); the ADD button quick-adds when the dish has no options.
 */
class MenuDirectory extends Component
{
    public function render(): View
    {
        $menus = Menu::query()
            ->with(['categories', 'media'])
            ->withCount('menu_options')
            ->where('menu_status', 1)
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

        // Group menus under their categories; uncategorised dishes go to "All".
        $groups = [];
        foreach ($menus as $menu) {
            $menuCats = $menu->categories->where('is_enabled', 1);
            if ($menuCats->isEmpty()) {
                $groups['all'][] = $menu;
                continue;
            }

            foreach ($menuCats as $category) {
                $groups[$category->permalink_slug][] = $menu;
            }
        }

        $categoryImages = [];
        foreach ($menus as $menu) {
            foreach ($menu->categories as $category) {
                $categoryImages[$category->permalink_slug] ??= $this->menuImage($menu->menu_name, $imageMap);
            }
        }

        return view('maries::livewire.menu-directory', [
            'menus' => $menus,
            'categories' => $categories,
            'groups' => $groups,
            'imageMap' => $imageMap,
            'menuImages' => $menus->mapWithKeys(fn (Menu $menu) => [
                $menu->menu_id => $this->menuImage($menu->menu_name, $imageMap),
            ]),
            'categoryImages' => $categoryImages,
        ]);
    }

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

    protected function menuImage(string $menuName, array $imageMap): ?string
    {
        $slug = Str::slug($menuName);

        if (isset($imageMap[$slug])) {
            return $imageMap[$slug];
        }

        foreach ($imageMap as $key => $url) {
            if ($key && (str_contains($slug, $key) || str_contains($key, $slug))) {
                return $url;
            }
        }

        return null;
    }
}
