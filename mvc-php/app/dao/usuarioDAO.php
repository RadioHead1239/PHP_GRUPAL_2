<?php
require_once __DIR__ . "//../config/database.php";
require_once __DIR__ . "//../models/Usuario.php";


class UsuarioDAO  {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

public function login($correo, $clave) {
    try {
        $sql = "CALL sp_LoginUsuario(:correo)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":correo", $correo);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && $clave === $data['ClaveHash']) {
            return new Usuario(
                $data['IdUsuario'],
                $data['Nombre'],
                $data['Correo'],
                $data['ClaveHash'],
                $data['Rol'],
                $data['Estado'],
                $data['FechaRegistro']
            );
        }

        return null;
    } catch (PDOException $e) {
    error_log("Error en login: " . $e->getMessage()); // 👈 log en PHP, no echo
    return null;
    }
}



    public function crearUsuario(Usuario $usuario) {
        try {
            $sql = "CALL sp_CrearUsuario(:nombre, :correo, :clave, :rol)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":nombre", $usuario->getNombre());
            $stmt->bindValue(":correo", $usuario->getCorreo());
            $stmt->bindValue(":clave", $usuario->getClaveHash());
            $stmt->bindValue(":rol", $usuario->getRol());
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al crear usuario: " . $e->getMessage();
            return false;
        }
    }

    public function leerUsuarios() {
        try {
            $sql = "CALL sp_LeerUsuarios()";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al leer usuarios: " . $e->getMessage();
            return [];
        }
    }



    public function actualizarUsuario(Usuario $usuario) {
        try {
            $sql = "CALL sp_ActualizarUsuario(:id, :nombre, :correo, :clave, :rol, :estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $usuario->getIdUsuario(), PDO::PARAM_INT);
            $stmt->bindValue(":nombre", $usuario->getNombre());
            $stmt->bindValue(":correo", $usuario->getCorreo());
            $stmt->bindValue(":clave", $usuario->getClaveHash());
            $stmt->bindValue(":rol", $usuario->getRol());
            $stmt->bindValue(":estado", $usuario->getEstado(), PDO::PARAM_BOOL);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar usuario: " . $e->getMessage();
            return false;
        }
    }

    public function eliminarUsuario($idUsuario) {
        try {
            $sql = "CALL sp_EliminarUsuario(:id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(":id", $idUsuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar usuario: " . $e->getMessage();
            return false;
        }
    }



}
