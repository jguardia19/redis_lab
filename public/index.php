<?php

declare(strict_types=1);

use App\Controllers\ProductController;
use App\Repositories\ProductRepository;

require_once dirname(__DIR__) . '/vendor/autoload.php';

try {
    $pdo = require dirname(__DIR__) . '/Config/database.php';

    $repository = new ProductRepository($pdo);
    $controller = new ProductController($repository);

    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

    if ($method !== 'GET') {
        http_response_code(405);
        header('Allow: GET');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    if ($path === '/api/productos') {
        $controller->index();
        exit;
    }

    if (preg_match(
        '#^/api/productos/([1-9][0-9]*)$#',
        $path,
        $matches
    )) {
        $controller->show((int) $matches[1]);
        exit;
    }

    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ruta no encontrada']);

} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);
    header('Content-Type: application/json');

    echo json_encode([
        'error' => 'Error interno del servidor'
    ]);
}