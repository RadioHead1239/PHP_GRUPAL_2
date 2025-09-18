<?php
require_once __DIR__ . '/../dao/UsuarioDAO.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function login($correo, $clave) {
    $usuario = $this->usuarioDAO->login($correo, $clave);

    if ($usuario) {
        session_start();
        $_SESSION['usuario'] = [
           "Id"     => $usuario->getIdUsuario(),
                "Nombre" => $usuario->getNombre(),
                "Correo" => $usuario->getCorreo(),
                "Rol"    => $usuario->getRol()
        ];

        if (
            isset($_SERVER['CONTENT_TYPE']) &&
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
        ) {
            // Respuesta para AJAX
            return ["success" => true, "rol" => $usuario->getRol()];
        } else {
            // Respuesta para submit clásico
            if ($usuario->getRol() === 'Administrador') {
                header("Location: /Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/admin.php");
            } else {
                header("Location: /Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/vendedor.php");
            }
            exit;
        }
    } else {
        if (
            isset($_SERVER['CONTENT_TYPE']) &&
            strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
        ) {
            return ["success" => false, "message" => "Credenciales incorrectas"];
        } else {
            header("Location: /Proyecto_Grupal/PHP-GRUPAL/mvc-php/public/views/usuario/login.php?error=Credenciales incorrectas");
            exit;
        }
    }
}

}
