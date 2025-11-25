<?php

use App\Controllers\AuthController;
use App\Controllers\ProductoController;
use App\Controllers\CategoriaController;
use App\Controllers\MovimientoController;
use App\Middleware\AuthMiddleware;

// RUTAS PÚBLICAS
$app->post('/login', function () use ($app) {
    (new AuthController($app))->login();
});

$app->get('/status', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    echo json_encode(['status' => 'OK']);
});

// RUTAS PROTEGIDAS (/api)
$app->group('/api', AuthMiddleware::verificar($app), function () use ($app) {

    // --- PRODUCTOS ---
    $app->group('/productos', function () use ($app) {
        $controller = new ProductoController($app);

        $app->get('/', function () use ($controller) {
            $controller->getAll();
        });
        $app->get('/:id', function ($id) use ($controller) {
            $controller->getOne($id);
        });
        $app->post('/', function () use ($controller) {
            $controller->create();
        });
        $app->put('/:id', function ($id) use ($controller) {
            $controller->update($id);
        });
    });

    // --- MOVIMIENTOS ---
    $app->post('/movimientos', function () use ($app) {
        (new MovimientoController($app))->create();
    });

    $app->get('/movimientos/producto/:id', function ($id) use ($app) {
        (new MovimientoController($app))->getHistory($id);
    });

    // --- CATEGORÍAS ---
    $app->group('/categorias', function () use ($app) {
        $controller = new CategoriaController($app);

        // Listar
        $app->get('/', function () use ($controller) {
            $controller->getAll();
        });

        // Crear
        $app->post('/', function () use ($controller) {
            $controller->create();
        });

        // Editar
        $app->put('/:id', function ($id) use ($controller) {
            $controller->update($id);
        });
    });
});
