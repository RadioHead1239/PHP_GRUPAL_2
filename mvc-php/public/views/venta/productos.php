<?php
session_start();

// Solo admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['Rol'] !== 'Administrador') {
    header("Location: ../usuario/login.php");
    exit;
}
?>

<?php include '../layout/header.php'; ?>
<?php include '../layout/sidebar.php'; ?>

<div class="p-4 flex-grow-1">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-box-open me-2"></i> Gestión de Productos</h2>
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
      <i class="fa-solid fa-plus"></i> Nuevo Producto
    </button>
  </div>

  <div class="row g-4">
    <?php
    $productos = [
      [
        "id" => 1,
        "nombre" => "Laptop Gamer",
        "precio" => 3500,
        "stock" => 5,
        "estado" => true,
        "imagenes" => ["../../assets/img/laptop1.jpg", "../../assets/img/laptop2.jpg"]
      ],
      [
        "id" => 2,
        "nombre" => "Mouse Inalámbrico",
        "precio" => 120,
        "stock" => 20,
        "estado" => true,
        "imagenes" => ["../../assets/img/mouse1.jpg"]
      ],
      [
        "id" => 3,
        "nombre" => "Teclado Mecánico",
        "precio" => 250,
        "stock" => 10,
        "estado" => false,
        "imagenes" => ["../../assets/img/teclado1.jpg", "../../assets/img/teclado2.jpg", "../../assets/img/teclado3.jpg"]
      ]
    ];

    foreach ($productos as $p): ?>
      <div class="col-md-4">
  <div class="card product-card shadow-lg border-0 h-100">
    <!-- Imagen -->
    <div class="position-relative">
      <img src="../../assets/img/laptop1.jpg" 
           class="card-img-top producto-img rounded-top"
           alt="Laptop Gamer"
           data-bs-toggle="modal" 
           data-bs-target="#modalImagenes1">
      <span class="badge estado-badge bg-success">Activo</span>
    </div>

    <!-- Info -->
    <div class="card-body text-center">
      <h5 class="card-title fw-bold text-dark">Laptop Gamer</h5>

      <!-- 👇 Descripción resumida -->
      <p class="text-muted small mb-2">
        Laptop de alto rendimiento con procesador Intel i7, 16GB RAM y tarjeta RTX 3060...
      </p>

      <p class="mb-1">Precio: 
        <span class="fw-bold text-success">S/ 3,500.00</span>
      </p>
      <p class="mb-2">Stock: 
        <span class="fw-bold text-primary">5</span>
      </p>
    </div>

    <!-- Botones -->
    <div class="card-footer d-flex justify-content-between bg-light border-0">
      <button class="btn btn-sm btn-outline-primary w-50 me-1"
        data-bs-toggle="modal" 
        data-bs-target="#modalEditar1">
        <i class="fa-solid fa-pen"></i> Editar
      </button>
      <button class="btn btn-sm btn-outline-danger w-50 ms-1 btnEliminar" 
        data-id="1" 
        data-nombre="Laptop Gamer">
        <i class="fa-solid fa-trash"></i> Eliminar
      </button>
    </div>
  </div>
</div>


      <!-- Modal de imágenes -->
      <div class="modal fade" id="modalImagenes<?php echo $p['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content border-0 shadow">
            <div class="modal-body p-0">
              <div id="carousel<?php echo $p['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <?php foreach ($p['imagenes'] as $i => $img): ?>
                    <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>">
                      <img src="<?php echo $img; ?>" class="d-block w-100 rounded" alt="Imagen <?php echo $i+1; ?>">
                    </div>
                  <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo $p['id']; ?>" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo $p['id']; ?>" data-bs-slide="next">
                  <span class="carousel-control-next-icon"></span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Editar Producto -->
      <div class="modal fade" id="modalEditar<?php echo $p['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title"><i class="fa-solid fa-pen"></i> Editar Producto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <form>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" value="<?php echo $p['nombre']; ?>">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Precio</label>
                    <input type="number" class="form-control" step="0.01" value="<?php echo $p['precio']; ?>">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" value="<?php echo $p['stock']; ?>">
                  </div>
                  <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control">Descripción ejemplo...</textarea>
                  </div>
                  <div class="col-12">
                    <label class="form-label">Imágenes</label>
                    <input type="file" class="form-control" multiple>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button class="btn btn-primary">Guardar Cambios</button>
            </div>
          </div>
        </div>
      </div>

    <?php endforeach; ?>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Modal Crear Producto -->
<div class="modal fade" id="modalCrear" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fa-solid fa-plus"></i> Nuevo Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formCrearProducto" method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nombre</label>
              <input type="text" class="form-control" name="Nombre" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Precio</label>
              <input type="number" class="form-control" step="0.01" name="Precio" required>
            </div>
            <div class="col-md-3">
              <label class="form-label">Stock</label>
              <input type="number" class="form-control" name="Stock" required>
            </div>
            <div class="col-12">
              <label class="form-label">Descripción</label>
              <textarea class="form-control" name="Descripcion"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Imagen principal</label>
              <input type="file" class="form-control" name="Imagen" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Estado</label>
              <select class="form-select" name="Estado">
                <option value="1" selected>Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" type="submit" form="formCrearProducto">Guardar</button>
      </div>
    </div>
  </div>
</div>


<!-- SweetAlert y JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.querySelectorAll(".btnEliminar").forEach(btn => {
    btn.addEventListener("click", function () {
      const id = this.dataset.id;
      const nombre = this.dataset.nombre;

      Swal.fire({
        title: "¿Eliminar producto?",
        text: `El producto "${nombre}" será eliminado permanentemente.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6"
      }).then(result => {
        if (result.isConfirmed) {
          Swal.fire("Eliminado", "El producto ha sido eliminado.", "success");
          // Aquí llamas a tu backend con fetch/AJAX para eliminar en BD
        }
      });
    });
  });
</script>

<!-- Estilos -->
<style>
  .product-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 15px;
    overflow: hidden;
  }
  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.15);
  }
  .producto-img {
    height: 200px;
    object-fit: cover;
  }
  .estado-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.8rem;
    padding: 6px 10px;
    border-radius: 12px;
  }
</style>
