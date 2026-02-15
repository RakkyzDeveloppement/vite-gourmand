<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;

final class MenuController extends Controller
{
    public function index(): void
    {
        $filters = [
            'theme' => $_GET['theme'] ?? null,
            'regime' => $_GET['regime'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'price_from' => $_GET['price_from'] ?? null,
            'price_to' => $_GET['price_to'] ?? null,
            'min_people' => $_GET['min_people'] ?? null,
        ];

        $menus = Menu::all($filters);
        if (!empty($_GET['ajax'])) {
            partial('pages/menus_list', ['menus' => $menus]);
            return;
        }

        $this->view('pages/menus', [
            'menus' => $menus,
            'filters' => $filters,
        ]);
    }

    public function show(string $id): void
    {
        $menu = Menu::find((int)$id);
        if (!$menu) {
            http_response_code(404);
            $this->view('pages/404');
            return;
        }
        $images = Menu::images((int)$id);
        $dishes = Menu::dishes((int)$id);
        $this->view('pages/menu_detail', [
            'menu' => $menu,
            'images' => $images,
            'dishes' => $dishes,
        ]);
    }
}
