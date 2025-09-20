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
<div class="flex-grow-1 p-4">
  <!-- Header del Dashboard -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">Dashboard Administrativo</h1>
      <p class="text-muted mb-0">
        <i class="fa-solid fa-user-shield me-2"></i>
        Bienvenido, <strong><?php echo htmlspecialchars($usuario['Nombre']); ?></strong>
      </p>
    </div>
  </div>

  <!-- Métricas rápidas -->
  <div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.1s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary me-3">
            <i class="fa-solid fa-users"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Usuarios</h6>
            <h3 class="fw-bold mb-0" id="cardUsuarios">-</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>
            </small>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-success me-3">
            <i class="fa-solid fa-box-open"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Productos</h6>
            <h3 class="fw-bold mb-0" id="cardProductos">-</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>
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
            <h6 class="text-muted mb-1">Clientes</h6>
            <h3 class="fw-bold mb-0" id="cardClientes">-</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>
            </small>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-danger me-3">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Ventas</h6>
            <h3 class="fw-bold mb-0" id="cardVentas">-</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Gráficos y estadísticas -->
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.5s;">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fa-solid fa-chart-line me-2"></i>Ventas por Mes
            </h5>
            <div class="btn-group btn-group-sm" role="group">
              <button type="button" class="btn btn-outline-light active">6M</button>
              <button type="button" class="btn btn-outline-light">1A</button>
              <button type="button" class="btn btn-outline-light">Todo</button>
            </div>
          </div>
        </div>
        <div class="card-body">
          <canvas id="ventasMesChart" height="300"></canvas>
        </div>
      </div>
    </div>
    
    <div class="col-lg-4">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.6s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-trophy me-2"></i>Productos más Vendidos
          </h5>
        </div>
        <div class="card-body">
          <canvas id="productosChart" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Actividad reciente -->
  <div class="row g-4 mt-4">
    <div class="col-lg-6">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.7s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-clock me-2"></i>Actividad Reciente
          </h5>
        </div>
        <div class="card-body">
          <div class="timeline" id="actividadReciente"></div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.8s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-bell me-2"></i>Notificaciones
          </h5>
        </div>
        <div class="card-body">
          <div id="notificaciones"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Dashboard dinámico
  let ventasMesChart, productosChart;
  async function cargarDashboard() {
    const res = await fetch('../../api/DashboardService.php?action=admin');
    const json = await res.json();
    if (!json.success) return;
    const d = json.data;
    // Cards
    document.getElementById('cardUsuarios').textContent = d.totalUsuarios;
    document.getElementById('cardProductos').textContent = d.totalProductos;
    document.getElementById('cardClientes').textContent = d.totalClientes;
    document.getElementById('cardVentas').textContent = d.totalVentas;
    // Ventas por mes
    const labelsMes = d.ventasPorMes.map(v => v.mes);
    const dataMes = d.ventasPorMes.map(v => parseInt(v.ventas));
    if (!ventasMesChart) {
      ventasMesChart = new Chart(document.getElementById('ventasMesChart').getContext('2d'), {
        type: 'line',
        data: {
          labels: labelsMes,
          datasets: [{
            label: 'Ventas',
            data: dataMes,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.2)',
            fill: true,
            tension: 0.3
          }]
        }
      });
    } else {
      ventasMesChart.data.labels = labelsMes;
      ventasMesChart.data.datasets[0].data = dataMes;
      ventasMesChart.update();
    }
    // Productos más vendidos
    const labelsProd = d.productosMasVendidos.map(p => p.Nombre);
    const dataProd = d.productosMasVendidos.map(p => parseInt(p.cantidad));
    if (!productosChart) {
      productosChart = new Chart(document.getElementById('productosChart').getContext('2d'), {
        type: 'bar',
        data: {
          labels: labelsProd,
          datasets: [{
            label: 'Cantidad Vendida',
            data: dataProd,
            backgroundColor: ['#198754','#0d6efd','#ffc107','#dc3545','#20c997']
          }]
        }
      });
    } else {
      productosChart.data.labels = labelsProd;
      productosChart.data.datasets[0].data = dataProd;
      productosChart.update();
    }
    // Actividad reciente
    let actHtml = '';
    d.actividadReciente.forEach(a => {
      actHtml += `<div class='timeline-item d-flex mb-3'>
        <div class='timeline-marker bg-primary rounded-circle me-3' style='width: 12px; height: 12px; margin-top: 6px;'></div>
        <div><h6 class='mb-1'>${a.Accion}</h6><p class='text-muted mb-0 small'>${a.UsuarioNombre}</p><small class='text-muted'>${a.Fecha}</small></div>
      </div>`;
    });
    document.getElementById('actividadReciente').innerHTML = actHtml;
    // Notificaciones
    let notifHtml = '';
    d.notificaciones.forEach(n => {
      let icon = n.tipo==='stock'?'<i class="fa-solid fa-exclamation-triangle me-2"></i>':(n.tipo==='reporte'?'<i class="fa-solid fa-info-circle me-2"></i>':'<i class="fa-solid fa-check-circle me-2"></i>');
      let alertClass = n.tipo==='stock'?'alert-warning':(n.tipo==='reporte'?'alert-info':'alert-success');
      notifHtml += `<div class='alert ${alertClass} alert-dismissible fade show' role='alert'>${icon}${n.mensaje}<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>`;
    });
    document.getElementById('notificaciones').innerHTML = notifHtml;
  }
  document.addEventListener('DOMContentLoaded', cargarDashboard);
</script>

<!-- Dashboard Script -->
<script src="../../assets/js/dashboard.js"></script>
