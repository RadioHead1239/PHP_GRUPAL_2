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

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'SistemaVentas';
$username = 'root';
$password = 'sqladmin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        switch ($action) {
            case 'listar':
                listarUsuarios($pdo);
                break;
            case 'obtener':
                obtenerUsuario($pdo);
                break;
            case 'log':
                obtenerLogUsuario($pdo);
                break;
            case 'detalle':
                detalleUsuario($pdo);
                break;
            default:
                listarUsuarios($pdo);
        }
        break;

    case 'POST':
        switch ($action) {
            case 'crear':
                crearUsuario($pdo);
                break;
            case 'registrarlog':
                registrarLogAcceso($pdo);
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        }
        break;

    case 'PUT':
        actualizarUsuario($pdo);
        break;

    case 'DELETE':
        eliminarUsuario($pdo);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

function listarUsuarios($pdo) {
    try {
        $stmt = $pdo->prepare("CALL sp_LeerUsuarios()");
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($usuarios);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al obtener usuarios: ' . $e->getMessage()]);
    }
}

function obtenerUsuario($pdo) {
    $id = $_GET['id'] ?? '';
    
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("CALL sp_LeerUsuarioPorId(?)");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            echo json_encode($usuario);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al obtener usuario: ' . $e->getMessage()]);
    }
}

// Obtener detalle de usuario y su log
function detalleUsuario($pdo) {
    $id = $_GET['id'] ?? '';
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
        return;
    }
    try {
        // Usuario
        $stmt = $pdo->prepare("CALL sp_LeerUsuarioPorId(?)");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        // Log
        $stmt2 = $pdo->prepare("CALL sp_ObtenerLogUsuario(?)");
        $stmt2->execute([$id]);
        $log = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['usuario' => $usuario, 'log' => $log]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al obtener detalle: ' . $e->getMessage()]);
    }
}

function crearUsuario($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nombre = $input['nombre'] ?? '';
    $correo = $input['correo'] ?? '';
    $clave = $input['clave'] ?? '';
    $rol = $input['rol'] ?? 'Vendedor';
    
    if (empty($nombre) || empty($correo) || empty($clave)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos requeridos: nombre, correo, clave']);
        return;
    }
    
    // Validar que el rol sea válido
    if (!in_array($rol, ['Administrador', 'Vendedor'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Rol inválido. Debe ser Administrador o Vendedor']);
        return;
    }
    
    // Hash de la contraseña
    $claveHash = password_hash($clave, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("CALL sp_CrearUsuario(?, ?, ?, ?)");
        $stmt->execute([$nombre, $correo, $claveHash, $rol]);
        
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error de clave duplicada
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $e->getMessage()]);
        }
    }
}

function actualizarUsuario($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? '';
    $nombre = $input['nombre'] ?? '';
    $correo = $input['correo'] ?? '';
    $clave = $input['clave'] ?? '';
    $rol = $input['rol'] ?? '';
    $estado = $input['estado'] ?? true;
    
    if (empty($id) || !is_numeric($id) || empty($nombre) || empty($correo) || empty($rol)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos requeridos: id, nombre, correo, rol']);
        return;
    }
    
    // Validar que el rol sea válido
    if (!in_array($rol, ['Administrador', 'Vendedor'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Rol inválido. Debe ser Administrador o Vendedor']);
        return;
    }
    
    // Hash de la contraseña si se proporciona
    $claveHash = !empty($clave) ? password_hash($clave, PASSWORD_DEFAULT) : '';
    
    try {
        if (!empty($clave)) {
            $stmt = $pdo->prepare("CALL sp_ActualizarUsuario(?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id, $nombre, $correo, $claveHash, $rol, $estado]);
            registrarLog($pdo, $id, 'Editar usuario (con cambio de clave)');
        } else {
            // Si no se proporciona nueva contraseña, mantener la actual
            $stmt = $pdo->prepare("
                UPDATE Usuario 
                SET Nombre = ?, Correo = ?, Rol = ?, Estado = ? 
                WHERE IdUsuario = ?
            ");
            $stmt->execute([$nombre, $correo, $rol, $estado, $id]);
            registrarLog($pdo, $id, 'Editar usuario (sin cambio de clave)');
        }
        
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Error de clave duplicada
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }
}

function eliminarUsuario($pdo) {
    $id = $_GET['id'] ?? '';
    
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("CALL sp_EliminarUsuario(?)");
        $stmt->execute([$id]);
        registrarLog($pdo, $id, 'Eliminar usuario');
        
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()]);
    }
}

// Registrar log de acceso/acción
function registrarLog($pdo, $idUsuario, $accion) {
    try {
        $stmt = $pdo->prepare("CALL sp_RegistrarLogAcceso(?, ?)");
        $stmt->execute([$idUsuario, $accion]);
    } catch (PDOException $e) {
        // No interrumpir la acción principal si falla el log
    }
}

// Endpoint para registrar log desde el frontend si se requiere
function registrarLogAcceso($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    $idUsuario = $input['idUsuario'] ?? '';
    $accion = $input['accion'] ?? '';
    if (empty($idUsuario) || empty($accion)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos requeridos: idUsuario, accion']);
        return;
    }
    registrarLog($pdo, $idUsuario, $accion);
    echo json_encode(['success' => true, 'message' => 'Log registrado']);
}

// Endpoint para obtener log de usuario
function obtenerLogUsuario($pdo) {
    $id = $_GET['id'] ?? '';
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de usuario requerido']);
        return;
    }
    try {
        $stmt = $pdo->prepare("CALL sp_ObtenerLogUsuario(?)");
        $stmt->execute([$id]);
        $log = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($log);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al obtener log: ' . $e->getMessage()]);
    }
}
?>
