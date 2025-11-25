<?php

namespace App\Controllers;

use App\Repositories\CategoriaRepository;

class CategoriaController
{
    private $app;
    private $repo;

    public function __construct($app)
    {
        $this->app = $app;
        $this->repo = new CategoriaRepository();
    }

    // GET /categorias
    public function getAll()
    {
        $data = $this->repo->getAll();
        $this->jsonResponse(['response' => 'success', 'data' => $data]);
    }

    // POST /categorias
    public function create()
    {
        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // Validación de negocio (Error 400)
        if (empty($data['categoria_nombre'])) {
            $this->app->halt(400, json_encode([
                'response' => 'error',
                'message' => 'El nombre de la categoría es obligatorio.'
            ]));
        }
        
        $id = $this->repo->create($data['categoria_nombre']);

        $this->jsonResponse([
            'response' => 'success',
            'message' => 'Categoría creada.',
            'id' => $id
        ], 201);
    }

    // PUT /categorias/:id
    public function update($id)
    {
        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // Validación de negocio
        if (empty($data['categoria_nombre'])) {
            $this->app->halt(400, json_encode([
                'response' => 'error',
                'message' => 'El nombre es obligatorio para editar.'
            ]));
        }

        // Ejecución directa
        $this->repo->update($id, $data['categoria_nombre']);

        $this->jsonResponse(['response' => 'success', 'message' => 'Categoría actualizada.']);
    }

    private function jsonResponse($data, $status = 200)
    {
        $this->app->response->setStatus($status);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
}
