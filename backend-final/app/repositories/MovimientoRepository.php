<?php

namespace App\Repositories;

use App\Core\Database;
use Exception;
use PDO;

class MovimientoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // 1. OBTENER HISTORIAL 
    public function getByProductoId($productoId)
    {
        $sql = "SELECT m.*, u.usuario_nombre 
                FROM movimientos m
                INNER JOIN usuarios u ON m.usuario_id = u.usuario_id
                WHERE m.producto_id = :pid
                ORDER BY m.movimiento_fecha DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $productoId]);
        return $stmt->fetchAll();
    }

    // 2. REGISTRAR MOVIMIENTO 
    public function registrar($datos)
    {
        try {
            $this->db->beginTransaction();

            // A. Insertar el movimiento
            $sqlMov = "INSERT INTO movimientos (producto_id, usuario_id, movimiento_tipo, movimiento_motivo, movimiento_cantidad) 
                       VALUES (:pid, :uid, :tipo, :motivo, :cant)";
            $stmt = $this->db->prepare($sqlMov);
            $stmt->execute([
                ':pid'    => $datos['producto_id'],
                ':uid'    => $datos['usuario_id'],
                ':tipo'   => $datos['movimiento_tipo'],
                ':motivo' => $datos['movimiento_motivo'],
                ':cant'   => $datos['movimiento_cantidad']
            ]);
            $movimientoId = $this->db->lastInsertId();

            // B. Obtener Stock Actual
            $stmtStock = $this->db->prepare("SELECT producto_stock FROM productos WHERE producto_id = :id");
            $stmtStock->execute([':id' => $datos['producto_id']]);
            $prod = $stmtStock->fetch();

            if (!$prod) {
                throw new Exception("El producto no existe.");
            }

            $nuevoStock = $prod['producto_stock'];

            // C. Calcular Nuevo Stock
            if ($datos['movimiento_tipo'] === 'ENTRADA') {
                $nuevoStock += $datos['movimiento_cantidad'];
            } else {
                if ($prod['producto_stock'] < $datos['movimiento_cantidad']) {
                    throw new Exception("Stock insuficiente para realizar esta salida.");
                }
                $nuevoStock -= $datos['movimiento_cantidad'];
            }

            // D. Actualizar Producto
            $sqlUpdate = $this->db->prepare("UPDATE productos SET producto_stock = :stock WHERE producto_id = :id");
            $sqlUpdate->execute([
                ':stock' => $nuevoStock,
                ':id'    => $datos['producto_id']
            ]);

            $this->db->commit();
            return $movimientoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
