<?php
require_once __DIR__ . "/../app/dao/UsuarioDAO.php";
$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->leerUsuarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #2b5876, #4e4376);
      min-height: 100vh;
      margin: 0;
      display: flex;
    }
    .sidebar {
      width: 240px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      padding: 20px 10px;
      display: flex;
      flex-direction: column;
      border-right: 1px solid rgba(255,255,255,0.2);
    }
    .sidebar h2 {
      font-size: 1.4rem;
      margin-bottom: 2rem;
      text-align: center;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 15px;
      border-radius: 12px;
      text-decoration: none;
      color: #fff;
      margin: 5px 0;
      transition: background 0.3s;
    }
    .sidebar a:hover {
      background: rgba(255,255,255,0.15);
    }
    .content {
      margin-left: 240px;
      padding: 30px;
      flex: 1;
      color: #333;
    }
    .card-custom {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
      border: none;
      transition: transform 0.2s;
    }
    .card-custom:hover {
      transform: translateY(-4px);
    }
    .card-header {
      font-weight: bold;
      font-size: 1.1rem;
    }
    .table thead {
      background: #2b5876;
      color: #fff;
    }
    .btn-custom {
      border-radius: 30px;
      padding: 8px 20px;
      font-weight: 500;
    }
    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        justify-content: space-around;
      }
      .content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2><i class="fa fa-cogs"></i> Panel</h2>
    <a href="#"><i class="fa fa-users"></i> Usuarios</a>
    <a href="#"><i class="fa fa-box"></i> Productos</a>
    <a href="#"><i class="fa fa-chart-bar"></i> Reportes</a>
    <a href="#"><i class="fa fa-gear"></i> Configuración</a>
  </div>

  <div class="content">
    <h1 class="text-white fw-bold mb-4">👥 Gestión de Usuarios</h1>

    <div class="card card-custom mb-4">
      <div class="card-header bg-gradient text-white" style="background: linear-gradient(45deg,#2193b0,#6dd5ed);">
        <i class="fa fa-user-plus"></i> Crear Usuario
      </div>
      <div class="card-body">
        <form id="formUsuario" class="row g-3">
          <div class="col-md-6">
            <input type="text" class="form-control" id="nombre" placeholder="Nombre completo" required>
          </div>
          <div class="col-md-6">
            <input type="email" class="form-control" id="correo" placeholder="Correo electrónico" required>
          </div>
          <div class="col-md-6">
            <input type="password" class="form-control" id="clave" placeholder="Contraseña" required>
          </div>
          <div class="col-md-6">
            <select id="rol" class="form-select">
              <option value="Vendedor">Vendedor</option>
              <option value="Administrador">Administrador</option>
            </select>
          </div>
          <div class="text-end mt-3">
            <button type="submit" class="btn btn-success btn-custom shadow">
              <i class="fa fa-save"></i> Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <div class="card card-custom">
      <div class="card-header bg-dark text-white">
        <i class="fa fa-list"></i> Lista de Usuarios
      </div>
      <div class="card-body table-responsive">
        <table class="table align-middle table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Rol</th>
              <th>Estado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= $u['IdUsuario'] ?></td>
                <td><?= $u['Nombre'] ?></td>
                <td><?= $u['Correo'] ?></td>
                <td><span class="badge bg-info"><?= $u['Rol'] ?></span></td>
                <td>
                  <?php if ($u['Estado']): ?>
                    <span class="badge bg-success">Activo</span>
                  <?php else: ?>
                    <span class="badge bg-danger">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalEditar" data-id="<?= $u['IdUsuario'] ?>">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar" data-id="<?= $u['IdUsuario'] ?>">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title"><i class="fa fa-edit"></i> Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formEditar">
            <input type="hidden" id="editId">
            <div class="mb-3">
              <label>Nombre</label>
              <input type="text" id="editNombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Correo</label>
              <input type="email" id="editCorreo" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Rol</label>
              <select id="editRol" class="form-select">
                <option value="Vendedor">Vendedor</option>
                <option value="Administrador">Administrador</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Estado</label>
              <select id="editEstado" class="form-select">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
            <button type="submit" class="btn btn-warning w-100">Actualizar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fa fa-trash"></i> Eliminar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <p>¿Estás seguro de eliminar este usuario?</p>
          <input type="hidden" id="deleteId">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-danger" id="btnConfirmEliminar">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/usuarios.js"></script>
</body>
</html>
