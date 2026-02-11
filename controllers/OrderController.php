<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;
use App\Services\Mailer;
use App\Services\MongoStats;

final class OrderController extends Controller
{
    public function create(): void
    {
        require_auth();
        $menuId = (int)($_GET['menu_id'] ?? 0);
        $menu = $menuId ? Menu::find($menuId) : null;
        $menus = $menu ? [] : Menu::all();
        $this->view('pages/order_new', [
            'menu' => $menu,
            'menus' => $menus,
        ]);
    }

    public function store(): void
    {
        require_auth();

        $menuId = (int)($_POST['menu_id'] ?? 0);
        $menu = Menu::find($menuId);
        if (!$menu) {
            flash('error', 'Menu introuvable.');
            redirect('/menus');
        }

        $people = (int)($_POST['people_count'] ?? 0);
        if ($people < (int)$menu['min_people']) {
            flash('error', 'Nombre de personnes insuffisant.');
            redirect('/orders/new?menu_id=' . $menuId);
        }

        $deliveryCity = trim($_POST['delivery_city'] ?? '');
        $distanceKm = (float)($_POST['distance_km'] ?? 0);

        $deliveryFee = 0.0;
        if (mb_strtolower($deliveryCity) !== 'bordeaux') {
            $deliveryFee = 5 + (0.59 * $distanceKm);
        }

        $menuPrice = (float)$menu['base_price'];
        if ($people >= ((int)$menu['min_people'] + 5)) {
            $menuPrice *= 0.9;
        }

        $total = $menuPrice + $deliveryFee;

        $orderId = Order::create([
            'user_id' => (int)auth()['id'],
            'menu_id' => $menuId,
            'status' => 'recue',
            'people_count' => $people,
            'delivery_address' => trim($_POST['delivery_address'] ?? ''),
            'delivery_city' => $deliveryCity,
            'delivery_postal' => trim($_POST['delivery_postal'] ?? ''),
            'delivery_date' => $_POST['delivery_date'] ?? null,
            'delivery_time' => $_POST['delivery_time'] ?? null,
            'delivery_place' => trim($_POST['delivery_place'] ?? ''),
            'distance_km' => $distanceKm,
            'delivery_fee' => $deliveryFee,
            'menu_price' => $menuPrice,
            'total_price' => $total,
        ]);

        Mailer::send(auth()['email'], 'Confirmation de commande', "Votre commande #{$orderId} a été enregistrée.");
        MongoStats::recordOrder($menuId, $menu['title'], $total);

        flash('success', 'Commande enregistrée.');
        redirect('/orders/' . $orderId);
    }

    public function show(string $id): void
    {
        require_auth();
        $order = Order::find((int)$id);
        if (!$order) {
            http_response_code(404);
            $this->view('pages/404');
            return;
        }
        $history = Order::history((int)$id);
        $this->view('pages/order_detail', [
            'order' => $order,
            'history' => $history,
        ]);
    }

    public function cancel(string $id): void
    {
        require_auth();
        $order = Order::find((int)$id);
        if (!$order || $order['status'] !== 'recue') {
            flash('error', 'Annulation impossible.');
            redirect('/orders/' . $id);
        }

        Order::cancel((int)$id, 'Annulation utilisateur', 'compte');
        flash('success', 'Commande annulée.');
        redirect('/orders/' . $id);
    }

    public function review(string $id): void
    {
        require_auth();
        $order = Order::find((int)$id);
        if (!$order || $order['status'] !== 'terminee') {
            flash('error', 'Avis non autorisé.');
            redirect('/orders/' . $id);
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        Review::create((int)$id, $rating, $comment);
        flash('success', 'Avis envoyé pour validation.');
        redirect('/orders/' . $id);
    }
}
