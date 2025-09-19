<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg border-0 rounded-4" style="max-width: 420px; width: 100%;">
        <div class="card-body p-4">
            <div class="text-center pb-4">
                <i class="fa fa-user-circle fa-3x text-primary"></i>
                <h3 class="pt-2">Iniciar Sesión</h3>
                <p class="text-muted">Accede a tu cuenta</p>
            </div>

<form id="loginForm">
    <div class="pb-3">
        <label for="correo" class="form-label">
            <i class="fa fa-envelope"></i> Correo
        </label>
        <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@email.com" required>
    </div>

    <div class="pb-3 position-relative">
        <label for="clave" class="form-label">
            <i class="fa fa-lock"></i> Clave
        </label>
        <div class="input-group">
            <input type="password" class="form-control" id="clave" name="clave" placeholder="********" required>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fa fa-eye"></i>
            </button>
        </div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fa fa-sign-in-alt"></i> Ingresar
        </button>
    </div>
</form>



            <p id="mensaje" class="pt-3 text-center text-danger"></p>
        </div>
    </div>

    <script src="../../assets/js/login.js"></script>
</body>

</html>