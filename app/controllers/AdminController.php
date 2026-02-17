<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use App\Services\MongoStats;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        require_role('admin');

        $stats = MongoStats::getStats();
        $employees = User::allEmployees();
        $menus = Menu::all([], true);

        $selectedMenuId = (int)($_GET['ca_menu_id'] ?? 0);
        $dateFrom = trim((string)($_GET['ca_from'] ?? ''));
        $dateTo = trim((string)($_GET['ca_to'] ?? ''));

        $caRows = Order::revenueByMenu(
            $selectedMenuId > 0 ? $selectedMenuId : null,
            $dateFrom !== '' ? $dateFrom : null,
            $dateTo !== '' ? $dateTo : null
        );

        $caTotal = Order::totalRevenue(
            $selectedMenuId > 0 ? $selectedMenuId : null,
            $dateFrom !== '' ? $dateFrom : null,
            $dateTo !== '' ? $dateTo : null
        );

        $this->view('admin/dashboard', [
            'stats' => $stats,
            'employees' => $employees,
            'menus' => $menus,
            'caRows' => $caRows,
            'caTotal' => $caTotal,
            'selectedMenuId' => $selectedMenuId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function syncNosql(): void
    {
        require_role('admin');

        $rows = Order::revenueByMenu(null, null, null);
        $ok = MongoStats::rebuildFromRows($rows);

        if ($ok) {
            flash('success', 'Stats NoSQL synchronisees depuis SQL.');
        } else {
            $mongoError = MongoStats::lastError();
            flash('error', 'Echec synchronisation NoSQL. ' . ($mongoError !== '' ? $mongoError : 'Verifier URI Mongo et acces Atlas.'));
        }

        redirect('/admin');
    }

    public function createEmployee(): void
    {
        require_role('admin');

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            flash('error', 'Email et mot de passe requis.');
            redirect('/admin');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Email invalide.');
            redirect('/admin');
        }

        if (User::findByEmail($email)) {
            flash('error', 'Un compte existe deja avec cet email.');
            redirect('/admin');
        }

        $data = [
            'first_name' => 'Employe',
            'last_name' => 'ViteGourmand',
            'email' => $email,
            'password' => $password,
            'phone' => '0000000000',
            'address_line1' => 'A definir',
            'postal_code' => '33000',
            'city' => 'Bordeaux',
        ];

        try {
            User::create($data, 'employe');
        } catch (\Throwable $e) {
            flash('error', 'Creation impossible pour le moment.');
            redirect('/admin');
        }

        flash('success', 'Compte employe cree.');
        redirect('/admin');
    }

    public function disableEmployee(string $id): void
    {
        require_role('admin');
        User::disable((int)$id);
        flash('success', 'Compte employe desactive.');
        redirect('/admin');
    }
}


