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
<div class="flex-grow-1 p-4">
  <!-- Header del Panel -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">
        <i class="fa-solid fa-cash-register me-2"></i>Panel de Ventas
      </h1>
      <p class="text-muted mb-0">
        <i class="fa-solid fa-user me-2"></i>
        Hola, <strong><?php echo htmlspecialchars($usuario['Nombre']); ?></strong>. ¡Listo para vender!
      </p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ventas del día">
        <i class="fa-solid fa-chart-bar me-1"></i>
        <span class="badge bg-primary ms-1">5</span>
      </button>
      <button class="btn btn-outline-success" data-bs-toggle="tooltip" title="Meta del día">
        <i class="fa-solid fa-target me-1"></i>
        S/ 2,500
      </button>
    </div>
  </div>

  <!-- Métricas rápidas del vendedor -->
  <div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.1s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-success me-3">
            <i class="fa-solid fa-cart-plus"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Ventas Hoy</h6>
            <h3 class="fw-bold mb-0">5</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>+25% vs ayer
            </small>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary me-3">
            <i class="fa-solid fa-dollar-sign"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Ingresos Hoy</h6>
            <h3 class="fw-bold mb-0">S/ 1,250</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>+18% vs ayer
            </small>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.3s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-warning me-3">
            <i class="fa-solid fa-user-group"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Clientes Atendidos</h6>
            <h3 class="fw-bold mb-0">12</h3>
            <small class="text-info">
              <i class="fa-solid fa-arrow-right me-1"></i>Promedio: 8/día
            </small>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-info me-3">
            <i class="fa-solid fa-percentage"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Meta Diaria</h6>
            <h3 class="fw-bold mb-0">50%</h3>
            <div class="progress mt-2" style="height: 4px;">
              <div class="progress-bar bg-info" style="width: 50%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Acciones principales -->
  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.5s;">
        <div class="card-body text-center p-5">
          <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
               style="width: 100px; height: 100px;">
            <i class="fa-solid fa-cart-plus fa-3x text-white"></i>
          </div>
          <h4 class="fw-bold mb-3">Nueva Venta</h4>
          <p class="text-muted mb-4">Registra una nueva venta de manera rápida y eficiente</p>
          <a href="../venta/registrarventa.php" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-plus me-2"></i>Crear Venta
          </a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.6s;">
        <div class="card-body text-center p-5">
          <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
               style="width: 100px; height: 100px;">
            <i class="fa-solid fa-user-group fa-3x text-white"></i>
          </div>
          <h4 class="fw-bold mb-3">Gestionar Clientes</h4>
          <p class="text-muted mb-4">Consulta y administra la información de tus clientes</p>
          <a href="../venta/clientes.php" class="btn btn-success btn-lg">
            <i class="fa-solid fa-users me-2"></i>Ver Clientes
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Accesos rápidos -->
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.7s;">
        <div class="card-body text-center">
          <i class="fa-solid fa-receipt fa-2x text-primary mb-3"></i>
          <h5 class="fw-bold">Ver Ventas</h5>
          <p class="text-muted small">Consulta el historial de ventas</p>
          <a href="../venta/ventas.php" class="btn btn-outline-primary btn-sm">Acceder</a>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.8s;">
        <div class="card-body text-center">
          <i class="fa-solid fa-chart-pie fa-2x text-warning mb-3"></i>
          <h5 class="fw-bold">Estadísticas</h5>
          <p class="text-muted small">Revisa tu rendimiento de ventas</p>
          <button class="btn btn-outline-warning btn-sm" disabled>Próximamente</button>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.9s;">
        <div class="card-body text-center">
          <i class="fa-solid fa-cog fa-2x text-secondary mb-3"></i>
          <h5 class="fw-bold">Configuración</h5>
          <p class="text-muted small">Ajusta tus preferencias</p>
          <button class="btn btn-outline-secondary btn-sm" disabled>Próximamente</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Dashboard Script -->
<script src="../../assets/js/dashboard.js"></script>

<?php include '../layout/footer.php'; ?>
