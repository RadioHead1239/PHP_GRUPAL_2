<?php
session_start();

// Seguridad: solo vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Vendedor') {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<!-- Contenido principal -->
<div class="p-4 flex-grow-1">
  <h2>Panel de Ventas 💵</h2>
  <p>Hola, <strong><?php echo htmlspecialchars($usuario['Nombre']); ?></strong>. ¡Listo para vender!</p>

  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <i class="fa-solid fa-cart-plus fa-2xl mb-3 text-primary"></i>
        <h5>Nueva Venta</h5>
        <a href="../venta/nueva.php" class="btn btn-sm btn-outline-primary mt-2">Registrar venta</a>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <i class="fa-solid fa-user-group fa-2xl mb-3 text-success"></i>
        <h5>Clientes</h5>
        <a href="../cliente/listado.php" class="btn btn-sm btn-outline-success mt-2">Ver clientes</a>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
