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

<div class="flex-grow-1 p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">
        <i class="fa-solid fa-box-open me-2"></i>Gestión de Productos
      </h1>
      <p class="text-muted mb-0">Administra el catálogo de productos</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Exportar productos">
        <i class="fa-solid fa-download me-1"></i>Exportar
      </button>
      <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Importar productos">
        <i class="fa-solid fa-upload me-1"></i>Importar
      </button>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="fa-solid fa-plus me-1"></i>Nuevo Producto
      </button>
    </div>
  </div>

  <!-- Filtros y búsqueda -->
  <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.1s;">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-search me-2"></i>Buscar Producto
          </label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="fa-solid fa-search"></i>
            </span>
            <input type="text" id="buscarProducto" class="form-control" placeholder="Nombre, descripción o SKU...">
          </div>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-filter me-2"></i>Categoría
          </label>
          <select class="form-select" id="filtroCategoria">
            <option value="">Todas</option>
            <option value="Electrónicos">Electrónicos</option>
            <option value="Accesorios">Accesorios</option>
            <option value="Software">Software</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-toggle-on me-2"></i>Estado
          </label>
          <select class="form-select" id="filtroEstado">
            <option value="">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold">
            <i class="fa-solid fa-sort me-2"></i>Ordenar por
          </label>
          <select class="form-select" id="ordenarPor">
            <option value="nombre">Nombre</option>
            <option value="precio">Precio</option>
            <option value="stock">Stock</option>
            <option value="fecha">Fecha</option>
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

    <!-- Estadísticas rápidas -->
  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary me-3">
            <i class="fa-solid fa-boxes"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Total Productos</h6>
            <h3 class="fw-bold mb-0" id="totalProductos">0</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.3s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-success me-3">
            <i class="fa-solid fa-check-circle"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Activos</h6>
            <h3 class="fw-bold mb-0" id="productosActivos">0</h3>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-warning me-3">
            <i class="fa-solid fa-exclamation-triangle"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Stock Bajo</h6>
            <h3 class="fw-bold mb-0" id="productosStockBajo">0</h3> <!-- 👈 corregido -->
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.5s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-info me-3">
            <i class="fa-solid fa-dollar-sign"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Valor Total</h6>
            <h3 class="fw-bold mb-0" id="valorInventario">S/ 0.00</h3> <!-- 👈 agregado -->
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Vista de productos -->
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
    <div class="modal-footer">
          <button class="btn btn btn-primary" data-bs-dismiss="modal">Guardar</button>
   </div>
</form>
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
      // Formatear fecha de registro
      const fechaRegistro = new Date(p.FechaRegistro).toLocaleDateString('es-PE', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      });

      // Manejar imagen (puede ser una sola imagen o múltiples separadas por ;)
      const imagenes = p.Imagen ? p.Imagen.split(";") : [];
      const primeraImg = imagenes[0] ? imagenes[0] : "../../assets/img/no-image.png";

      // Determinar estado del stock
      const stockBajo = p.Stock <= 5;
      const sinStock = p.Stock === 0;

      contenedor.innerHTML += `
        <div class="col-lg-4 col-md-6 animate-fadeInUp">
          <div class="card product-card shadow-custom h-100">
            <div class="position-relative">
              <img src="${primeraImg}" 
                   class="card-img-top producto-img" 
                   alt="${p.Nombre}"
                   style="height: 200px; object-fit: cover;"
                   data-bs-toggle="modal" 
                   data-bs-target="#modalImagenes${p.IdProducto}">
              
              <!-- Badge de estado -->
              <span class="badge estado-badge ${p.Estado ? 'bg-success' : 'bg-danger'}">
                <i class="fa-solid fa-${p.Estado ? 'check-circle' : 'times-circle'} me-1"></i>
                ${p.Estado ? 'Activo' : 'Inactivo'}
              </span>
              
              <!-- Badge de stock -->
              ${stockBajo ? `
                <span class="badge bg-warning position-absolute" style="top: 10px; left: 10px;">
                  <i class="fa-solid fa-exclamation-triangle me-1"></i>
                  ${sinStock ? 'Sin Stock' : 'Stock Bajo'}
                </span>
              ` : ''}
            </div>
            
            <div class="card-body">
              <h5 class="card-title fw-bold text-dark mb-2">${p.Nombre}</h5>
              <p class="text-muted small mb-3">
                ${p.Descripcion ? p.Descripcion.substring(0, 80) + (p.Descripcion.length > 80 ? '...' : '') : 'Sin descripción'}
              </p>
              
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <div class="text-center p-2 bg-light rounded">
                    <div class="fw-bold text-success">S/ ${parseFloat(p.Precio).toFixed(2)}</div>
                    <small class="text-muted">Precio</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="text-center p-2 bg-light rounded">
                    <div class="fw-bold text-primary">${p.Stock}</div>
                    <small class="text-muted">Stock</small>
                  </div>
                </div>
              </div>
              
              <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">
                  <i class="fa-solid fa-calendar me-1"></i>
                  ${fechaRegistro}
                </small>
                <small class="text-muted">ID: ${p.IdProducto}</small>
              </div>
            </div>
            
            <div class="card-footer bg-light border-0">
              <div class="d-grid gap-2">
                <div class="btn-group" role="group">
                  <button class="btn btn-outline-primary btn-sm btnEditar"
                          data-bs-toggle="modal" 
                          data-bs-target="#modalEditar"
                          data-id="${p.IdProducto}" 
                          data-nombre="${p.Nombre}" 
                          data-precio="${p.Precio}" 
                          data-stock="${p.Stock}" 
                          data-descripcion="${p.Descripcion || ''}" 
                          data-imagen="${p.Imagen}" 
                          data-estado="${p.Estado}">
                    <i class="fa-solid fa-pen me-1"></i>Editar
                  </button>
                  
                  <button class="btn btn-outline-danger btn-sm btnEliminar"
                          data-id="${p.IdProducto}" 
                          data-nombre="${p.Nombre}">
                    <i class="fa-solid fa-trash me-1"></i>Eliminar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal con Carousel para imágenes -->
        <div class="modal fade" id="modalImagenes${p.IdProducto}" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title fw-bold">
                  <i class="fa-solid fa-images me-2"></i>Imágenes de ${p.Nombre}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body p-0">
                <div id="carousel${p.IdProducto}" class="carousel slide" data-bs-ride="carousel">
                  <div class="carousel-inner">
                    ${imagenes.map((img, i) => `
                      <div class="carousel-item ${i === 0 ? 'active' : ''}">
                        <img src="${img}" 
                             class="d-block w-100" 
                             style="height: 400px; object-fit: contain;"
                             alt="Imagen ${i+1}">
                      </div>
                    `).join('')}
                  </div>
                  ${imagenes.length > 1 ? `
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  ` : ''}
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
  debugger;

  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append("action", "crear");

  // 👀 DEBUG: Ver qué se está enviando
  console.log("📤 Datos enviados al servidor (crear):");
  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }

  const res = await fetch("../../api/ProductoService.php", {
    method: "POST",
    body: formData
  });

  // 👀 DEBUG: Ver la respuesta cruda del servidor
  const raw = await res.text();
  console.log("📥 Respuesta cruda del servidor (crear):", raw);

  let data;
  try {
    data = JSON.parse(raw);
  } catch (err) {
    console.error("❌ Error parseando JSON:", err);
    return;
  }

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

// 🚀 Cargar estadísticas
async function cargarEstadisticas() {
  try {
    const response = await fetch('../../api/ProductoService.php?action=estadisticas');
    
    // 👀 Ver la respuesta cruda como texto antes de parsear
    const rawText = await response.text();
    console.log("📥 Respuesta cruda del servidor (estadísticas):", rawText);

    let stats;
    try {
      stats = JSON.parse(rawText);
      console.log("✅ Objeto JSON parseado (estadísticas):", stats);
    } catch (e) {
      console.error("❌ Error al parsear JSON de estadísticas:", e);
      return;
    }

    // Actualizar cards de estadísticas
    document.getElementById('totalProductos').textContent = stats.totalProductos || 0;
    document.getElementById('productosActivos').textContent = stats.productosActivos || 0;
    document.getElementById('productosStockBajo').textContent = stats.productosStockBajo || 0;
    document.getElementById('valorInventario').textContent = 
    `S/ ${Number(stats.valorTotalInventario || 0).toFixed(2)}`;

  } catch (error) {
    console.error('❌ Error al cargar estadísticas:', error);
  }
}


// 🚀 Inicializar
cargarProductos();
cargarEstadisticas();
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
  .modal-backdrop {
    display: none !important;
  }
</style>
