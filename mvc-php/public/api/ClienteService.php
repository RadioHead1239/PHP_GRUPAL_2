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

require_once __DIR__ . '/../../app/controller/ClienteController.php';

$controller = new ClienteController();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'listar':
                $clientes = $controller->listar();
                echo json_encode($clientes);
                break;
            case 'obtener':
                $id = $_GET['id'] ?? '';
                if (empty($id) || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID de cliente requerido']);
                    break;
                }
                $cliente = $controller->obtenerPorId($id);
                if ($cliente) {
                    echo json_encode($cliente);
                } else {
                    http_response_code(404);
                    echo json_encode(['success' => false, 'message' => 'Cliente no encontrado']);
                }
                break;
            case 'estadisticas':
                $estadisticas = obtenerEstadisticasClientes();
                echo json_encode($estadisticas);
                break;
            case 'ventas':
                $id = $_GET['id'] ?? '';
                if (empty($id) || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID de cliente requerido']);
                    break;
                }
                $ventas = obtenerVentasPorCliente($id);
                echo json_encode($ventas);
                break;
            default:
                $clientes = $controller->listar();
                echo json_encode($clientes);
        }
        break;
        
    case 'POST':
        switch ($action) {
            case 'crear':
                $input = json_decode(file_get_contents('php://input'), true);
                
                $data = [
                    'nombre' => $input['nombre'] ?? '',
                    'apellido' => $input['apellido'] ?? '',
                    'email' => $input['correo'] ?? '',
                    'telefono' => $input['telefono'] ?? '',
                    'direccion' => $input['direccion'] ?? '',
                    'estado' => 1
                ];
                
                if (empty($data['nombre'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
                    break;
                }
                
                $resultado = $controller->crear($data);
                if ($resultado) {
                    echo json_encode(['success' => true, 'message' => 'Cliente creado exitosamente']);
                } else {
                    http_response_code(500);
                    echo json_encode(['success' => false, 'message' => 'Error al crear cliente']);
                }
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
        break;
        
    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        
        $data = [
            'IdCliente' => $input['id'] ?? '',
            'Nombre' => $input['nombre'] ?? '',
            'Apellido' => $input['apellido'] ?? '',
            'Email' => $input['correo'] ?? '',
            'Telefono' => $input['telefono'] ?? '',
            'Direccion' => $input['direccion'] ?? '',
            'Estado' => $input['estado'] ?? 1
        ];
        
        if (empty($data['IdCliente']) || !is_numeric($data['IdCliente']) || empty($data['Nombre'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID y nombre son requeridos']);
            break;
        }
        
        $resultado = $controller->editar($data);
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Cliente actualizado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar cliente']);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de cliente requerido']);
            break;
        }
        
        $resultado = $controller->eliminar($id);
        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Cliente eliminado exitosamente']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar cliente']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function obtenerEstadisticasClientes() {
    require_once __DIR__ . '/../../app/config/database.php';
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stats = [];
        
        // Total de clientes
        $stmt = $conn->query("SELECT COUNT(*) as total FROM Cliente");
        $stats['totalClientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Clientes activos (que han hecho al menos una compra)
        $stmt = $conn->query("
            SELECT COUNT(DISTINCT c.IdCliente) as total 
            FROM Cliente c 
            INNER JOIN Venta v ON c.IdCliente = v.IdCliente
        ");
        $stats['clientesActivos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Clientes nuevos hoy
        $stmt = $conn->query("
            SELECT COUNT(*) as total 
            FROM Cliente 
            WHERE DATE(FechaRegistro) = CURDATE()
        ");
        $stats['clientesNuevosHoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Promedio de compras por cliente
        $stmt = $conn->query("
            SELECT 
                COUNT(DISTINCT v.IdCliente) as clientesConCompras,
                COUNT(v.IdVenta) as totalCompras,
                CASE 
                    WHEN COUNT(DISTINCT v.IdCliente) > 0 
                    THEN ROUND(COUNT(v.IdVenta) / COUNT(DISTINCT v.IdCliente), 2)
                    ELSE 0 
                END as promedioCompras
            FROM Venta v
        ");
        $promedio = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['promedioCompras'] = $promedio['promedioCompras'];
        
        return $stats;
    } catch (PDOException $e) {
        error_log("Error obtener estadísticas clientes: " . $e->getMessage());
        return [
            'totalClientes' => 0,
            'clientesActivos' => 0,
            'clientesNuevosHoy' => 0,
            'promedioCompras' => 0
        ];
    }
}

function obtenerVentasPorCliente($idCliente) {
    require_once __DIR__ . '/../../app/config/database.php';
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        $stmt = $conn->prepare("CALL sp_VentasPorCliente(?)");
        $stmt->execute([$idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error obtener ventas por cliente: " . $e->getMessage());
        return [];
    }
}
?>