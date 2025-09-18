<?php
session_start();

// Seguridad: solo usuarios logueados
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuario/login.php");
    exit;
}

include '../layout/header.php';
include '../layout/sidebar.php';
?>

<div class="p-4 flex-grow-1">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-user-group me-2"></i> Clientes</h2>
    <div>
      <button class="btn btn-outline-success btn-sm"><i class="fa-solid fa-file-excel"></i> Exportar Excel</button>
      <button class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-file-pdf"></i> Exportar PDF</button>
    </div>
  </div>

  <!-- Buscador -->
  <div class="input-group mb-3 shadow-sm">
    <span class="input-group-text bg-light"><i class="fa-solid fa-search"></i></span>
    <input type="text" id="buscarCliente" class="form-control" placeholder="Buscar cliente por nombre, correo o teléfono...">
  </div>

  <!-- Tabla clientes -->
  <div class="card shadow-sm">
    <div class="card-body">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Registrado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tablaClientes">
          <?php
          // Clientes de ejemplo
          $clientes = [
            ["id" => 1, "nombre" => "Carlos López", "correo" => "carlos@mail.com", "telefono" => "987654321", "direccion" => "Av. Siempre Viva 123", "fecha" => "2025-09-10"],
            ["id" => 2, "nombre" => "Ana Torres", "correo" => "ana@mail.com", "telefono" => "912345678", "direccion" => "Calle Lima 456", "fecha" => "2025-09-12"],
            ["id" => 3, "nombre" => "Luis Fernández", "correo" => "luis@mail.com", "telefono" => "956123789", "direccion" => "Jr. Puno 789", "fecha" => "2025-09-15"]
          ];

          foreach ($clientes as $c): ?>
            <tr>
              <td><?php echo $c['nombre']; ?></td>
              <td><?php echo $c['correo']; ?></td>
              <td><?php echo $c['telefono']; ?></td>
              <td><?php echo $c['direccion']; ?></td>
              <td><?php echo $c['fecha']; ?></td>
              <td>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalCliente<?php echo $c['id']; ?>">
                  <i class="fa-solid fa-eye"></i> Ver
                </button>
              </td>
            </tr>

            <!-- Modal detalle cliente -->
            <div class="modal fade" id="modalCliente<?php echo $c['id']; ?>" tabindex="-1">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa-solid fa-user"></i> Detalle Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Nombre:</strong> <?php echo $c['nombre']; ?></p>
                    <p><strong>Correo:</strong> <?php echo $c['correo']; ?></p>
                    <p><strong>Teléfono:</strong> <?php echo $c['telefono']; ?></p>
                    <p><strong>Dirección:</strong> <?php echo $c['direccion']; ?></p>
                    <p><strong>Fecha Registro:</strong> <?php echo $c['fecha']; ?></p>
                    
                    <hr>
                    <h6><i class="fa-solid fa-receipt"></i> Últimas Compras</h6>
                    <ul>
                      <li>Venta #1001 - Laptop Gamer - S/ 3500 (12/09/2025)</li>
                      <li>Venta #1012 - Mouse Inalámbrico - S/ 120 (14/09/2025)</li>
                    </ul>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>

          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Script búsqueda en vivo -->
<script>
  document.getElementById("buscarCliente").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    document.querySelectorAll("#tablaClientes tr").forEach(fila => {
      let texto = fila.innerText.toLowerCase();
      fila.style.display = texto.includes(filtro) ? "" : "none";
    });
  });
</script>
