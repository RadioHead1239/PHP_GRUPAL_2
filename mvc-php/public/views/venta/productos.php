<?php
session_start();

// Solo admin
if (!isset($_SESSION['usuario'])) {
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

  <!-- Contenedor dinámico de productos -->
  <div class="row g-4" id="listaProductos">
    <!-- Aquí se cargan con JS -->
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
              <label class="form-label">Imágenes (separadas por ;)</label>
              <input type="text" class="form-control" name="Imagen" placeholder="img1.jpg;img2.jpg">
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

<!-- Modal Editar Producto -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fa-solid fa-pen"></i> Editar Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarProducto" enctype="multipart/form-data">
  <input type="hidden" name="IdProducto" id="editIdProducto">
  <div class="mb-3">
    <label>Nombre</label>
    <input type="text" class="form-control" name="Nombre" id="editNombre">
  </div>
  <div class="mb-3">
    <label>Precio</label>
    <input type="number" class="form-control" name="Precio" id="editPrecio" step="0.01">
  </div>
  <div class="mb-3">
    <label>Stock</label>
    <input type="number" class="form-control" name="Stock" id="editStock">
  </div>
  <div class="mb-3">
    <label>Descripción</label>
    <textarea class="form-control" name="Descripcion" id="editDescripcion"></textarea>
  </div>
  <div class="mb-3">
    <label>Imagen</label>
    <input type="file" class="form-control" name="Imagen" id="editImagen">
    <input type="hidden" name="ImagenActual" id="editImagenActual"> <!-- 👈 Mantener imagen previa -->
  </div>
  <div class="mb-3">
    <label>Estado</label>
    <select class="form-select" name="Estado" id="editEstado">
      <option value="1">Activo</option>
      <option value="0">Inactivo</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>

      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" type="submit" form="formEditarProducto">Guardar Cambios</button>
      </div>
    </div>
  </div>
</div>


<!-- SweetAlert y JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>

<script>
// 🚀 Cargar productos dinámicamente
async function cargarProductos() {
  try {
    const res = await fetch("../../api/ProductoService.php?action=listar");
    const productos = await res.json();

    const contenedor = document.getElementById("listaProductos");
    contenedor.innerHTML = "";

    productos.forEach(p => {
      const imagenes = p.Imagen ? p.Imagen.split(";") : [];
      const primeraImg = imagenes[0] ? imagenes[0] : "../../assets/img/no-image.png";

      contenedor.innerHTML += `
        <div class="col-md-4 animate__animated animate__fadeIn">
          <div class="card product-card shadow-lg border-0 h-100">
            <div class="position-relative">
              <img src="${primeraImg}" class="card-img-top producto-img rounded-top" alt="${p.Nombre}"
                   data-bs-toggle="modal" data-bs-target="#modalImagenes${p.IdProducto}">
              <span class="badge estado-badge ${p.Estado == 1 ? 'bg-success' : 'bg-danger'}">
                ${p.Estado == 1 ? 'Activo' : 'Inactivo'}
              </span>
            </div>
            <div class="card-body text-center">
              <h5 class="card-title fw-bold text-dark">${p.Nombre}</h5>
              <p class="text-muted small mb-2">${p.Descripcion ? p.Descripcion.substring(0, 60) : ""}...</p>
              <p class="mb-1">Precio: <span class="fw-bold text-success">S/ ${p.Precio}</span></p>
              <p class="mb-2">Stock: <span class="fw-bold text-primary">${p.Stock}</span></p>
            </div>
            <div class="card-footer d-flex justify-content-between bg-light border-0">
             <button class="btn btn-sm btn-outline-primary w-50 me-1 btnEditar"
                data-bs-toggle="modal" data-bs-target="#modalEditar"
                data-id="${p.IdProducto}" data-nombre="${p.Nombre}" 
                data-precio="${p.Precio}" data-stock="${p.Stock}" 
                data-descripcion="${p.Descripcion}" data-imagen="${p.Imagen}" 
                data-estado="${p.Estado}">
                <i class="fa-solid fa-pen"></i> Editar
            </button>

              <button class="btn btn-sm btn-outline-danger w-50 ms-1 btnEliminar"
                      data-id="${p.IdProducto}" data-nombre="${p.Nombre}">
                <i class="fa-solid fa-trash"></i> Eliminar
              </button>
            </div>
          </div>
        </div>

        <!-- Modal con Carousel -->
        <div class="modal fade" id="modalImagenes${p.IdProducto}" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
              <div class="modal-body p-0">
                <div id="carousel${p.IdProducto}" class="carousel slide" data-bs-ride="carousel">
                  <div class="carousel-inner">
                    ${imagenes.map((img, i) => `
                      <div class="carousel-item ${i === 0 ? 'active' : ''}">
                        <img src="${img}" class="d-block w-100 producto-carousel-img" alt="Imagen ${i+1}">
                      </div>
                    `).join('')}
                  </div>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    agregarEventosEliminar();
    agregarEventosEditar();
  } catch (err) {
    console.error("Error al cargar productos:", err);
  }
}


// 🚀 Crear producto
document.getElementById("formCrearProducto").addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append("action", "crear");

  const res = await fetch("../../api/ProductoService.php", {
    method: "POST",
    body: formData
  });
  const data = await res.json();

  if (data.success) {
    Swal.fire("Éxito", "Producto creado correctamente", "success");
    e.target.reset();
    cargarProductos();
    bootstrap.Modal.getInstance(document.getElementById("modalCrear")).hide();
  } else {
    Swal.fire("Error", "No se pudo crear el producto", "error");
  }
});

// 🚀 Editar producto con logs de depuración
document.getElementById("formEditarProducto").addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append("action", "editar");

  // 👀 Verificar qué datos se están enviando
  console.log("📤 Datos enviados en editar:");
  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }

  try {
    const res = await fetch("../../api/ProductoService.php", {
      method: "POST",
      body: formData
    });

    // 👀 Verificar si la respuesta realmente es JSON
    const text = await res.text();
    console.log("📥 Respuesta cruda del servidor:", text);

    let data;
    try {
      data = JSON.parse(text);
    } catch (error) {
      console.error("❌ Error al parsear JSON:", error);
      return;
    }

    console.log("✅ Respuesta JSON parseada:", data);

    if (data.success) {
  Swal.fire("Éxito", "Producto actualizado correctamente", "success");
  cargarProductos().then(() => {
    const card = document.querySelector(`[data-id="${formData.get("IdProducto")}"]`).closest(".card");
    if (card) {
      card.classList.add("animate__animated", "animate__pulse");
    }
  });
  bootstrap.Modal.getInstance(document.getElementById("modalEditar")).hide();
}
  } catch (err) {
    console.error("❌ Error en fetch editar:", err);
    Swal.fire("Error", "Problema de conexión con el servidor", "error");
  }
});


// 🚀 Eliminar producto
function agregarEventosEliminar() {
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
      }).then(async result => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append("action", "eliminar");
          formData.append("IdProducto", id);

          const res = await fetch("../../api/ProductoService.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();

          if (data.success) {
            Swal.fire("Eliminado", "Producto eliminado exitosamente", "success");
            cargarProductos();
          } else {
            Swal.fire("Error", "No se pudo eliminar el producto", "error");
          }
        }
      });
    });
  });
}
// 🚀 Rellenar modal de editar
function agregarEventosEditar() {
  document.querySelectorAll(".btnEditar").forEach(btn => {
    btn.addEventListener("click", function () {
      document.getElementById("editIdProducto").value = this.dataset.id;
      document.getElementById("editNombre").value = this.dataset.nombre;
      document.getElementById("editPrecio").value = this.dataset.precio;
      document.getElementById("editStock").value = this.dataset.stock;
      document.getElementById("editDescripcion").value = this.dataset.descripcion || "";
      document.getElementById("editEstado").value = this.dataset.estado;
      document.getElementById("editImagenActual").value = this.dataset.imagen;

    });
  });
}

// 🚀 Inicializar
cargarProductos();
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
    width: 100%;
    object-fit: cover;
    cursor: pointer;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
  }
  .producto-carousel-img {
    height: 500px;
    object-fit: contain;
    background: #000;
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
