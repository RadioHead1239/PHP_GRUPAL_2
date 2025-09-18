<!-- Sidebar -->
<div class="bg-dark text-white p-3 vh-100" style="width: 220px;">
  <h4 class="text-center mb-4">
    <i class="fa-solid fa-cash-register"></i> Ventas
  </h4>
  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a href="../venta/nueva.php" class="nav-link text-white">
        <i class="fa-solid fa-cart-plus"></i> Nueva Venta
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="../venta/listado.php" class="nav-link text-white">
        <i class="fa-solid fa-receipt"></i> Ventas
      </a>
    </li>
    <li class="nav-item mb-2">
      <a href="../cliente/listado.php" class="nav-link text-white">
        <i class="fa-solid fa-user-group"></i> Clientes
      </a>
    </li>

    <?php if ($_SESSION['usuario']['Rol'] === 'Administrador'): ?>
      <li class="nav-item mb-2">
        <a href="../producto/listado.php" class="nav-link text-white">
          <i class="fa-solid fa-box-open"></i> Productos
        </a>
      </li>
      <li class="nav-item mb-2">
        <a href="../usuario/listado.php" class="nav-link text-white">
          <i class="fa-solid fa-users-gear"></i> Usuarios
        </a>
      </li>
    <?php endif; ?>

    <li class="nav-item mt-4">
      <a href="../../logout.php" class="nav-link text-danger">
        <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
      </a>
    </li>
  </ul>
</div>
