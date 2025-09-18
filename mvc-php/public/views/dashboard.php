<?php
session_start();

// Seguridad: si no está logueado, volver al login
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuario/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<!-- Contenido principal -->
<div class="p-4 flex-grow-1">
  <h2 class="mb-4">Bienvenido, <?php echo htmlspecialchars($usuario['Nombre']); ?> 👋</h2>
  <p class="text-muted">Has iniciado sesión como <strong><?php echo $usuario['Rol']; ?></strong>.</p>

  <div class="row mt-4">
    <?php if ($usuario['Rol'] === 'Administrador'): ?>
      <!-- Card Gestión de Usuarios -->
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="fa-solid fa-users fa-3x text-primary mb-3"></i>
            <h5 class="card-title">Usuarios</h5>
            <p class="card-text">Administra usuarios y roles del sistema.</p>
            <a href="../usuario/listar.php" class="btn btn-primary">Ir</a>
          </div>
        </div>
      </div>

      <!-- Card Gestión de Productos -->
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="fa-solid fa-boxes-stacked fa-3x text-success mb-3"></i>
            <h5 class="card-title">Productos</h5>
            <p class="card-text">Gestiona el inventario de productos.</p>
            <a href="../producto/listar.php" class="btn btn-success">Ir</a>
          </div>
        </div>
      </div>

      <!-- Card Reportes -->
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="fa-solid fa-chart-line fa-3x text-warning mb-3"></i>
            <h5 class="card-title">Reportes</h5>
            <p class="card-text">Consulta reportes completos del sistema.</p>
            <a href="../reportes/index.php" class="btn btn-warning">Ir</a>
          </div>
        </div>
      </div>

    <?php elseif ($usuario['Rol'] === 'Vendedor'): ?>
      <!-- Card Registrar Venta -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="fa-solid fa-cash-register fa-3x text-success mb-3"></i>
            <h5 class="card-title">Registrar Venta</h5>
            <p class="card-text">Agrega nuevas ventas al sistema.</p>
            <a href="../venta/crear.php" class="btn btn-success">Ir</a>
          </div>
        </div>
      </div>

      <!-- Card Clientes -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body text-center">
            <i class="fa-solid fa-user fa-3x text-info mb-3"></i>
            <h5 class="card-title">Clientes</h5>
            <p class="card-text">Gestiona tus clientes registrados.</p>
            <a href="../cliente/listar.php" class="btn btn-info">Ir</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
