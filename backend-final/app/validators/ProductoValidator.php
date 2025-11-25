<?php

namespace App\Validators;

class ProductoValidator
{
    public static function validate(array $data)
    {
        $errors = [];

        // Validaciones para los campos que son obligatorios
        if (empty($data['producto_nombre'])) {
            $errors[] = "El campo 'producto_nombre' es obligatorio.";
        }

        if (empty($data['producto_codigo'])) {
            $errors[] = "El campo 'producto_codigo' (SKU) es obligatorio.";
        }

        if (empty($data['categoria_id']) || !is_numeric($data['categoria_id'])) {
            $errors[] = "Debes seleccionar una 'categoria_id' válida.";
        }

        // Validación de tipos y lógica de negocio (Precios y Stock positivos)
        if (!isset($data['producto_precio']) || !is_numeric($data['producto_precio']) || $data['producto_precio'] < 0) {
            $errors[] = "El 'producto_precio' debe ser un número mayor o igual a 0.";
        }

        if (isset($data['producto_stock']) && (!is_numeric($data['producto_stock']) || $data['producto_stock'] < 0)) {
            $errors[] = "El 'producto_stock' no puede ser negativo.";
        }

        return $errors;
    }
}
