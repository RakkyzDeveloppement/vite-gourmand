<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
});

$config = require __DIR__ . '/../config/app.php';
$dbConfig = require __DIR__ . '/../config/db.php';

$app = [
    'config' => $config,
    'db' => $dbConfig,
];

function app(): array
{
    global $app;
    return $app;
}

$router = new App\Router();

// Public pages
$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/menus', [App\Controllers\MenuController::class, 'index']);
$router->get('/menus/{id}', [App\Controllers\MenuController::class, 'show']);
$router->get('/contact', [App\Controllers\ContactController::class, 'show']);
$router->post('/contact', [App\Controllers\ContactController::class, 'send']);
$router->get('/legal', [App\Controllers\HomeController::class, 'legal']);
$router->get('/cgv', [App\Controllers\HomeController::class, 'cgv']);

// Auth
$router->get('/signup', [App\Controllers\AuthController::class, 'showRegister']);
$router->post('/signup', [App\Controllers\AuthController::class, 'register']);
$router->get('/signin', [App\Controllers\AuthController::class, 'showLogin']);
$router->post('/signin', [App\Controllers\AuthController::class, 'login']);
$router->get('/signout', [App\Controllers\AuthController::class, 'logout']);
$router->get('/forgot', [App\Controllers\AuthController::class, 'showForgot']);
$router->post('/forgot', [App\Controllers\AuthController::class, 'sendReset']);
$router->get('/reset/{token}', [App\Controllers\AuthController::class, 'showReset']);
$router->post('/reset', [App\Controllers\AuthController::class, 'reset']);

// User space
$router->get('/account', [App\Controllers\AccountController::class, 'index']);
$router->post('/account', [App\Controllers\AccountController::class, 'update']);
$router->get('/orders/new', [App\Controllers\OrderController::class, 'create']);
$router->post('/orders', [App\Controllers\OrderController::class, 'store']);
$router->get('/orders/{id}', [App\Controllers\OrderController::class, 'show']);
$router->post('/orders/{id}/update', [App\Controllers\OrderController::class, 'update']);
$router->post('/orders/{id}/cancel', [App\Controllers\OrderController::class, 'cancel']);
$router->post('/orders/{id}/review', [App\Controllers\OrderController::class, 'review']);

// Employee
$router->get('/employee', [App\Controllers\EmployeeController::class, 'dashboard']);
$router->post('/employee/orders/{id}/status', [App\Controllers\EmployeeController::class, 'updateStatus']);
$router->post('/employee/orders/{id}/cancel', [App\Controllers\EmployeeController::class, 'cancelOrder']);
$router->get('/employee/menus', [App\Controllers\EmployeeController::class, 'menus']);
$router->post('/employee/menus', [App\Controllers\EmployeeController::class, 'saveMenu']);
$router->post('/employee/menus/{id}/delete', [App\Controllers\EmployeeController::class, 'deleteMenu']);
$router->post('/employee/menus/{id}/deactivate', [App\Controllers\EmployeeController::class, 'deactivateMenu']);
$router->post('/employee/menus/{id}/activate', [App\Controllers\EmployeeController::class, 'activateMenu']);
$router->post('/employee/dishes', [App\Controllers\EmployeeController::class, 'saveDish']);
$router->post('/employee/dishes/{id}/delete', [App\Controllers\EmployeeController::class, 'deleteDish']);
$router->post('/employee/schedules', [App\Controllers\EmployeeController::class, 'saveSchedule']);
$router->post('/employee/reviews/{id}/validate', [App\Controllers\EmployeeController::class, 'validateReview']);
$router->post('/employee/reviews/{id}/reject', [App\Controllers\EmployeeController::class, 'rejectReview']);

// Admin
$router->get('/admin', [App\Controllers\AdminController::class, 'dashboard']);
$router->post('/admin/employees', [App\Controllers\AdminController::class, 'createEmployee']);
$router->post('/admin/employees/{id}/disable', [App\Controllers\AdminController::class, 'disableEmployee']);
$router->post('/admin/nosql/sync', [App\Controllers\AdminController::class, 'syncNosql']);

return $router;




