<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function crearProducto(Producto $producto) {
        try {
            // 👇 Debug antes de ejecutar
            error_log("🔎 Insertando producto: " . json_encode([
                "nombre" => $producto->getNombre(),
                "imagen" => $producto->getImagen(),
                "descripcion" => $producto->getDescripcion(),
                "precio" => $producto->getPrecio(),
                "stock" => $producto->getStock(),
                "estado" => $producto->getEstado()
            ]));
    
            $sql = "INSERT INTO Producto (Nombre, Imagen, Descripcion, Precio, Stock, Estado)
                    VALUES (:nombre, :imagen, :descripcion, :precio, :stock, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nombre", $producto->getNombre());
            $stmt->bindValue(":imagen", $producto->getImagen());
            $stmt->bindValue(":descripcion", $producto->getDescripcion());
            $stmt->bindValue(":precio", $producto->getPrecio());
            $stmt->bindValue(":stock", $producto->getStock(), PDO::PARAM_INT);
            $stmt->bindValue(":estado", $producto->getEstado(), PDO::PARAM_BOOL);
    
            $ok = $stmt->execute();
    
            error_log("✅ Producto insertado, filas afectadas: " . $stmt->rowCount());
    
            return $ok;
        } catch (PDOException $e) {
            error_log("❌ Error al crear producto: " . $e->getMessage());
            return false;
        }
    }
    
    
    public function leerProductos() {
        try {
            $sql = "CALL sp_LeerProductos()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer productos: " . $e->getMessage());
            return [];
        }
    }

    public function leerProductoPorId($id) {
        try {
            $sql = "CALL sp_LeerProductoPorId(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer producto: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarProducto(Producto $producto) {
    try {
        $sql = "CALL sp_ActualizarProducto(:id, :nombre, :imagen, :descripcion, :precio, :stock, :estado)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $producto->getIdProducto(), PDO::PARAM_INT);
        $stmt->bindValue(":nombre", $producto->getNombre());
        $stmt->bindValue(":imagen", $producto->getImagen()); // no null
        $stmt->bindValue(":descripcion", $producto->getDescripcion());
        $stmt->bindValue(":precio", $producto->getPrecio());
        $stmt->bindValue(":stock", $producto->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(":estado", $producto->getEstado(), PDO::PARAM_BOOL);

        $ok = $stmt->execute();
        return $ok;
    } catch (PDOException $e) {
        error_log("Error actualizar producto: " . $e->getMessage());
        return false;
    }
}


    public function eliminarProducto($id) {
        try {
            $sql = "CALL sp_EliminarProducto(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminar producto: " . $e->getMessage());
            return false;
        }
    }
}
