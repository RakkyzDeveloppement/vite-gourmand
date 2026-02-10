<?php

declare(strict_types=1);

session_start();

$router = require __DIR__ . '/../app/bootstrap.php';

csrf_check();

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
