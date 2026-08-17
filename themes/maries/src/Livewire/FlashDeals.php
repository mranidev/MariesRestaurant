<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\Cart\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * "Flash Deals" strip, matching the Resto Casa reference homepage.
 *
 * No specials are configured in the demo store, so the deals are a curated
 * selection of active menus shown with a promotional discount. The discount
 * is only cosmetic (marketing); adding to cart always uses the real price.
 */
class FlashDeals extends Component
{
    public function render(): View
    {
        $query = Menu::query()
            ->withCount('menu_options')
            ->where('menu_status', 1);

        $menus = $query
            ->orderBy('menu_priority')
            ->orderBy('menu_name')
            ->get()
            ->take(6);

        // Per-dish photos from the theme's published assets (same resolver as MenuList).
        $imageMap = $this->menuItemImages();

        $deals = $menus->map(function (Menu $menu) use ($imageMap) {
            $price = (float) $menu->menu_price;
            $discountPct = [20, 15, 25, 10, 30, 15][$menu->menu_id % 6];
            $salePrice = $price > 0 ? round($price * (1 - $discountPct / 100), 2) : 0.0;

            return [
                'menu' => $menu,
                'image' => $this->menuImage($menu->menu_name, $imageMap),
                'original' => $price,
                'sale' => $salePrice,
                'discount' => $discountPct,
            ];
        });

        return view('maries::livewire.flash-deals', [
            'deals' => $deals,
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
