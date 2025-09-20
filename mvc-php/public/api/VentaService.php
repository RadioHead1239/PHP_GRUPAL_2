<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../app/controller/VentaController.php';

$controller = new VentaController();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'listar':
                $ventas = $controller->listar();
                echo json_encode($ventas);
                break;
            case 'obtener':
                $id = $_GET['id'] ?? '';
                if (empty($id) || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID de venta requerido']);
                    break;
                }
                $venta = $controller->obtenerPorId($id);
                if ($venta) {
                    echo json_encode($venta);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Venta no encontrada']);
                }
                break;
            case 'detalle':
                $id = $_GET['id'] ?? '';
                if (empty($id) || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID de venta requerido']);
                    break;
                }
                $detalles = $controller->obtenerDetalle($id);
                echo json_encode($detalles);
                break;
            case 'estadisticas':
                $estadisticas = $controller->obtenerEstadisticas();
                    // Permitir estadísticas por rango de fechas si se pasan los parámetros
                    $fechaInicio = $_GET['fechaInicio'] ?? null;
                    $fechaFin = $_GET['fechaFin'] ?? null;
                    if ($fechaInicio && $fechaFin) {
                        $estadisticas = $controller->obtenerEstadisticas($fechaInicio, $fechaFin);
                    } else {
                        $estadisticas = $controller->obtenerEstadisticas();
                    }
                    echo json_encode($estadisticas);
                break;
            case 'porfecha':
                $fecha = $_GET['fecha'] ?? date('Y-m-d');
                $ventas = $controller->obtenerVentasPorFecha($fecha);
                echo json_encode($ventas);
                break;
            case 'porrango':
                $fechaInicio = $_GET['fechaInicio'] ?? '';
                $fechaFin = $_GET['fechaFin'] ?? '';
                if (empty($fechaInicio) || empty($fechaFin)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Fechas requeridas: fechaInicio y fechaFin']);
                    break;
                }
                $ventas = $controller->obtenerVentasPorRangoFechas($fechaInicio, $fechaFin);
                echo json_encode($ventas);
                break;
            default:
                $ventas = $controller->listar();
                echo json_encode($ventas);
        }
        break;
        
    case 'POST':
        switch ($action) {
            case 'crear':
                $input = json_decode(file_get_contents('php://input'), true);
                
                $data = [
                    'idUsuario' => $input['idUsuario'] ?? '',
                    'idCliente' => $input['idCliente'] ?? null,
                    'productos' => $input['productos'] ?? []
                ];
                
                if (empty($data['idUsuario']) || !is_numeric($data['idUsuario']) || empty($data['productos'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Datos requeridos: idUsuario y productos']);
                    break;
                }
                
                $resultado = $controller->crear($data);
                if ($resultado['success']) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Venta creada exitosamente',
                        'idVenta' => $resultado['idVenta'],
                        'total' => $resultado['total']
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => $resultado['message']]);
                }
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de venta requerido']);
            break;
        }
        
        $resultado = $controller->eliminar($id);
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Venta eliminada exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar venta']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>