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

<div class="flex-grow-1 p-4">
  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center mb-4 animate-fadeIn">
    <div>
      <h1 class="h2 fw-bold text-gradient mb-1">
        <i class="fa-solid fa-cart-plus me-2"></i>Registrar Nueva Venta
      </h1>
      <p class="text-muted mb-0">Crea una nueva venta de manera rápida y eficiente</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Guardar borrador">
        <i class="fa-solid fa-save me-1"></i>Borrador
      </button>
      <button class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Ventas recientes">
        <i class="fa-solid fa-history me-1"></i>Recientes
      </button>
    </div>
  </div>

  <div class="row g-4">
    <!-- Panel izquierdo - Cliente y productos -->
    <div class="col-lg-8">
      <!-- Información del Cliente -->
      <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-user me-2"></i>Información del Cliente
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-search me-2"></i>Buscar Cliente
              </label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa-solid fa-user"></i>
                </span>
                <input type="text" class="form-control" placeholder="Nombre, correo o teléfono del cliente..." id="clienteInput">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalBuscarCliente">
                  <i class="fa-solid fa-search"></i>
                </button>
              </div>
              <div id="clienteSeleccionado" class="mt-2" style="display: none;">
                <div class="alert alert-success d-flex align-items-center">
                  <i class="fa-solid fa-check-circle me-2"></i>
                  <div>
                    <strong id="nombreCliente"></strong><br>
                    <small id="infoCliente"></small>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-calendar me-2"></i>Fecha de Venta
              </label>
              <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" id="fechaVenta">
            </div>
          </div>
        </div>
      </div>

      <!-- Búsqueda de Productos -->
      <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-box-open me-2"></i>Agregar Productos
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-search me-2"></i>Buscar Producto
              </label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="fa-solid fa-box"></i>
                </span>
                <input type="text" id="productoSearch" class="form-control" placeholder="Nombre, código o descripción del producto...">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
                  <i class="fa-solid fa-search"></i>
                </button>
              </div>
              <div id="productoList" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; display: none;"></div>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">
                <i class="fa-solid fa-barcode me-2"></i>Escáner de Código
              </label>
              <button class="btn btn-outline-success w-100" data-bs-toggle="tooltip" title="Escanear código de barras">
                <i class="fa-solid fa-qrcode me-1"></i>Escanear
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Lista de Productos -->
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.3s;">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fa-solid fa-list me-2"></i>Productos Seleccionados
            </h5>
            <span class="badge bg-primary" id="contadorProductos">0 productos</span>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaVenta">
              <thead>
                <tr>
                  <th class="border-0">Producto</th>
                  <th class="border-0">Precio</th>
                  <th class="border-0">Cantidad</th>
                  <th class="border-0">Descuento</th>
                  <th class="border-0">Subtotal</th>
                  <th class="border-0">Acciones</th>
                </tr>
              </thead>
              <tbody id="tbodyProductos">
                <tr id="filaVacia">
                  <td colspan="6" class="text-center text-muted py-5">
                    <i class="fa-solid fa-shopping-cart fa-3x mb-3 d-block"></i>
                    <p class="mb-0">No hay productos agregados</p>
                    <small>Busca y agrega productos para comenzar la venta</small>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel derecho - Resumen y totales -->
    <div class="col-lg-4">
      <!-- Resumen de Venta -->
      <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-calculator me-2"></i>Resumen de Venta
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3 mb-3">
            <div class="col-6">
              <div class="text-center p-3 bg-light rounded">
                <h4 class="fw-bold text-primary mb-1" id="totalProductos">0</h4>
                <small class="text-muted">Productos</small>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center p-3 bg-light rounded">
                <h4 class="fw-bold text-success mb-1" id="totalCantidad">0</h4>
                <small class="text-muted">Unidades</small>
              </div>
            </div>
          </div>
          
          <hr>
          
          <div class="d-flex justify-content-between mb-2">
            <span>Subtotal:</span>
            <span id="subtotalVenta">S/ 0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Descuento:</span>
            <span class="text-success" id="descuentoVenta">-S/ 0.00</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>IGV (18%):</span>
            <span id="igvVenta">S/ 0.00</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between">
            <h5 class="fw-bold mb-0">Total:</h5>
            <h4 class="fw-bold text-success mb-0" id="totalVenta">S/ 0.00</h4>
          </div>
        </div>
      </div>

      <!-- Métodos de Pago -->
      <div class="card shadow-custom mb-4 animate-fadeInUp" style="animation-delay: 0.5s;">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="fa-solid fa-credit-card me-2"></i>Método de Pago
          </h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <button class="btn btn-outline-primary" data-metodo="efectivo">
              <i class="fa-solid fa-money-bill me-2"></i>Efectivo
            </button>
            <button class="btn btn-outline-success" data-metodo="tarjeta">
              <i class="fa-solid fa-credit-card me-2"></i>Tarjeta
            </button>
            <button class="btn btn-outline-info" data-metodo="transferencia">
              <i class="fa-solid fa-university me-2"></i>Transferencia
            </button>
            <button class="btn btn-outline-warning" data-metodo="mixto">
              <i class="fa-solid fa-hand-holding-usd me-2"></i>Mixto
            </button>
          </div>
        </div>
      </div>

      <!-- Acciones -->
      <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.6s;">
        <div class="card-body">
          <div class="d-grid gap-2">
            <button class="btn btn-success btn-lg" id="btnGuardarVenta">
              <i class="fa-solid fa-save me-2"></i>Guardar Venta
            </button>
            <button class="btn btn-primary" id="btnImprimir">
              <i class="fa-solid fa-print me-2"></i>Imprimir
            </button>
            <button class="btn btn-outline-danger" id="btnLimpiar">
              <i class="fa-solid fa-eraser me-2"></i>Limpiar Todo
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Galería -->
<div class="modal fade" id="modalImagenes" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Galería de Imágenes</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="carouselImagenes" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner" id="carouselItems"></div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselImagenes" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselImagenes" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Datos simulados de productos (luego conectar a BD)
const productos = [
  {id:1, nombre:"Laptop HP", precio:2500, imagenes:[
    "../../assets/img/laptop1.png",
    "../../assets/img/laptop2.png",
    "../../assets/img/laptop3.png"
  ]},
  {id:2, nombre:"Mouse Logitech", precio:80, imagenes:[
    "../../assets/img/mouse1.png",
    "../../assets/img/mouse2.png"
  ]},
  {id:3, nombre:"Teclado Mecánico", precio:150, imagenes:[
    "../../assets/img/teclado.png"
  ]},
];


const input = document.getElementById("productoSearch");
const lista = document.getElementById("productoList");
const tabla = document.querySelector("#tablaVenta tbody");
const totalVenta = document.getElementById("totalVenta");

let venta = [];

// Buscar productos con imagen
input.addEventListener("input", function() {
  const query = this.value.toLowerCase();
  lista.innerHTML = "";
  if (query.length > 0) {
    productos
      .filter(p => p.nombre.toLowerCase().includes(query))
      .forEach(p => {
        const item = document.createElement("div");
        item.className = "list-group-item list-group-item-action d-flex align-items-center";
        item.style.cursor = "pointer";
        item.innerHTML = `
          <img src="${p.imagen}" class="me-2 rounded" style="width:40px; height:40px; object-fit:cover;">
          <div>
            <div class="fw-bold">${p.nombre}</div>
            <small class="text-muted">S/ ${p.precio}</small>
          </div>
        `;
        item.onclick = () => agregarProducto(p);
        lista.appendChild(item);
      });
  }
});

// Agregar producto
function agregarProducto(p) {
  lista.innerHTML = "";
  input.value = "";

  const existe = venta.find(v => v.id === p.id);
  if (existe) {
    existe.cantidad++;
  } else {
    venta.push({...p, cantidad:1, descuento:0});
  }
  renderTabla();
}

// Render tabla
function renderTabla() {
  tabla.innerHTML = "";
  let total = 0;

  venta.forEach((p, i) => {
    const subtotal = (p.precio * p.cantidad) * (1 - p.descuento/100);
    total += subtotal;

    const row = `
      <tr>
        <td>
          <img src="${p.imagenes[0]}" 
               style="width:40px; height:40px; object-fit:cover; cursor:pointer;" 
               class="me-2 rounded"
               onclick="mostrarImagenes(${i})">
          ${p.nombre}
        </td>
        <td>S/ ${p.precio.toFixed(2)}</td>
        <td><input type="number" min="1" value="${p.cantidad}" class="form-control form-control-sm" onchange="cambiarCantidad(${i}, this.value)"></td>
        <td><input type="number" min="0" max="100" value="${p.descuento}" class="form-control form-control-sm" onchange="cambiarDescuento(${i}, this.value)"></td>
        <td>S/ ${subtotal.toFixed(2)}</td>
        <td><button class="btn btn-sm btn-danger" onclick="eliminarProducto(${i})"><i class="fa fa-trash"></i></button></td>
      </tr>`;
    tabla.insertAdjacentHTML("beforeend", row);
  });

  totalVenta.textContent = "S/ " + total.toFixed(2);
}
function mostrarImagenes(index) {
  const producto = venta[index];
  const carouselItems = document.getElementById("carouselItems");
  carouselItems.innerHTML = "";

  producto.imagenes.forEach((img, i) => {
    carouselItems.innerHTML += `
      <div class="carousel-item ${i === 0 ? 'active' : ''}">
        <img src="${img}" class="d-block w-100" style="max-height:500px; object-fit:contain;">
      </div>
    `;
  });

  const modal = new bootstrap.Modal(document.getElementById("modalImagenes"));
  modal.show();
}

// Funciones auxiliares
function cambiarCantidad(i, value) {
  venta[i].cantidad = parseInt(value) || 1;
  renderTabla();
}
function cambiarDescuento(i, value) {
  venta[i].descuento = parseInt(value) || 0;
  renderTabla();
}
function eliminarProducto(i) {
  venta.splice(i,1);
  renderTabla();
}
</script>

<!-- Modal Buscar Cliente -->
<div class="modal fade" id="modalBuscarCliente" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">
          <i class="fa-solid fa-user-group me-2"></i>Buscar Cliente
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="text" class="form-control" id="buscarClienteModal" placeholder="Buscar por nombre, correo o teléfono...">
        </div>
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Contacto</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody id="tablaClientesModal">
              <!-- Se llenará dinámicamente -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Buscar Producto -->
<div class="modal fade" id="modalBuscarProducto" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">
          <i class="fa-solid fa-box-open me-2"></i>Buscar Producto
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="text" class="form-control" id="buscarProductoModal" placeholder="Buscar por nombre, descripción o código...">
        </div>
        <div class="row" id="productosModal">
          <!-- Se llenará dinámicamente -->
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>
