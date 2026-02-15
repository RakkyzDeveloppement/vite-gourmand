<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Services\Mailer;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/signin');
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);
        if (!$user || !($user['is_active'] ?? 0)) {
            flash('error', 'Identifiants invalides.');
            redirect('/signin');
        }

        if (!password_verify($password, $user['password_hash'])) {
            flash('error', 'Identifiants invalides.');
            redirect('/signin');
        }

        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'role' => $user['role'],
        ];
        redirect('/account');
    }

    public function showRegister(): void
    {
        $this->view('auth/signup');
    }

    public function register(): void
    {
        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone' => trim($_POST['phone'] ?? ''),
            'address_line1' => trim($_POST['address_line1'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
        ];

        if (!$this->isStrongPassword($data['password'])) {
            flash('error', 'Mot de passe trop faible.');
            redirect('/signup');
        }

        if (User::findByEmail($data['email'])) {
            flash('error', 'Un compte existe déjà avec cet email.');
            redirect('/signup');
        }

        $userId = User::create($data, 'utilisateur');
        Mailer::send($data['email'], 'Bienvenue chez Vite & Gourmand', "Bonjour {$data['first_name']},\nVotre compte a bien été créé.");

        $_SESSION['user'] = [
            'id' => $userId,
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'role' => 'utilisateur',
        ];
        redirect('/account');
    }

    public function logout(): void
    {
        session_destroy();
        redirect('/');
    }

    public function showForgot(): void
    {
        $this->view('auth/forgot');
    }

    public function sendReset(): void
    {
        $email = trim($_POST['email'] ?? '');
        $user = User::findByEmail($email);
        if (!$user) {
            flash('error', 'Email introuvable.');
            redirect('/forgot');
        }

        $token = bin2hex(random_bytes(32));
        $stmt = \App\Models\Database::pdo()->prepare('INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))');
        $stmt->execute(['email' => $email, 'token' => $token]);

        $link = '/reset/' . $token;
        Mailer::send($email, 'Réinitialisation de mot de passe', "Cliquez ici pour réinitialiser votre mot de passe : {$link}");
        flash('success', 'Un email de réinitialisation a été envoyé.');
        redirect('/signin');
    }

    public function showReset(string $token): void
    {
        $this->view('auth/reset', ['token' => $token]);
    }

    public function reset(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        if (!$this->isStrongPassword($password)) {
            flash('error', 'Mot de passe trop faible.');
            redirect('/reset/' . $token);
        }

        $stmt = \App\Models\Database::pdo()->prepare('SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW() LIMIT 1');
        $stmt->execute(['token' => $token]);
        $reset = $stmt->fetch();
        if (!$reset) {
            flash('error', 'Lien invalide ou expir�.');
            redirect('/forgot');
        }

        $stmt = \App\Models\Database::pdo()->prepare('UPDATE users SET password_hash = :hash WHERE email = :email');
        $stmt->execute([
            'hash' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $reset['email'],
        ]);
        \App\Models\Database::pdo()->prepare('DELETE FROM password_resets WHERE token = :token')->execute(['token' => $token]);

        flash('success', 'Mot de passe mis à jour.');
        redirect('/signin');
    }

    private function isStrongPassword(string $password): bool
    {
        if (strlen($password) < 10) {
            return false;
        }
        $hasUpper = preg_match('/[A-Z]/', $password);
        $hasLower = preg_match('/[a-z]/', $password);
        $hasDigit = preg_match('/\d/', $password);
        $hasSpecial = preg_match('/[^A-Za-z0-9]/', $password);
        return $hasUpper && $hasLower && $hasDigit && $hasSpecial;
    }
}
