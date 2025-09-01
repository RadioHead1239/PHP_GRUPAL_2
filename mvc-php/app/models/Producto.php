<?php
class Producto {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    private function validarDatos($nombre, $precio, $stock) {
        $errores = [];

        if (empty($nombre)) {
            $errores[] = "El nombre del producto es obligatorio.";
        }

        if (!is_numeric($precio) || $precio <= 0) {
            $errores[] = "El precio debe ser un número mayor a 0.";
        }

        if (!is_numeric($stock) || $stock < 0) {
            $errores[] = "El stock no puede ser negativo.";
        }

        return $errores;
    }

    public function crearProducto($nombre, $descripcion, $precio, $stock) {
        $errores = $this->validarDatos($nombre, $precio, $stock);
        if (!empty($errores)) return $errores;

        $stmt = $this->db->prepare("INSERT INTO Producto (Nombre, Descripcion, Precio, Stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $stock]);

        return true;
    }

    public function listarProductos() {
        $stmt = $this->db->query("SELECT * FROM Producto WHERE Estado = 1 ORDER BY FechaRegistro DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
