<?php

namespace App\Repositories;

use App\Core\Database;

class CategoriaRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT categoria_id, categoria_nombre FROM categorias WHERE categoria_activo = 1");
        return $stmt->fetchAll();
    }

    // CRU: Create
    public function create($nombre)
    {
        $sql = "INSERT INTO categorias (categoria_nombre, categoria_activo) VALUES (:nom, 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nom' => $nombre]);
        return $this->db->lastInsertId();
    }

    // CRU: Update
    public function update($id, $nombre)
    {
        $sql = "UPDATE categorias SET categoria_nombre = :nom WHERE categoria_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nom' => $nombre, ':id' => $id]);
        return $stmt->rowCount();
    }
}
