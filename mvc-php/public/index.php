<?php
require_once __DIR__ . "/../app/models/Conexion/Conexion.php";


$database = new Database();
$conn = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Prueba de Conexión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            text-align: center;
            padding: 50px;
        }
        .card {
            display: inline-block;
            padding: 20px;
            border-radius: 10px;
            background: white;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.2);
        }
        .success { color: green; font-size: 18px; }
        .error { color: red; font-size: 18px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Prueba de Conexión a la Base de Datos</h2>
        <?php if ($conn): ?>
            <p class="success">✅ Conexión exitosa a <strong>SistemaVentas</strong>.</p>
        <?php else: ?>
            <p class="error">❌ Error al conectar con la base de datos.</p>
        <?php endif; ?>
    </div>
</body>
</html>
