<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../app/controller/DashboardController.php';

$controller = new DashboardController();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

switch ($action) {
    case 'admin':
        $stats = $controller->obtenerAdmin();
        echo json_encode(['success' => true, 'data' => $stats]);
        break;
    case 'vendedor':
        $idUsuario = $_GET['idUsuario'] ?? '';
        if (empty($idUsuario) || !is_numeric($idUsuario)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
            break;
        }
        $stats = $controller->obtenerVendedor($idUsuario);
        echo json_encode(['success' => true, 'data' => $stats]);
        break;
    case 'estadisticas':
        $stats = $controller->obtenerEstadisticas();
        echo json_encode(['success' => true, 'data' => $stats]);
        break;
    default:
        $stats = $controller->obtenerAdmin();
        echo json_encode(['success' => true, 'data' => $stats]);
}
?>