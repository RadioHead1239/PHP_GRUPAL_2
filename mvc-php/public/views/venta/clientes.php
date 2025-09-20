<?php
session_start();

// Solo usuarios logueados
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuario/login.php");
    exit;
}
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<div class="flex-grow-1 p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">
        <i class="fa-solid fa-user-group me-2"></i>Gestión de Clientes
      </h1>
      <p class="text-muted mb-0">Administra la información de tus clientes</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Exportar clientes">
        <i class="fa-solid fa-download me-1"></i>Exportar
      </button>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
        <i class="fa-solid fa-user-plus me-1"></i>Nuevo Cliente
      </button>
    </div>
  </div>

  <!-- Filtros y búsqueda -->
  <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.1s;">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-search me-2"></i>Buscar Cliente
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fa-solid fa-search"></i>
            </span>
            <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre, correo o teléfono...">
          </div>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-filter me-2"></i>Filtrar por Estado
          </label>
          <select class="form-select" id="filtroEstado">
            <option value="">Todos los estados</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-calendar me-2"></i>Fecha de Registro
          </label>
          <select class="form-select" id="filtroFecha">
            <option value="">Todas las fechas</option>
            <option value="hoy">Hoy</option>
            <option value="semana">Esta semana</option>
            <option value="mes">Este mes</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Estadísticas rápidas -->
  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary me-3">
            <i class="fa-solid fa-users"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total Clientes</h6>
            <h3 class="fw-bold mb-0" id="totalClientes">0</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.3s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-success me-3">
            <i class="fa-solid fa-user-check"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Clientes Activos</h6>
            <h3 class="fw-bold mb-0" id="clientesActivos">0</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-warning me-3">
            <i class="fa-solid fa-user-plus"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Nuevos Hoy</h6>
            <h3 class="fw-bold mb-0" id="nuevosHoy">0</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.5s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-info me-3">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Promedio Compras</h6>
            <h3 class="fw-bold mb-0">S/ 250</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de clientes -->
  <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.6s;">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fa-solid fa-list me-2"></i>Lista de Clientes
        </h5>
        <div class="d-flex gap-2">
          <span class="badge bg-primary" id="badgeTotal">Total: 0</span>
          <span class="badge bg-success" id="badgeActivos">Activos: 0</span>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaClientes">
          <thead>
            <tr>
              <th class="border-0">Cliente</th>
              <th class="border-0">Contacto</th>
              <th class="border-0">Dirección</th>
              <th class="border-0">Registrado</th>
              <th class="border-0">Estado</th>
              <th class="border-0">Compras</th>
              <th class="border-0">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Se llenará dinámicamente -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<script>
// 🚀 Función para cargar clientes desde la API
async function cargarClientes() {
    try {
        const res = await fetch("../../api/ClienteService.php?action=listar");
        const clientes = await res.json();

        const tbody = document.getElementById("tablaClientes");
        tbody.innerHTML = "";

        if (!clientes || clientes.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay clientes registrados</td></tr>';
            return;
        }

        clientes.forEach(c => {
            // Formatear fecha de registro
            const fechaRegistro = new Date(c.FechaRegistro).toLocaleDateString('es-PE', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });

            tbody.innerHTML += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-user text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">${c.Nombre}</div>
                                <small class="text-muted">ID: ${c.IdCliente}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div>${c.Correo || 'No especificado'}</div>
                            <small class="text-muted">
                                <i class="fa-solid fa-envelope me-1"></i>Email
                            </small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div>${c.Telefono || 'No especificado'}</div>
                            <small class="text-muted">
                                <i class="fa-solid fa-phone me-1"></i>Teléfono
                            </small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div>${c.Direccion || 'No especificado'}</div>
                            <small class="text-muted">
                                <i class="fa-solid fa-map-marker-alt me-1"></i>Dirección
                            </small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="fw-bold">${fechaRegistro}</div>
                            <small class="text-muted">Registrado</small>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalCliente${c.IdCliente}" data-bs-toggle="tooltip" title="Ver detalles">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>

                        <!-- Modal detalle cliente -->
                        <div class="modal fade" id="modalCliente${c.IdCliente}" tabindex="-1">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title fw-bold">
                                    <i class="fa-solid fa-user me-2 text-primary"></i>Detalle del Cliente
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">
                                                    <i class="fa-solid fa-id-card me-2"></i>Información Personal
                                                </h6>
                                                <p><strong>Nombre:</strong> ${c.Nombre}</p>
                                                <p><strong>ID Cliente:</strong> ${c.IdCliente}</p>
                                                <p><strong>Fecha Registro:</strong> ${fechaRegistro}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">
                                                    <i class="fa-solid fa-address-book me-2"></i>Contacto
                                                </h6>
                                                <p><strong>Correo:</strong> ${c.Correo || 'No especificado'}</p>
                                                <p><strong>Teléfono:</strong> ${c.Telefono || 'No especificado'}</p>
                                                <p><strong>Dirección:</strong> ${c.Direccion || 'No especificado'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <i class="fa-solid fa-chart-line me-2"></i>Estadísticas de Compras
                                        </h6>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="fw-bold text-primary">0</div>
                                                <small class="text-muted">Compras</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold text-success">S/ 0.00</div>
                                                <small class="text-muted">Total Gastado</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold text-warning">-</div>
                                                <small class="text-muted">Última Compra</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-times me-1"></i>Cerrar
                                </button>
                                <button class="btn btn-primary">
                                    <i class="fa-solid fa-pen me-1"></i>Editar Cliente
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
            `;
        });

    } catch (err) {
        console.error("Error al cargar clientes:", err);
        const tbody = document.getElementById("tablaClientes");
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Error al cargar los clientes</td></tr>';
    }
}

// Buscador en vivo
document.getElementById("buscarCliente").addEventListener("keyup", function() {
    const filtro = this.value.toLowerCase();
    document.querySelectorAll("#tablaClientes tr").forEach(fila => {
        const texto = fila.innerText.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

async function cargarEstadisticas() {
  try {
    const response = await fetch('../../api/ClienteService.php?action=estadisticas');
    const stats = await response.json();
    
    // Actualizar cards de estadísticas
    document.getElementById('totalClientes').textContent = stats.totalClientes || 0;
    document.getElementById('clientesActivos').textContent = stats.clientesActivos || 0;
    document.getElementById('clientesNuevosHoy').textContent = stats.clientesNuevosHoy || 0;
    document.getElementById('promedioCompras').textContent = stats.promedioCompras || 0;
  } catch (error) {
    console.error('Error al cargar estadísticas:', error);
  }
}

cargarClientes();
cargarEstadisticas();
</script>

