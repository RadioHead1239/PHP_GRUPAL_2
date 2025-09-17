<?php
// Verificar si es una petición AJAX
$esPeticionAjax = ($_SERVER["REQUEST_METHOD"] === "POST" && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false);

if ($esPeticionAjax) {
    header("Content-Type: application/json");

    require_once "database.php";
    require_once "usuario.php";

    // Conexión usando Database.php
    $database = new Database();
    $conexion = $database->getConnection();
    $usuarioModel = new Usuario($conexion);

    // Capturar datos del frontend
    $data = json_decode(file_get_contents("php://input"), true);
    $correo = $data["correo"] ?? "";
    $clave = $data["clave"] ?? "";

    // Llamar método login
    $resultado = $usuarioModel->login($correo, $clave);

    if ($resultado) {
        echo json_encode([
            "success" => true,
            "rol" => $resultado["Rol"],
            "nombre" => $resultado["Nombre"]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Correo o contraseña incorrectos"
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow p-4" style="width: 350px;">
            <div class="d-flex justify-content-center pb-3 fs-6">
                <i class="fa-solid fa-circle-user fa-2xl"></i>
            </div>
            <form id="loginForm">
                <div class="pb-3">
                    <label for="correo" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correo" placeholder="Ingresa tu correo" required>
                </div>

                <div class="pd-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="clave" placeholder="Ingresa tu contraseña" required>
                </div>

                <div class="d-grid pt-3">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            <div id="mensaje" class="mt-3 text-center text-danger"></div>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>