<?php

namespace App\Repository;

use App\Core\Database;
use App\Models\Producto;
use PDO;

class ProductoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Obtener todos los productos, con opción a filtrar por usuario_id
    public function getAll($usuarioId = null)
    {
        // Hacemos JOIN con usuarios para saber el nombre del creador
        $sql = "SELECT p.*, c.categoria_nombre, u.usuario_nombre as creado_por
                FROM productos p
                INNER JOIN categorias c ON p.categoria_id = c.categoria_id
                INNER JOIN usuarios u ON p.usuario_id = u.usuario_id";

        if ($usuarioId !== null) {
            $sql .= " WHERE p.usuario_id = :uid";
        }

        $sql .= " ORDER BY p.producto_id DESC";

        $stmt = $this->db->prepare($sql);

        if ($usuarioId !== null) {
            $stmt->execute([':uid' => $usuarioId]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll();
    }

    // Buscar por ID 
    public function findById($id)
    {
        $sql = "SELECT p.*, c.categoria_nombre 
                FROM productos p
                INNER JOIN categorias c ON p.categoria_id = c.categoria_id
                WHERE p.producto_id = :id 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(Producto $prod)
    {
        $sql = "INSERT INTO productos (usuario_id, categoria_id, producto_codigo, producto_nombre, producto_descripcion, producto_precio, producto_stock) 
                VALUES (:uid, :cat_id, :cod, :nom, :desc, :pre, :stock)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':uid'    => $prod->usuario_id,
            ':cat_id' => $prod->categoria_id,
            ':cod'    => $prod->producto_codigo,
            ':nom'    => $prod->producto_nombre,
            ':desc'   => $prod->producto_descripcion,
            ':pre'    => $prod->producto_precio,
            ':stock'  => $prod->producto_stock
        ]);

        return $this->db->lastInsertId();
    }

    public function update(Producto $prod)
    {
        $sql = "UPDATE productos SET 
                    categoria_id = :cat_id,
                    producto_codigo = :cod,
                    producto_nombre = :nom,
                    producto_descripcion = :desc,
                    producto_precio = :pre,
                    producto_stock = :stock
                WHERE producto_id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':cat_id' => $prod->categoria_id,
            ':cod'    => $prod->producto_codigo,
            ':nom'    => $prod->producto_nombre,
            ':desc'   => $prod->producto_descripcion,
            ':pre'    => $prod->producto_precio,
            ':stock'  => $prod->producto_stock,
            ':id'     => $prod->producto_id
        ]);
        return $stmt->rowCount();
    }
}
