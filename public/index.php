<?php

declare(strict_types=1);

session_start();

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

$router = require __DIR__ . '/../app/bootstrap.php';

csrf_check();

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
