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
  <h2>Dashboard Administrativo</h2>
  <p>Bienvenido, <strong><?php echo htmlspecialchars($usuario['Nombre']); ?></strong></p>

  <!-- Métricas rápidas -->
  <div class="row mt-4">
    <div class="col-md-3">
      <div class="card text-bg-primary shadow-sm p-3 text-center">
        <h5>Usuarios</h5>
        <h2>25</h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-success shadow-sm p-3 text-center">
        <h5>Productos</h5>
        <h2>80</h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-warning shadow-sm p-3 text-center">
        <h5>Clientes</h5>
        <h2>40</h2>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-bg-danger shadow-sm p-3 text-center">
        <h5>Ventas</h5>
        <h2>120</h2>
      </div>
    </div>
  </div>

  <!-- Gráficos -->
  <div class="row mt-5">
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="text-center">Ventas por Mes</h5>
        <canvas id="ventasMesChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm p-3">
        <h5 class="text-center">Productos más Vendidos</h5>
        <canvas id="productosChart"></canvas>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Ventas por Mes (ejemplo estático)
  const ventasMesCtx = document.getElementById('ventasMesChart').getContext('2d');
  new Chart(ventasMesCtx, {
    type: 'line',
    data: {
      labels: ['2025-04', '2025-05', '2025-06', '2025-07', '2025-08', '2025-09'],
      datasets: [{
        label: 'Ventas (S/.)',
        data: [1200, 1500, 1800, 1300, 2000, 2200],
        borderColor: '#0d6efd',
        backgroundColor: 'rgba(13,110,253,0.2)',
        fill: true,
        tension: 0.3
      }]
    }
  });

  // Productos más Vendidos (ejemplo estático)
  const productosCtx = document.getElementById('productosChart').getContext('2d');
  new Chart(productosCtx, {
    type: 'bar',
    data: {
      labels: ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Impresora'],
      datasets: [{
        label: 'Cantidad Vendida',
        data: [50, 70, 40, 35, 20],
        backgroundColor: ['#198754','#0d6efd','#ffc107','#dc3545','#20c997']
      }]
    }
  });
</script>
