<?php

namespace App\Controllers;

use App\Repository\UsuarioRepository;
use App\Core\Database; 

class AuthController
{
    private $app;
    private $repo;

    public function __construct($app)
    {
        $this->app = $app;
        $this->repo = new UsuarioRepository();
    }

    public function login()
    {
        $body = $this->app->request->getBody();
        $data = json_decode($body, true);

        // 1. Validar datos de entrada
        if (empty($data['usuario_correo']) || empty($data['usuario_password'])) {
            $this->jsonResponse(['response' => 'error', 'message' => 'Falta correo o contraseña.'], 400);
            return;
        }

        try {
            // 2. Buscar usuario
            $usuario = $this->repo->findByEmail($data['usuario_correo']);

            // 3. Verificar contraseña
            if ($usuario && password_verify($data['usuario_password'], $usuario['usuario_password'])) {

                // Verificar estado
                if ($usuario['usuario_estado'] == 0) {
                    $this->jsonResponse(['response' => 'error', 'message' => 'Usuario inactivo.'], 403);
                    return;
                }

                // --- GENERACIÓN DE TOKEN DINÁMICO ---
                // Generar un string aleatorio único
                $nuevoToken = bin2hex(random_bytes(32));

                // Guardar el nuevo token en la BD para este usuario
                $db = Database::getInstance();
                $stmt = $db->prepare("UPDATE usuarios SET usuario_api_key = :token WHERE usuario_id = :id");
                $stmt->execute([
                    ':token' => $nuevoToken,
                    ':id'    => $usuario['usuario_id']
                ]);

                // 4. Responder con el NUEVO token
                $this->jsonResponse([
                    'response' => 'success',
                    'message' => 'Bienvenido al sistema.',
                    'data' => [
                        'usuario_id' => $usuario['usuario_id'],
                        'usuario_nombre' => $usuario['usuario_nombre'],
                        'token' => $nuevoToken 
                    ]
                ], 200);
            } else {
                $this->jsonResponse(['response' => 'error', 'message' => 'Credenciales incorrectas.'], 401);
            }
        } catch (\Exception $e) {
            error_log("Error Login: " . $e->getMessage());
            $this->jsonResponse(['response' => 'error', 'message' => 'Error interno.'], 500);
        }
    }
    
    private function jsonResponse($data, $status = 200)
    {
        $this->app->response->setStatus($status);
        $this->app->response->headers->set('Content-Type', 'application/json');
        $this->app->response->setBody(json_encode($data));
    }
}
