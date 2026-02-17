<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    protected function view(string $path, array $data = []): void
    {
        view($path, $data);
    }
}
