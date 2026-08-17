<?php

declare(strict_types=1);

namespace Maries\Livewire;

use Igniter\Cart\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Item detail modal, matching the Resto Casa reference ("cart-item-modal").
 *
 * Clicking a flash-deal or product card dispatches `show-item-modal` with the
 * menu id. The modal shows the dish photo, description, option groups (when the
 * menu has any), a quantity stepper, and an "Add to order" button that adds to
 * the cart via the cart-box component's `cart-box:add-item` event.
 */
class CartItemModal extends Component
{
    public ?int $menuId = null;

    public int $quantity = 1;

    public bool $open = false;

    /**
     * Selected option values, keyed by menu_option_id:
     *   [menu_option_id => [menu_option_value_id, ...]]
     */
    public array $selectedOptions = [];

    #[On('show-item-modal')]
    public function openModal(int $menuId): void
    {
        $this->menuId = $menuId;
        $this->quantity = 1;
        $this->selectedOptions = [];
        $this->open = true;
    }

    public function increment(): void
    {
        $this->quantity++;
    }

    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function close(): void
    {
        $this->open = false;
        $this->menuId = null;
        $this->selectedOptions = [];
    }

    public function addToCart(): void
    {
        $menu = $this->menu;
        if (!$menu) {
            $this->close();

            return;
        }

        $this->dispatch('cart-box:add-item', menuId: $menu->menu_id, quantity: max($this->quantity, 1), menuOptions: $this->selectedOptions);

        $this->close();
    }

    protected function menuImage(array $imageMap): ?string
    {
        if (!$this->menu) {
            return null;
        }

        $slug = Str::slug($this->menu->menu_name);

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

    public function getMenuProperty(): ?Menu
    {
        if (!$this->menuId) {
            return null;
        }

        return Menu::query()
            ->with(['categories', 'media', 'menu_options.menu_option_values'])
            ->withCount('menu_options')
            ->where('menu_status', 1)
            ->find($this->menuId);
    }

    public function render(): View
    {
        $imageMap = $this->menuItemImages();

        return view('maries::livewire.cart-item-modal', [
            'menu' => $this->menu,
            'image' => $this->menuImage($imageMap),
            'options' => $this->menu?->menu_options ?? collect(),
        ]);
    }
}
