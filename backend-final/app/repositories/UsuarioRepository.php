<?php

namespace App\Repository;

use App\Core\Database;
use PDO;

class UsuarioRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Busca un usuario por su correo electrónico para verificar credenciales
    public function findByEmail($correo)
    {
        $sql = "SELECT usuario_id, usuario_nombre, usuario_password, usuario_api_key, usuario_estado 
                FROM usuarios 
                WHERE usuario_correo = :correo 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':correo' => $correo]);

        // Retorna un array asociativo con los datos del usuario o false si no existe
        return $stmt->fetch();
    }
}
