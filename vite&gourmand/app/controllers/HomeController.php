<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Review;

final class HomeController extends Controller
{
    public function index(): void
    {
        $reviews = Review::validated();
        $this->view('pages/home', [
            'reviews' => $reviews,
        ]);
    }

    public function legal(): void
    {
        $this->view('pages/legal');
    }

    public function cgv(): void
    {
        $this->view('pages/cgv');
    }
}
