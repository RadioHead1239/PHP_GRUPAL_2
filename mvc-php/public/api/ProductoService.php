<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../app/controller/ProductoController.php';
require_once __DIR__ . '/../../app/config/database.php';

$controller = new ProductoController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($action) {
            case 'listar':
                echo json_encode($controller->listar());
                break;

            case 'obtener':
                $id = $_GET['id'] ?? '';
                if (!$id || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID requerido']);
                    break;
                }
                $producto = $controller->obtenerPorId($id);
                echo $producto
                    ? json_encode(['success' => true, 'data' => $producto])
                    : json_encode(['success' => false, 'message' => 'No encontrado']);
                break;

            case 'estadisticas':
                echo json_encode(obtenerEstadisticasProductos());
                break;

            default:
                echo json_encode($controller->listar());
        }
        break;

    case 'POST':
        switch ($action) {
            case 'crear':
                // Normalizar los datos del formulario
                $data = [
                    'nombre' => $_POST['Nombre'] ?? '',
                    'imagen' => $_POST['Imagen'] ?? null,
                    'descripcion' => $_POST['Descripcion'] ?? null,
                    'precio' => $_POST['Precio'] ?? 0,
                    'stock' => $_POST['Stock'] ?? 0,
                    'estado' => $_POST['Estado'] ?? 1
                ];
                
            
                $ok = $controller->crear($data);
                echo json_encode(['success' => $ok]);
                error_log("Datos recibidos en crear: " . json_encode($data));

                break;
            

            case 'editar':
                $data = $_POST;
                $files = $_FILES ?? [];
                $ok = $controller->editar($data, $files);
                echo json_encode(['success' => $ok]);
                break;

            case 'eliminar':
                $id = $_POST['IdProducto'] ?? '';
                if (!$id || !is_numeric($id)) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'ID requerido']);
                    break;
                }
                $ok = $controller->eliminar($id);
                echo json_encode(['success' => $ok]);
                break;

            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Acción inválida']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

// 📊 Estadísticas con SQL simples
function obtenerEstadisticasProductos() {
    try {
        $db = new Database();
        $conn = $db->getConnection();

        $stats = [];

        // Total de productos
        $stmt = $conn->query("SELECT COUNT(*) as total FROM Producto");
        $stats['totalProductos'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Productos activos
        $stmt = $conn->query("SELECT COUNT(*) as total FROM Producto WHERE Estado = 1");
        $stats['productosActivos'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Stock bajo
        $stmt = $conn->query("SELECT COUNT(*) as total FROM Producto WHERE Stock <= 5 AND Estado = 1");
        $stats['productosStockBajo'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Sin stock
        $stmt = $conn->query("SELECT COUNT(*) as total FROM Producto WHERE Stock = 0 AND Estado = 1");
        $stats['productosSinStock'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Valor total inventario
        $stmt = $conn->query("SELECT SUM(Precio * Stock) as total FROM Producto WHERE Estado = 1");
        $valor = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $stats['valorTotalInventario'] = $valor ? (float)$valor : 0.0;

        return $stats;
    } catch (PDOException $e) {
        error_log("Error estadísticas productos: " . $e->getMessage());
        return [
            'totalProductos' => 0,
            'productosActivos' => 0,
            'productosStockBajo' => 0,
            'valorTotalInventario' => 0,
            'productosSinStock' => 0
        ];
    }
}

