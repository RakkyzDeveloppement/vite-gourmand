<?php

declare(strict_types=1);

function view(string $path, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewPath = __DIR__ . '/views/' . $path . '.php';
    require __DIR__ . '/views/layout.php';
}

function partial(string $path, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/' . $path . '.php';
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function url(string $path): string
{
    return $path;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['_csrf'] ?? '';
        if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo 'CSRF token invalide.';
            exit;
        }
    }
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['flash'][$key] ?? null;
    if ($value !== null) {
        unset($_SESSION['flash'][$key]);
    }
    return $value;
}

function auth(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(): void
{
    if (!auth()) {
        flash('error', 'Veuillez vous connecter pour continuer.');
        redirect('/signin');
    }
}

function require_role(string $role): void
{
    require_auth();
    $user = auth();
    $userRole = $user['role'] ?? '';
    if ($userRole !== $role && $userRole !== 'admin') {
        http_response_code(403);
        echo 'Accés refusé.';
        exit;
    }
}
