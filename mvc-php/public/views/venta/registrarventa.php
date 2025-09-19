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

<div class="container-fluid p-4">
  <h2 class="mb-4"><i class="fa-solid fa-cart-plus"></i> Registrar Venta</h2>

  <!-- Cliente -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-user"></i> Cliente</h5>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Seleccionar Cliente</label>
          <input type="text" class="form-control" placeholder="Buscar cliente..." id="clienteInput">
        </div>
        <div class="col-md-6">
          <label class="form-label">Fecha</label>
          <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- Productos -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title"><i class="fa-solid fa-box-open"></i> Productos</h5>

      <!-- Buscador de productos -->
      <div class="mb-3">
        <label class="form-label">Buscar Producto</label>
        <input type="text" id="productoSearch" class="form-control" placeholder="Escribe el nombre del producto...">
        <div id="productoList" class="list-group position-absolute w-50 shadow-sm" style="z-index:1000;"></div>
      </div>

      <!-- Tabla de productos -->
      <div class="table-responsive">
        <table class="table table-striped align-middle" id="tablaVenta">
          <thead class="table-dark">
            <tr>
              <th>Producto</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Descuento (%)</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr class="fw-bold">
              <td colspan="4" class="text-end">Total:</td>
              <td id="totalVenta">0.00</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Botones -->
      <div class="d-flex justify-content-end gap-2 mt-3">
        <button class="btn btn-secondary" id="btnLimpiar"><i class="fa-solid fa-eraser"></i> Limpiar</button>
        <button class="btn btn-success"><i class="fa-solid fa-save"></i> Guardar Venta</button>
        <button class="btn btn-danger"><i class="fa-solid fa-file-pdf"></i> Exportar PDF</button>
        <button class="btn btn-primary"><i class="fa-solid fa-file-excel"></i> Exportar Excel</button>
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

<?php include '../layout/footer.php'; ?>
