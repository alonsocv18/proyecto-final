<?php

namespace App\Controllers;

use App\Repositories\MovimientoRepository;
use Exception;

class MovimientoController
{
    private $app;
    private $repo;

    public function __construct($app)
    {
        $this->app = $app;
        $this->repo = new MovimientoRepository();
    }

    // GET /api/movimientos/producto/:id
    public function getHistory($productoId)
    {
        $data = $this->repo->getByProductoId($productoId);
        $this->jsonResponse(['response' => 'success', 'data' => $data]);
    }

    // POST /api/movimientos
    public function create()
    {
        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // 1. Validaciones de entrada (Error 400)
        if (empty($data['producto_id']) || empty($data['movimiento_tipo']) || empty($data['movimiento_cantidad'])) {
            $this->app->halt(400, json_encode(['response' => 'error', 'message' => 'Faltan datos del movimiento.']));
        }

        // Asignamos usuario (Por defecto 1 o el que venga)
        $usuarioId = isset($data['usuario_id']) ? $data['usuario_id'] : 1;

        // 2. Ejecución directa

        $payload = [
            'producto_id'         => $data['producto_id'],
            'usuario_id'          => $usuarioId,
            'movimiento_tipo'     => $data['movimiento_tipo'],
            'movimiento_motivo'   => $data['movimiento_motivo'],
            'movimiento_cantidad' => $data['movimiento_cantidad']
        ];

        $id = $this->repo->registrar($payload);

        $this->jsonResponse([
            'response' => 'success',
            'message' => 'Movimiento registrado y stock actualizado.',
            'id' => $id
        ], 201);
    }

    private function jsonResponse($data, $status = 200)
    {
        $this->app->response->setStatus($status);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
}
