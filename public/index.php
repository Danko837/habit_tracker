<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/BaseModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Habit.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/HabitController.php';

$database = new Database([
    'host' => '127.0.0.1',
    'port' => 3306,
    'dbname' => 'habit_tracker',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
]);

try {
    $database->getConnection();
} catch (RuntimeException $exception) {
    http_response_code(500);
    exit('Nepodarilo sa pripojiť k databáze. Skontrolujte config/Database.php a importujte config/schema.sql.');
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($basePath !== '' && $basePath !== '/' && str_starts_with($path, $basePath)) {
    $path = substr($path, strlen($basePath)) ?: '/';
}
$path = rtrim($path, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$homeController = new HomeController();
$authController = new AuthController($database);
$habitController = new HabitController($database);

switch ([$method, $path]) {
    case ['GET', '/']:
        $homeController->index();
        break;
    case ['GET', '/login']:
    case ['POST', '/login']:
        $authController->login();
        break;
    case ['GET', '/register']:
    case ['POST', '/register']:
        $authController->register();
        break;
    case ['POST', '/logout']:
        verifyCsrf();
        session_destroy();
        redirect('/');
        break;
    case ['GET', '/dashboard']:
        $homeController->dashboard();
        break;
    case ['GET', '/habits']:
        $habitController->index();
        break;
    case ['GET', '/habits/create']:
        $habitController->create();
        break;
    case ['POST', '/habits/store']:
        $habitController->store();
        break;
    case ['GET', '/habits/edit']:
        $habitController->edit($id);
        break;
    case ['POST', '/habits/update']:
        $habitController->update($id);
        break;
    case ['POST', '/habits/delete']:
        $habitController->delete($id);
        break;
    case ['POST', '/habits/toggle']:
        $habitController->toggle($id);
        break;
    case ['GET', '/progress']:
        $habitController->progress();
        break;
    default:
        http_response_code(404);
        view('404');
        break;
}
