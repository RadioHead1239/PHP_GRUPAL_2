<?php
class Usuario {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function validarDatos($nombre, $correo, $clave) {
        $errores = [];

        if (empty($nombre) || strlen($nombre) < 3) {
            $errores[] = "El nombre debe tener al menos 3 caracteres.";
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Correo no válido.";
        }

        if (strlen($clave) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres.";
        }

        return $errores;
    }

    public function crearUsuario($nombre, $correo, $clave, $rol = "Vendedor") {
        $errores = $this->validarDatos($nombre, $correo, $clave);
        if (!empty($errores)) return $errores;

        $hash = password_hash($clave, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO Usuario (Nombre, Correo, ClaveHash, Rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $hash, $rol]);

        return true;
    }

    public function login($correo, $clave) {
        $stmt = $this->db->prepare("SELECT * FROM Usuario WHERE Correo = ? AND Estado = 1");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($clave, $usuario['ClaveHash'])) {
            return $usuario;
        }

        return false;
    }
}
?>
