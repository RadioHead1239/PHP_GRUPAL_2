<!-- Sidebar -->
<div class="d-flex flex-column flex-shrink-0 bg-dark text-white p-3 vh-100 shadow" style="width: 240px;">
  <!-- Logo / Título -->
  <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"> <!-- el redireccionamiento queda pendiente o se quita -->
    <i class="fa-solid fa-cash-register fa-lg me-2"></i>
    <span class="fs-5 fw-bold">Sistema Ventas</span>
  </a>
  <hr class="text-secondary">

  <!-- Menú -->
  <ul class="nav nav-pills flex-column mb-auto">


    <li class="nav-item">
      <a href="../venta/registrarventa.php" class="nav-link text-white">
        <i class="fa-solid fa-cart-plus me-2"></i> Nueva Venta
      </a>
    </li>
    <li>
      <a href="../venta/ventas.php" class="nav-link text-white">
        <i class="fa-solid fa-receipt me-2"></i> Ventas
      </a>
    </li>
    <li>
      <a href="../venta/clientes.php" class="nav-link text-white">
        <i class="fa-solid fa-user-group me-2"></i> Clientes
      </a>
    </li>

    <?php if ($_SESSION['usuario']['Rol'] === 'Administrador'): ?>
      <hr class="text-secondary">
          <li class="nav-item mb-2">
      <a href="../usuario/admin.php" class="nav-link text-white">
        <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
      </a>
    </li>

      <li>
        <a href="../venta/productos.php" class="nav-link text-white">
          <i class="fa-solid fa-box-open me-2"></i> Productos
        </a>
      </li>
      <li>
        <a href="../usuario/usuarios.php" class="nav-link text-white">
          <i class="fa-solid fa-users-gear me-2"></i> Usuarios
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <hr class="text-secondary">

  <!-- Footer Sidebar -->
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
      <i class="fa-solid fa-user-circle fa-lg me-2"></i>
      <strong><?php echo $_SESSION['usuario']['Nombre'] ?? 'Invitado'; ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
      <li><a class="dropdown-item" href="../../logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Sesión</a></li>
    </ul>
  </div>
</div>

<!-- Estilos hover -->
<style>
  .nav-link {
    border-radius: 6px;
    padding: 8px 12px;
    transition: all 0.2s;
  }
  .nav-link:hover {
    background-color: #0d6efd !important;
    color: #fff !important;
  }
</style>
