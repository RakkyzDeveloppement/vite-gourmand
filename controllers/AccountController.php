<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Models\Order;

final class AccountController extends Controller
{
    public function index(): void
    {
        require_auth();
        $user = User::findById((int)auth()['id']);
        $orders = Order::byUser((int)auth()['id']);
        $this->view('auth/account', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    public function update(): void
    {
        require_auth();
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address_line1' => trim($_POST['address_line1'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
        ];
        User::update((int)auth()['id'], $data);
        flash('success', 'Informations mises à jour.');
        redirect('/account');
    }
}
