<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\Review;

final class EmployeeController extends Controller
{
    public function dashboard(): void
    {
        require_role('employe');
        $status = $_GET['status'] ?? null;
        $orders = Order::allByStatus($status);
        $reviews = Review::pending();
        $this->view('employee/dashboard', [
            'orders' => $orders,
            'reviews' => $reviews,
        ]);
    }

    public function updateStatus(string $id): void
    {
        require_role('employe');
        $status = $_POST['status'] ?? 'recue';
        Order::updateStatus((int)$id, $status);
        if ($status === 'en_attente_retour_materiel') {
            // Notification mail a faire via service si besoin
        }
        flash('success', 'Statut mis à jour.');
        redirect('/employee');
    }

    public function cancelOrder(string $id): void
    {
        require_role('employe');
        $reason = trim($_POST['reason'] ?? '');
        $contactMode = trim($_POST['contact_mode'] ?? '');
        Order::cancel((int)$id, $reason, $contactMode);
        flash('success', 'Commande annulée.');
        redirect('/employee');
    }

    public function menus(): void
    {
        require_role('employe');
        $menus = Menu::all();
        $this->view('employee/menus', ['menus' => $menus]);
    }

    public function saveMenu(): void
    {
        require_role('employe');
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'theme_id' => (int)($_POST['theme_id'] ?? 0),
            'regime_id' => (int)($_POST['regime_id'] ?? 0),
            'min_people' => (int)($_POST['min_people'] ?? 0),
            'base_price' => (float)($_POST['base_price'] ?? 0),
            'conditions_text' => trim($_POST['conditions_text'] ?? ''),
            'stock' => (int)($_POST['stock'] ?? 0),
        ];
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        Menu::save($data, $id);
        flash('success', 'Menu enregistré.');
        redirect('/employee/menus');
    }

    public function deleteMenu(string $id): void
    {
        require_role('employe');
        Menu::delete((int)$id);
        flash('success', 'Menu supprimé.');
        redirect('/employee/menus');
    }

    public function validateReview(string $id): void
    {
        require_role('employe');
        Review::validate((int)$id, true);
        flash('success', 'Avis validé.');
        redirect('/employee');
    }

    public function rejectReview(string $id): void
    {
        require_role('employe');
        Review::validate((int)$id, false);
        flash('success', 'Avis refusé.');
        redirect('/employee');
    }
}
