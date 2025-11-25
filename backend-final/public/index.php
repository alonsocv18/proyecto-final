<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    die(); 
}

require __DIR__ . '/../vendor/autoload.php';

// CARGA DE ARCHIVOS

// --- CORE ---
require_once __DIR__ . '/../app/core/Database.php';

// --- MODELS ---
require_once __DIR__ . '/../app/models/Producto.php';
require_once __DIR__ . '/../app/models/Usuario.php';

// --- REPOSITORIES ---
require_once __DIR__ . '/../app/repositories/CategoriaRepository.php';
require_once __DIR__ . '/../app/repositories/MovimientoRepository.php';
require_once __DIR__ . '/../app/repositories/ProductoRepository.php';
require_once __DIR__ . '/../app/repositories/UsuarioRepository.php';

// --- VALIDATORS ---
require_once __DIR__ . '/../app/validators/ProductoValidator.php';

// --- MIDDLEWARE ---
require_once __DIR__ . '/../app/middleware/AuthMiddleware.php';

// --- CONTROLLERS ---
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/CategoriaController.php';
require_once __DIR__ . '/../app/controllers/MovimientoController.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';

require_once __DIR__ . '/../app/config/ErrorHandler.php';

use App\Config\ErrorHandler;
use Slim\Slim;

// 3. Inicializar Slim
$app = new Slim([
    'debug' => false, 
    'mode'  => 'production'
]);

// 4. Configuración de Cabeceras Globales (JSON)
$app->response->headers->set('Content-Type', 'application/json');

// ACTIVAR EL MANEJADOR GLOBAL
ErrorHandler::register($app);

// 5. CARGAR LAS RUTAS 
require_once __DIR__ . '/../app/routes/api.php';

// 6. Ejecutar la Aplicación
$app->run();
