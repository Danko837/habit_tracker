<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private Database $database;
    private User $userModel;

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->userModel = new User($database);
    }

    public function showLoginForm(): void
    {
        require __DIR__ . '/../views/login.php';
    }

    public function showRegisterForm(): void
    {
        require __DIR__ . '/../views/register.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLoginForm();
            return;
        }
        verifyCsrf();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $error = null;

        if (empty($email) || empty($password)) {
            $error = 'E-mail a heslo sú povinné.';
        } else {
            $user = $this->userModel->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                redirect('/dashboard');
            } else {
                $error = 'Neplatný e-mail alebo heslo.';
            }
        }

        require __DIR__ . '/../views/login.php';
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showRegisterForm();
            return;
        }
        verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $error = null;

        if (empty($name) || empty($email) || empty($password) || empty($passwordConfirm)) {
            $error = 'Všetky polia sú povinné.';
        } elseif ($password !== $passwordConfirm) {
            $error = 'Heslá sa nezhodujú.';
        } else {
            try {
                $this->userModel->register($name, $email, $password);
                flash('success', 'Registrácia bola úspešná. Teraz sa prihlás.');
                redirect('/login');
            } catch (Exception $exception) {
                $error = $exception->getMessage();
            }
        }

        require __DIR__ . '/../views/register.php';
    }
}
