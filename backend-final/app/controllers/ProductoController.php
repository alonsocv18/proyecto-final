<?php

namespace App\Controllers;
    
use App\Repository\ProductoRepository;
use App\Models\Producto;
use App\Validators\ProductoValidator;

class ProductoController
{
    private $app;
    private $repo;

    public function __construct($app)
    {
        $this->app = $app;
        $this->repo = new ProductoRepository();
    }

    // GET /productos
    public function getAll()
    {
        // Leemos si viene el parámetro 'usuario_id' en la URL
        $usuarioId = $this->app->request->get('usuario_id');

        try {
            // Pasamos ese ID (o null) al repositorio
            $data = $this->repo->getAll($usuarioId);

            $this->jsonResponse([
                'response' => 'success',
                'total' => count($data),
                'filtro_usuario' => $usuarioId ? $usuarioId : 'todos',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            error_log("Error en getAll: " . $e->getMessage());
            $this->jsonResponse(['response' => 'error', 'message' => 'Error al obtener productos.'], 500);
        }
    }

    // GET /productos/:id
    public function getOne($id)
    {
        try {
            $producto = $this->repo->findById($id);

            if (!$producto) {
                $this->jsonResponse(['response' => 'error', 'message' => 'Producto no encontrado.'], 404);
                return;
            }

            $this->jsonResponse(['response' => 'success', 'data' => $producto]);
        } catch (\Exception $e) {
            error_log("Error getOne: " . $e->getMessage());
            $this->jsonResponse(['response' => 'error', 'message' => 'Error al obtener el producto.'], 500);
        }
    }

    // POST /productos
    public function create()
    {
        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // 1. Validar integridad de datos
        $errors = ProductoValidator::validate($data);
        if (!empty($errors)) {
            $this->jsonResponse(['response' => 'error', 'message' => 'Datos inválidos', 'errors' => $errors], 400);
            return;
        }

        try {
            // 2. Mapear JSON a Entidad
            $prod = new Producto();

            // Producto añadido por x usuario
            $prod->usuario_id          = isset($data['usuario_id']) ? $data['usuario_id'] : 1;

            $prod->categoria_id        = $data['categoria_id'];
            $prod->producto_codigo     = $data['producto_codigo'];
            $prod->producto_nombre     = $data['producto_nombre'];
            $prod->producto_descripcion = isset($data['producto_descripcion']) ? $data['producto_descripcion'] : '';
            $prod->producto_precio     = $data['producto_precio'];
            $prod->producto_stock      = isset($data['producto_stock']) ? $data['producto_stock'] : 0;

            // 3. Guardar en BD
            $id = $this->repo->create($prod);

            // 4. Respuesta Éxito
            $this->jsonResponse([
                'response' => 'success',
                'message' => 'Producto creado correctamente.',
                'producto_id' => $id
            ], 201);
        } catch (\PDOException $e) {
            error_log("Error DB: " . $e->getMessage());
            if ($e->getCode() == 23000) {
                $this->jsonResponse(['response' => 'error', 'message' => 'El Código SKU ya existe.'], 409);
            } else {
                $this->jsonResponse(['response' => 'error', 'message' => 'Error interno al guardar.'], 500);
            }
        } catch (\Exception $e) {
            error_log("Error General: " . $e->getMessage());
            $this->jsonResponse(['response' => 'error', 'message' => 'Ocurrió un error inesperado.'], 500);
        }
    }

    // PUT /productos/:id
    public function update($id)
    {
        // 1. Verificar existencia
        $existente = $this->repo->findById($id);
        if (!$existente) {
            $this->jsonResponse(['response' => 'error', 'message' => 'Producto no encontrado.'], 404);
            return;
        }

        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // 2. Validar
        $errors = ProductoValidator::validate($data);
        if (!empty($errors)) {
            $this->jsonResponse(['response' => 'error', 'message' => 'Datos inválidos', 'errors' => $errors], 400);
            return;
        }

        try {
            $prod = new Producto();
            $prod->producto_id          = $id;
            $prod->categoria_id         = $data['categoria_id'];
            $prod->producto_codigo      = $data['producto_codigo'];
            $prod->producto_nombre      = $data['producto_nombre'];
            $prod->producto_descripcion = isset($data['producto_descripcion']) ? $data['producto_descripcion'] : '';
            $prod->producto_precio      = $data['producto_precio'];
            $prod->producto_stock       = isset($data['producto_stock']) ? $data['producto_stock'] : 0;

            $this->repo->update($prod);

            $this->jsonResponse(['response' => 'success', 'message' => 'Producto actualizado.'], 200);
        } catch (\Exception $e) {
            $this->jsonResponse(['response' => 'error', 'message' => 'Error al actualizar.'], 500);
        }
    }

    private function jsonResponse($data, $status = 200)
    {
        $this->app->response->setStatus($status);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
}
