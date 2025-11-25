<?php

namespace App\Config;

class ErrorHandler
{

    // Esta función se activará cuando ocurra CUALQUIER excepción no capturada
    public static function register($app)
    {
        $app->error(function (\Exception $e) use ($app) {
            
            $logMsg = sprintf(
                "[%s] Error 500: %s en %s línea %d",
                date('Y-m-d H:i:s'),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            );

            // Escribimos en la carpeta logs
            error_log($logMsg . PHP_EOL, 3, __DIR__ . '/../../logs/app.log');

            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setStatus(500);

            echo json_encode([
                'response' => 'error',
                'message'  => 'Ocurrió un error interno en el servidor.',
                'debug_info' => $app->config('debug') ? $e->getMessage() : null
            ]);
        });

        // Manejo para rutas no encontradas (404)
        $app->notFound(function () use ($app) {
            $app->response->headers->set('Content-Type', 'application/json');
            $app->response->setStatus(404);
            echo json_encode([
                'response' => 'error',
                'message' => 'Ruta no encontrada o método incorrecto.'
            ]);
        });
    }
}
