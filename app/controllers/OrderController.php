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

        $deliveryCity = trim((string)($_POST['delivery_city'] ?? ''));
        $distanceKm = (float)($_POST['distance_km'] ?? 0);

        $prices = $this->computePrices($menu, $people, $deliveryCity, $distanceKm);

        $orderId = Order::create([
            'user_id' => (int)auth()['id'],
            'menu_id' => $menuId,
            'status' => 'recue',
            'people_count' => $people,
            'delivery_address' => trim((string)($_POST['delivery_address'] ?? '')),
            'delivery_city' => $deliveryCity,
            'delivery_postal' => trim((string)($_POST['delivery_postal'] ?? '')),
            'delivery_date' => $_POST['delivery_date'] ?? null,
            'delivery_time' => $_POST['delivery_time'] ?? null,
            'delivery_place' => trim((string)($_POST['delivery_place'] ?? '')),
            'distance_km' => $distanceKm,
            'delivery_fee' => $prices['delivery_fee'],
            'menu_price' => $prices['menu_price'],
            'total_price' => $prices['total_price'],
        ]);

        Mailer::send((string)auth()['email'], 'Confirmation de commande', "Votre commande #{$orderId} a ete enregistree.");
        MongoStats::recordOrder($menuId, (string)$menu['title'], $prices['total_price']);

        flash('success', 'Commande enregistree.');
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

        if ((int)$order['user_id'] !== (int)auth()['id']) {
            http_response_code(403);
            echo 'Acces refuse.';
            return;
        }

        $history = Order::history((int)$id);
        $this->view('pages/order_detail', [
            'order' => $order,
            'history' => $history,
        ]);
    }

    public function update(string $id): void
    {
        require_auth();

        $order = Order::find((int)$id);
        if (!$order || (int)$order['user_id'] !== (int)auth()['id']) {
            http_response_code(404);
            $this->view('pages/404');
            return;
        }

        if (in_array((string)$order['status'], ['acceptee', 'en_preparation', 'en_livraison', 'livree', 'en_attente_retour_materiel', 'terminee', 'annulee'], true)) {
            flash('error', 'Modification impossible: commande deja prise en charge.');
            redirect('/orders/' . $id);
        }

        $people = (int)($_POST['people_count'] ?? 0);
        if ($people < (int)$order['min_people']) {
            flash('error', 'Nombre de personnes insuffisant pour ce menu.');
            redirect('/orders/' . $id);
        }

        $deliveryCity = trim((string)($_POST['delivery_city'] ?? ''));
        $distanceKm = (float)($_POST['distance_km'] ?? 0);

        $prices = $this->computePrices($order, $people, $deliveryCity, $distanceKm);

        Order::updateEditable((int)$id, [
            'people_count' => $people,
            'delivery_address' => trim((string)($_POST['delivery_address'] ?? '')),
            'delivery_city' => $deliveryCity,
            'delivery_postal' => trim((string)($_POST['delivery_postal'] ?? '')),
            'delivery_date' => $_POST['delivery_date'] ?? null,
            'delivery_time' => $_POST['delivery_time'] ?? null,
            'delivery_place' => trim((string)($_POST['delivery_place'] ?? '')),
            'distance_km' => $distanceKm,
            'delivery_fee' => $prices['delivery_fee'],
            'menu_price' => $prices['menu_price'],
            'total_price' => $prices['total_price'],
        ]);

        flash('success', 'Commande modifiee.');
        redirect('/orders/' . $id);
    }

    public function cancel(string $id): void
    {
        require_auth();
        $order = Order::find((int)$id);
        if (!$order || (int)$order['user_id'] !== (int)auth()['id'] || $order['status'] !== 'recue') {
            flash('error', 'Annulation impossible.');
            redirect('/orders/' . $id);
        }

        Order::cancel((int)$id, 'Annulation utilisateur', 'compte');
        flash('success', 'Commande annulee.');
        redirect('/orders/' . $id);
    }

    public function review(string $id): void
    {
        require_auth();
        $order = Order::find((int)$id);
        if (!$order || (int)$order['user_id'] !== (int)auth()['id'] || $order['status'] !== 'terminee') {
            flash('error', 'Avis non autorise.');
            redirect('/orders/' . $id);
        }

        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim((string)($_POST['comment'] ?? ''));
        Review::create((int)$id, $rating, $comment);
        flash('success', 'Avis envoye pour validation.');
        redirect('/orders/' . $id);
    }

    private function computePrices(array $menuLike, int $people, string $deliveryCity, float $distanceKm): array
    {
        $deliveryCityNormalized = function_exists('mb_strtolower') ? mb_strtolower($deliveryCity) : strtolower($deliveryCity);

        $deliveryFee = 0.0;
        if ($deliveryCityNormalized !== 'bordeaux') {
            $deliveryFee = 5 + (0.59 * $distanceKm);
        }

        $menuPrice = (float)$menuLike['base_price'];
        if ($people >= ((int)$menuLike['min_people'] + 5)) {
            $menuPrice *= 0.9;
        }

        return [
            'menu_price' => $menuPrice,
            'delivery_fee' => $deliveryFee,
            'total_price' => $menuPrice + $deliveryFee,
        ];
    }
}
