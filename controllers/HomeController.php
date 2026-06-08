<?php

declare(strict_types=1);

class HomeController
{
    public function index(): void
    {
        require __DIR__ . '/../views/home.php';
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        require __DIR__ . '/../views/dashboard.php';
    }
}
