<?php

declare(strict_types=1);

/**
 * Idempotent seed of the Maries demo menu.
 *
 * Creates the Drinks / Salads / Specialty categories and the menu items from
 * the original Maries static site (public/img/menu holds their photos),
 * then deactivates any remaining installer demo menus.
 *
 * Run from the app root:
 *
 *   php artisan tinker --execute="require base_path('themes/pasta/seed.php');"
 *
 * Prices are stored in the store's configured currency (GBP by default in this
 * install); the original site quoted Tunisian dinars.
 */

use Igniter\Cart\Models\Category;
use Igniter\Cart\Models\Menu;

$categories = [
    'drinks' => ['Drinks', 1],
    'salads' => ['Salads', 2],
    'specialty' => ['Specialty', 3],
];

$categoryIds = [];
foreach ($categories as $slug => [$name, $priority]) {
    $category = Category::updateOrCreate(
        ['name' => $name],
        ['description' => '', 'priority' => $priority, 'status' => 1],
    );

    if (!$category->permalink_slug) {
        $category->permalink_slug = $slug;
        $category->save();
    }

    $categoryIds[$slug] = $category->category_id;
}

$items = [
    ['Schweppes Tonic', 'Crisp tonic water served over ice with a slice of lemon.', 2.50, 'drinks', 1],
    ['Fresh Orange Juice', 'Freshly squeezed oranges, no added sugar.', 3.50, 'drinks', 2],
    ['Mojito', 'Classic mojito with fresh mint, lime and soda.', 4.00, 'drinks', 3],
    ['Caesar Salad', 'Heart of lettuce, cherry tomatoes, grilled chicken, parmesan.', 8.50, 'salads', 1],
    ['Nut Salad', 'Lettuce, arugula, cherry tomatoes, grilled chicken, goat cheese, nut sauce, balsamic.', 9.00, 'salads', 2],
    ['Burrata Salad', 'Burrata, arugula, cherry tomatoes, balsamic vinegar, virgin olive oil, dried tomatoes.', 8.50, 'salads', 3],
    ['Cleopatra', 'Fillet of sea bream on a bed of prawns, shrimps, mushrooms, basil leaves, zucchinis.', 14.00, 'specialty', 1],
    ['Beffy', 'Tomato sauce, ground meat, garlic, chili flakes, parsley.', 11.00, 'specialty', 2],
    ['Juicy', 'Minced meat, fresh tomato sauce, fresh parmesan, gratiné with grilled cheese.', 12.00, 'specialty', 3],
];

$pastaIds = [];
foreach ($items as [$name, $description, $price, $categorySlug, $priority]) {
    $menu = Menu::updateOrCreate(
        ['menu_name' => $name],
        [
            'menu_description' => $description,
            'menu_price' => $price,
            'menu_status' => 1,
            'menu_priority' => $priority,
            'minimum_qty' => 1,
        ],
    );

    $menu->categories()->syncWithoutDetaching([$categoryIds[$categorySlug]]);

    $pastaIds[] = $menu->menu_id;
}

// Deactivate installer demo menus that are not part of the pasta menu.
Menu::where('menu_status', 1)
    ->whereNotIn('menu_id', $pastaIds)
    ->update(['menu_status' => 0]);

echo 'Seeded '.count($pastaIds).' pasta menu items across '.count($categoryIds)." categories.\n";
