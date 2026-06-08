<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function basePath(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    return rtrim(str_replace('/index.php', '', $scriptName), '/');
}

function url(string $path = ''): string
{
    $path = '/' . ltrim($path, '/');
    if ($path === '/') {
        $path = '';
    }
    return basePath() . $path;
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }
    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $value;
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Neplatný bezpečnostný token. Skúste akciu zopakovať.');
    }
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/views/' . $template . '.php';
}
