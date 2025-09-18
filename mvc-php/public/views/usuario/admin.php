<?php
session_start();

// Seguridad: solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<!-- Contenido principal -->
<div class="p-4 flex-grow-1">
  <h2>Panel de Administración 👑</h2>
  <p>Bienvenido, <strong><?php echo htmlspecialchars($usuario['Nombre']); ?></strong></p>

  <div class="row mt-4">
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <i class="fa-solid fa-users-gear fa-2xl mb-3 text-primary"></i>
        <h5>Gestión de Usuarios</h5>
        <a href="../usuario/listado.php" class="btn btn-sm btn-outline-primary mt-2">Ver usuarios</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <i class="fa-solid fa-boxes-stacked fa-2xl mb-3 text-success"></i>
        <h5>Gestión de Productos</h5>
        <a href="../producto/listado.php" class="btn btn-sm btn-outline-success mt-2">Ver productos</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm p-3">
        <i class="fa-solid fa-chart-line fa-2xl mb-3 text-info"></i>
        <h5>Reportes</h5>
        <a href="../reportes/index.php" class="btn btn-sm btn-outline-info mt-2">Ver reportes</a>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
