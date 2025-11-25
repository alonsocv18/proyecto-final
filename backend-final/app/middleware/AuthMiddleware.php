<?php

namespace App\Middleware;

use App\Core\Database;

class AuthMiddleware
{

    // Este método devuelve la función que Slim necesita para proteger una ruta
    public static function verificar($app)
    {
        return function () use ($app) {
            $req = $app->request;

            // 1. Intentar obtener el token de Authorization o X-API-KEY
            $token = $req->headers->get('Authorization');
            if (empty($token)) {
                $token = $req->headers->get('X-API-KEY');
            }

            // 2. Si no hay token, rechazamos inmediatamente
            if (empty($token)) {
                $app->halt(401, json_encode([
                    'response' => 'error',
                    'message' => 'Acceso denegado. Falta el Token de autorización.'
                ]));
            }

            // 3. Validar contra la Base de Datos
            try {
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT usuario_id FROM usuarios WHERE usuario_api_key = :token AND usuario_estado = 1 LIMIT 1");
                $stmt->execute([':token' => $token]);
                $user = $stmt->fetch();

                if (!$user) {
                    $app->halt(403, json_encode([
                        'response' => 'error',
                        'message' => 'Token inválido, expirado o usuario inactivo.'
                    ]));
                }


            } catch (\Exception $e) {
                $app->halt(500, json_encode([
                    'response' => 'error',
                    'message' => 'Error interno de autenticación.'
                ]));
            }
        };
    }
}
