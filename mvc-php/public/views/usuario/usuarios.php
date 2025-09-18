<?php
session_start();

// 🔒 Seguridad: solo admin

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<div class="p-4 flex-grow-1">
  <h2 class="mb-4">👥 Gestión de Usuarios</h2>

  <!-- Botón para abrir modal -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
    <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
  </button>

  <!-- Tabla de usuarios -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <i class="fa-solid fa-users-gear"></i> Lista de Usuarios
    </div>
    <div class="card-body">
      <table class="table table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Ventas</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- Ejemplo estático -->
          <tr>
            <td>1</td>
            <td>Victor</td>
            <td>victor@correo.com</td>
            <td><span class="badge bg-danger">Administrador</span></td>
            <td><span class="badge bg-success">Activo</span></td>
            <td>12</td>
            <td>
              <button class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Juana</td>
            <td>juana@correo.com</td>
            <td><span class="badge bg-info">Vendedor</span></td>
            <td><span class="badge bg-success">Activo</span></td>
            <td>5</td>
            <td>
              <button class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></button>
              <button class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Crear Usuario -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="crearUsuarioModalLabel">
          <i class="fa-solid fa-user-plus"></i> Crear Usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Ícono grande de usuario -->
        <div class="text-center mb-4">
          <i class="fa-solid fa-user-circle fa-5x text-secondary"></i>
        </div>

        <form>
          <div class="mb-3">
            <label for="nombre" class="form-label"><i class="fa-solid fa-id-card"></i> Nombre</label>
            <input type="text" class="form-control" id="nombre" required>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label"><i class="fa-solid fa-envelope"></i> Correo</label>
            <input type="email" class="form-control" id="correo" required>
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label"><i class="fa-solid fa-lock"></i> Clave</label>
            <input type="password" class="form-control" id="clave" required>
          </div>
          <div class="mb-3">
            <label for="rol" class="form-label"><i class="fa-solid fa-user-shield"></i> Rol</label>
            <select class="form-select" id="rol">
              <option value="Vendedor">Vendedor</option>
              <option value="Administrador">Administrador</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary"><i class="fa-solid fa-save"></i> Guardar</button>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('ventasUsuarioChart').getContext('2d');
  const ventasUsuarioChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Victor', 'Ana', 'Luis', 'Juana'],
      datasets: [{
        label: 'Ventas realizadas',
        data: [12, 19, 7, 15], // Datos estáticos de ejemplo
        backgroundColor: 'rgba(13, 110, 253, 0.7)',
        borderColor: 'rgba(13, 110, 253, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
