<!-- Sidebar -->
<div class="sidebar d-flex flex-column flex-shrink-0 text-white p-4" style="width: 280px; min-height: 100vh;">
  <!-- Logo / Título -->
  <div class="d-flex align-items-center mb-4">
    <div class="bg-primary rounded-3 p-2 me-3">
      <i class="fa-solid fa-cash-register fa-lg text-white"></i>
    </div>
    <div>
      <h4 class="mb-0 text-white fw-bold">Sistema Ventas</h4>
      <small class="text-muted">Dashboard</small>
    </div>
  </div>

  <!-- Menú Principal -->
  <ul class="nav nav-pills flex-column mb-auto">
    <li class="nav-item mb-2">
      <a href="../venta/registrarventa.php" class="nav-link text-white">
        <i class="fa-solid fa-cart-plus"></i>
        <span>Nueva Venta</span>
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="../venta/ventas.php" class="nav-link text-white">
        <i class="fa-solid fa-receipt"></i>
        <span>Ventas</span>
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="../venta/clientes.php" class="nav-link text-white">
        <i class="fa-solid fa-user-group"></i>
        <span>Clientes</span>
      </a>
    </li>

    <?php if ($_SESSION['usuario']['Rol'] === 'Administrador'): ?>
      <hr class="text-secondary my-3">
      <li class="nav-item mb-2">
        <a href="../usuario/admin.php" class="nav-link text-white">
          <i class="fa-solid fa-gauge-high"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="../venta/productos.php" class="nav-link text-white">
          <i class="fa-solid fa-box-open"></i>
          <span>Productos</span>
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="../usuario/usuarios.php" class="nav-link text-white">
          <i class="fa-solid fa-users-gear"></i>
          <span>Usuarios</span>
        </a>
      </li>
    <?php endif; ?>
  </ul>

  <hr class="text-secondary my-3">

  <!-- Footer Sidebar -->
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle p-2 rounded-3" 
       data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.1);">
      <div class="bg-primary rounded-circle p-2 me-3">
        <i class="fa-solid fa-user fa-sm"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold"><?php echo $_SESSION['usuario']['Nombre'] ?? 'Invitado'; ?></div>
        <small class="text-muted"><?php echo $_SESSION['usuario']['Rol'] ?? 'Usuario'; ?></small>
      </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow-lg border-0" style="border-radius: 12px;">
      <li>
        <a class="dropdown-item py-2" href="../../logout.php">
          <i class="fa-solid fa-right-from-bracket me-2"></i> 
          Cerrar Sesión
        </a>
      </li>
    </ul>
  </div>
</div>
