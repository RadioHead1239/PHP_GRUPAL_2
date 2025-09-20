<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function crearCliente(Cliente $cliente) {
        try {
            $sql = "CALL sp_CrearCliente(:nombre, :apellido, :email, :telefono, :direccion, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nombre", $cliente->getNombre());
            $stmt->bindValue(":apellido", $cliente->getApellido());
            $stmt->bindValue(":email", $cliente->getEmail());
            $stmt->bindValue(":telefono", $cliente->getTelefono());
            $stmt->bindValue(":direccion", $cliente->getDireccion());
            $stmt->bindValue(":estado", (int)$cliente->getEstado(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error crear cliente: " . $e->getMessage());
            return false;
        }
    }

    public function leerClientes() {
        try {
            $sql = "CALL sp_LeerClientes()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            error_log("Error leer clientes: " . $e->getMessage());
            return [];
        }
    }

    public function leerClientePorId($id) {
        try {
            $sql = "CALL sp_LeerClientePorId(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result;
        } catch (PDOException $e) {
            error_log("Error leer cliente: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarCliente(Cliente $cliente) {
        try {
            $sql = "CALL sp_ActualizarCliente(:id, :nombre, :apellido, :email, :telefono, :direccion, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $cliente->getIdCliente(), PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $cliente->getNombre());
            $stmt->bindValue(":apellido", $cliente->getApellido());
            $stmt->bindValue(":email", $cliente->getEmail());
            $stmt->bindValue(":telefono", $cliente->getTelefono());
            $stmt->bindValue(":direccion", $cliente->getDireccion());
            $stmt->bindValue(":estado", (int)$cliente->getEstado(), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error actualizar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarCliente($id) {
        try {
            $sql = "CALL sp_EliminarCliente(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error eliminar cliente: " . $e->getMessage());
            return false;
        }
    }
}
?>
