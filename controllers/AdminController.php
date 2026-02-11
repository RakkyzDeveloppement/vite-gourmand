<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Services\MongoStats;

final class AdminController extends Controller
{
    public function dashboard(): void
    {
        require_role('admin');
        $stats = MongoStats::getStats();
        $this->view('admin/dashboard', [
            'stats' => $stats,
        ]);
    }

    public function createEmployee(): void
    {
        require_role('admin');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$email || !$password) {
            flash('error', 'Email et mot de passe requis.');
            redirect('/admin');
        }
        $data = [
            'first_name' => 'Employe',
            'last_name' => 'ViteGourmand',
            'email' => $email,
            'password' => $password,
            'phone' => '',
            'address_line1' => '',
            'postal_code' => '',
            'city' => '',
        ];
        User::create($data, 'employe');
        flash('success', 'Compte employé créé.');
        redirect('/admin');
    }

    public function disableEmployee(string $id): void
    {
        require_role('admin');
        User::disable((int)$id);
        flash('success', 'Compte employé désactivé.');
        redirect('/admin');
    }
}
