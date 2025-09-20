
<!-- Modal Editar Usuario (Nuevo Formulario) -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="editarUsuarioModalLabel"><i class="fa-solid fa-pen me-2"></i>Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEditarUsuario" autocomplete="off">
        <div class="modal-body">
          <input type="hidden" id="editIdUsuario" name="id">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="editNombre" name="nombre" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="editCorreo" name="correo" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contraseña (dejar vacío para no cambiar)</label>
              <input type="password" class="form-control" id="editClave" name="clave" placeholder="Nueva contraseña">
            </div>
            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select class="form-select" id="editRol" name="rol" required>
                <option value="Administrador">Administrador</option>
                <option value="Vendedor">Vendedor</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado</label>
              <select class="form-select" id="editEstado" name="estado">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono (Opcional)</label>
              <input type="tel" class="form-control" id="editTelefono" name="telefono">
            </div>
            <div class="col-12">
              <label class="form-label">Notas Adicionales</label>
              <textarea class="form-control" id="editNotas" name="notas" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
              <button class="btn btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
session_start();


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Administrador') {
    header("Location: login.php"); //esto se debe de reutilizar siempre
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<div class="flex-grow-1 p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">
        <i class="fa-solid fa-users-gear me-2"></i>Gestión de Usuarios
      </h1>
      <p class="text-muted mb-0">Administra los usuarios del sistema</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearUsuarioModal">
        <i class="fa-solid fa-user-plus me-1"></i>Nuevo Usuario
      </button>
    </div>
  </div>

  <!-- Filtros y búsqueda -->
  <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.1s;">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-search me-2"></i>Buscar Usuario
          </label>
          <input type="text" class="form-control" placeholder="Nombre, correo o ID..." id="buscarUsuario">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-filter me-2"></i>Filtrar por Rol
          </label>
          <select class="form-select" id="filtroRol">
            <option value="">Todos los roles</option>
            <option value="Administrador">Administrador</option>
            <option value="Vendedor">Vendedor</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-toggle-on me-2"></i>Estado
          </label>
          <select class="form-select" id="filtroEstado">
            <option value="">Todos los estados</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button class="btn btn-outline-secondary w-100" id="limpiarFiltros">
            <i class="fa-solid fa-eraser me-1"></i>Limpiar
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabla de usuarios -->
  <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.2s;">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fa-solid fa-users me-2"></i>Lista de Usuarios
        </h5>
        <div class="d-flex gap-2">
          <span class="badge bg-primary">Total: 2</span>
          <span class="badge bg-success">Activos: 2</span>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaUsuarios">
          <thead>
            <tr>
              <th class="border-0">#</th>
              <th class="border-0">Usuario</th>
              <th class="border-0">Correo</th>
              <th class="border-0">Rol</th>
              <th class="border-0">Estado</th>
              <th class="border-0">Ventas</th>
              <th class="border-0">Último Acceso</th>
              <th class="border-0">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Usuario Administrador -->
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                       style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user text-white"></i>
                  </div>
                  <span class="fw-bold">1</span>
                </div>
              </td>
              <td>
                <div>
                  <div class="fw-bold">Victor</div>
                  <small class="text-muted">ID: 001</small>
                </div>
              </td>
              <td>
                <div>
                  <div>victor@correo.com</div>
                  <small class="text-muted">
                    <i class="fa-solid fa-check-circle text-success me-1"></i>Verificado
                  </small>
                </div>
              </td>
              <td>
                <span class="badge bg-danger">
                  <i class="fa-solid fa-shield-halved me-1"></i>Administrador
                </span>
              </td>
              <td>
                <span class="badge bg-success">
                  <i class="fa-solid fa-circle me-1"></i>Activo
                </span>
              </td>
              <td>
                <div class="text-center">
                  <div class="fw-bold text-primary">12</div>
                  <small class="text-muted">ventas</small>
                </div>
              </td>
              <td>
                <div>
                  <div class="small">Hace 2 horas</div>
                  <small class="text-muted">Último acceso</small>
                </div>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            
            <!-- Usuario Vendedor -->
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" 
                       style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user text-white"></i>
                  </div>
                  <span class="fw-bold">2</span>
                </div>
              </td>
              <td>
                <div>
                  <div class="fw-bold">Juana</div>
                  <small class="text-muted">ID: 002</small>
                </div>
              </td>
              <td>
                <div>
                  <div>juana@correo.com</div>
                  <small class="text-muted">
                    <i class="fa-solid fa-check-circle text-success me-1"></i>Verificado
                  </small>
                </div>
              </td>
              <td>
                <span class="badge bg-info">
                  <i class="fa-solid fa-cash-register me-1"></i>Vendedor
                </span>
              </td>
              <td>
                <span class="badge bg-success">
                  <i class="fa-solid fa-circle me-1"></i>Activo
                </span>
              </td>
              <td>
                <div class="text-center">
                  <div class="fw-bold text-primary">5</div>
                  <small class="text-muted">ventas</small>
                </div>
              </td>
              <td>
                <div>
                  <div class="small">Hace 30 min</div>
                  <small class="text-muted">Último acceso</small>
                </div>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            
            <!-- Usuario Vendedor 2 -->
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                       style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user text-white"></i>
                  </div>
                  <span class="fw-bold">3</span>
                </div>
              </td>
              <td>
                <div>
                  <div class="fw-bold">Carlos</div>
                  <small class="text-muted">ID: 003</small>
                </div>
              </td>
              <td>
                <div>
                  <div>carlos@correo.com</div>
                  <small class="text-muted">
                    <i class="fa-solid fa-check-circle text-success me-1"></i>Verificado
                  </small>
                </div>
              </td>
              <td>
                <span class="badge bg-info">
                  <i class="fa-solid fa-cash-register me-1"></i>Vendedor
                </span>
              </td>
              <td>
                <span class="badge bg-danger">
                  <i class="fa-solid fa-times-circle me-1"></i>Inactivo
                </span>
              </td>
              <td>
                <div class="text-center">
                  <div class="fw-bold text-primary">8</div>
                  <small class="text-muted">ventas</small>
                </div>
              </td>
              <td>
                <div>
                  <div class="small">Hace 2 días</div>
                  <small class="text-muted">Último acceso</small>
                </div>
              </td>
              <td>
                <div class="btn-group" role="group">
                  <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Ver detalles">
                    <i class="fa-solid fa-eye"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Editar">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal Crear Usuario (Nuevo Formulario) -->
<div class="modal fade" id="crearUsuarioModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fa-solid fa-user-plus me-2"></i> Crear Nuevo Usuario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formCrearUsuario" class="needs-validation" novalidate autocomplete="off">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre Completo</label>
              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan Pérez" required>
              <div class="invalid-feedback">Por favor ingresa el nombre completo.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Correo Electrónico</label>
              <input type="email" class="form-control" id="correo" name="correo" placeholder="usuario@empresa.com" required>
              <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Contraseña</label>
              <div class="input-group">
                <input type="password" class="form-control" id="clave" name="clave" placeholder="Mínimo 8 caracteres" minlength="8" required>
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('clave')">
                  <i class="fa fa-eye"></i>
                </button>
              </div>
              <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirmar Contraseña</label>
              <input type="password" class="form-control" id="confirmarClave" name="confirmarClave" placeholder="Repite la contraseña" required>
              <div class="invalid-feedback">Las contraseñas no coinciden.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Rol</label>
              <select class="form-select" id="rol" name="rol" required>
                <option value="">Selecciona un rol</option>
                <option value="Administrador">Administrador</option>
                <option value="Vendedor">Vendedor</option>
              </select>
              <div class="invalid-feedback">Por favor selecciona un rol.</div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Teléfono (Opcional)</label>
              <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="+51 999 999 999">
            </div>
            <div class="col-12">
              <label class="form-label">Notas Adicionales</label>
              <textarea class="form-control" id="notas" name="notas" rows="2" placeholder="Información adicional sobre el usuario..."></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn btn-primary" data-bs-dismiss="modal">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal Detalle Usuario -->
<div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalleUsuarioModalLabel">Detalle de Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="detalleUsuarioInfo"></div>
        <h6 class="mt-4">Historial de Acciones</h6>
        <table class="table table-sm table-bordered mt-2">
          <thead><tr><th>Acción</th><th>Fecha</th></tr></thead>
          <tbody id="detalleUsuarioLog"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 🚀 Cargar usuarios con AJAX
async function cargarUsuarios() {
  const tbody = document.querySelector('#tablaUsuarios tbody');
  tbody.innerHTML = `<tr><td colspan="8" class="text-center p-3">
    <div class="spinner-border text-primary"></div> Cargando...
  </td></tr>`;

  try {
    const res = await fetch('../../api/UsuarioService.php?action=listar');
    const usuarios = await res.json();

    tbody.innerHTML = usuarios.map((u, i) => `
      <tr>
        <td>${i+1}</td>
        <td><b>${u.Nombre}</b><br><small>ID: ${u.IdUsuario}</small></td>
        <td>${u.Correo}</td>
        <td><span class="badge ${u.Rol==='Administrador'?'bg-danger':'bg-info'}">${u.Rol}</span></td>
        <td><span class="badge ${u.Estado==1?'bg-success':'bg-secondary'}">${u.Estado==1?'Activo':'Inactivo'}</span></td>
        <td class="text-center">-</td>
        <td>-</td>
        <td>
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="verDetalleUsuario(${u.IdUsuario})" title="Ver"><i class="fa fa-eye"></i></button>
            <button class="btn btn-sm btn-outline-warning" onclick="abrirEditarUsuario(${u.IdUsuario})" title="Editar"><i class="fa fa-pen"></i></button>
            <button class="btn btn-sm btn-outline-danger" onclick="eliminarUsuario(${u.IdUsuario})" title="Eliminar"><i class="fa fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="8" class="text-center text-danger">Error al cargar usuarios</td></tr>`;
    console.error("❌ Error cargarUsuarios:", err);
  }
}

// 🚀 Ver detalle y log de usuario
function verDetalleUsuario(id) {
  fetch(`../../api/UsuarioService.php?action=detalle&id=${id}`)
    .then(r => r.json())
    .then(data => {
      let u = data.usuario;
      let info = `
        <b>Nombre:</b> ${u.Nombre}<br>
        <b>Correo:</b> ${u.Correo}<br>
        <b>Rol:</b> ${u.Rol}<br>
        <b>Estado:</b> ${u.Estado==1?'Activo':'Inactivo'}<br>
        <b>Fecha Registro:</b> ${u.FechaRegistro}
      `;
      document.getElementById('detalleUsuarioInfo').innerHTML = info;

      let logHtml = '';
      if (data.log && data.log.length > 0) {
        data.log.forEach(l => {
          logHtml += `<tr><td>${l.Accion}</td><td>${l.Fecha}</td></tr>`;
        });
      } else {
        logHtml = '<tr><td colspan="2">Sin acciones registradas</td></tr>';
      }
      document.getElementById('detalleUsuarioLog').innerHTML = logHtml;

      new bootstrap.Modal(document.getElementById('detalleUsuarioModal')).show();
    })
    .catch(err => console.error("❌ Error detalle usuario:", err));
}

// 🚀 Crear usuario
document.getElementById('formCrearUsuario').addEventListener('submit', async e => {
  e.preventDefault();

  const data = {
    nombre: document.getElementById('nombre').value.trim(),
    correo: document.getElementById('correo').value.trim(),
    clave: document.getElementById('clave').value.trim(),
    rol: document.getElementById('rol').value,
    telefono: document.getElementById('telefono')?.value || '',
    notas: document.getElementById('notas')?.value || ''
  };

  try {
    const res = await fetch('../../api/UsuarioService.php?action=crear', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const json = await res.json();
    console.log("📥 Respuesta crear usuario:", json);

    if (json.success) {
      Swal.fire("✅ Éxito", "Usuario creado correctamente", "success");
      e.target.reset();
      bootstrap.Modal.getInstance(document.getElementById('crearUsuarioModal')).hide();
      cargarUsuarios();
    } else {
      Swal.fire("⚠️ Error", json.message || "No se pudo crear el usuario", "error");
    }
  } catch (err) {
    Swal.fire("❌ Error", "Fallo de conexión con el servidor", "error");
    console.error("Error:", err);
  }
});

// 🚀 Editar usuario
document.getElementById('formEditarUsuario').addEventListener('submit', async e => {
  e.preventDefault();

  const data = {
    id: document.getElementById('editIdUsuario').value,
    nombre: document.getElementById('editNombre').value,
    correo: document.getElementById('editCorreo').value,
    clave: document.getElementById('editClave').value,
    rol: document.getElementById('editRol').value,
    estado: document.getElementById('editEstado').value
  };

  try {
    const res = await fetch('../../api/UsuarioService.php', {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    const json = await res.json();
    console.log("📥 Respuesta editar usuario:", json);

    if (json.success) {
      Swal.fire("✅ Éxito", "Usuario actualizado correctamente", "success");
      bootstrap.Modal.getInstance(document.getElementById('editarUsuarioModal')).hide();
      cargarUsuarios();
    } else {
      Swal.fire("⚠️ Error", json.message || "No se pudo actualizar el usuario", "error");
    }
  } catch (err) {
    Swal.fire("❌ Error", "Fallo de conexión con el servidor", "error");
    console.error("Error:", err);
  }
});

// 🚀 Abrir modal de edición
function abrirEditarUsuario(id) {
  fetch(`../../api/UsuarioService.php?action=obtener&id=${id}`)
    .then(r => r.json())
    .then(u => {
      document.getElementById('editIdUsuario').value = u.IdUsuario;
      document.getElementById('editNombre').value = u.Nombre;
      document.getElementById('editCorreo').value = u.Correo;
      document.getElementById('editClave').value = '';
      document.getElementById('editRol').value = u.Rol;
      document.getElementById('editEstado').value = u.Estado;
      new bootstrap.Modal(document.getElementById('editarUsuarioModal')).show();
    })
    .catch(err => console.error("❌ Error abrir editar usuario:", err));
}

// 🚀 Eliminar usuario con confirmación
function eliminarUsuario(id) {
  Swal.fire({
    title: "¿Eliminar usuario?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  }).then(async result => {
    if (result.isConfirmed) {
      try {
        const res = await fetch(`../../api/UsuarioService.php?id=${id}`, { method: 'DELETE' });
        const json = await res.json();

        if (json.success) {
          Swal.fire("Eliminado", "Usuario eliminado correctamente", "success");
          cargarUsuarios();
        } else {
          Swal.fire("⚠️ Error", json.message || "No se pudo eliminar el usuario", "error");
        }
      } catch (err) {
        Swal.fire("❌ Error", "Fallo de conexión con el servidor", "error");
        console.error("Error eliminar:", err);
      }
    }
  });
}

// 🚀 Inicializar al cargar la vista
document.addEventListener('DOMContentLoaded', function() {
  cargarUsuarios();
});
</script>
