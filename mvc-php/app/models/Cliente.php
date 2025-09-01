<?php
class Cliente {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    private function validarDatos($nombre, $correo) {
        $errores = [];

        if (empty($nombre)) {
            $errores[] = "El nombre es obligatorio.";
        }

        if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Correo no válido.";
        }

        return $errores;
    }

    public function crearCliente($nombre, $correo, $telefono, $direccion) {
        $errores = $this->validarDatos($nombre, $correo);
        if (!empty($errores)) return $errores;

        $stmt = $this->db->prepare("INSERT INTO Cliente (Nombre, Correo, Telefono, Direccion) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $telefono, $direccion]);

        return true;
    }

    public function listarClientes() {
        $stmt = $this->db->query("SELECT * FROM Cliente ORDER BY FechaRegistro DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
