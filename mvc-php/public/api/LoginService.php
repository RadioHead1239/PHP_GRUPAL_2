<?php
require_once __DIR__ . '/../../app/controller/UsuarioController.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);

$correo = $input['correo'] ?? '';
$clave  = $input['clave'] ?? '';

$controller = new UsuarioController();
$response = $controller->login($correo, $clave);

echo json_encode($response);
