<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

$correo = $input['correo'] ?? '';
$clave = $input['clave'] ?? '';

if (empty($correo) || empty($clave)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Correo y contraseña son requeridos']);
    exit();
}

try {
    // Usar el stored procedure para obtener el usuario
    $stmt = $pdo->prepare("CALL sp_LoginUsuario(?)");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
        exit();
    }
    
    // Verificar la contraseña
    // Verificar la contraseña (sin hash, comparación directa)
if ($clave === $usuario['ClaveHash']) {
    // Iniciar sesión
    session_start();
    $_SESSION['usuario'] = [
        'IdUsuario' => $usuario['IdUsuario'],
        'Nombre' => $usuario['Nombre'],
        'Correo' => $usuario['Correo'],
        'Rol' => $usuario['Rol'],
        'Estado' => $usuario['Estado']
    ];
    
    // Registrar log de acceso
    $stmt = $pdo->prepare("INSERT INTO LogAcceso (IdUsuario, Accion) VALUES (?, ?)");
    $stmt->execute([$usuario['IdUsuario'], 'Login exitoso']);
    
    echo json_encode([
        'success' => true,
        'message' => 'Login exitoso',
        'usuario' => [
            'IdUsuario' => $usuario['IdUsuario'],
            'Nombre' => $usuario['Nombre'],
            'Correo' => $usuario['Correo'],
            'Rol' => $usuario['Rol']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales inválidas']);
}
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
?>