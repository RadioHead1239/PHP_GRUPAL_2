console.log("✅ productos.js cargado correctamente");

// 🚀 Cargar productos dinámicamente
async function cargarProductos() {
  try {
    const res = await fetch("../../api/ProductoService.php?action=listar");
    const productos = await res.json();

    const contenedor = document.getElementById("listaProductos");
    contenedor.innerHTML = "";

    productos.forEach(p => {
      const fechaRegistro = new Date(p.FechaRegistro).toLocaleDateString("es-PE", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
      });

      const imagenes = p.Imagen ? p.Imagen.split(";") : [];
      const primeraImg = imagenes[0] ? imagenes[0] : "../../assets/img/no-image.png";

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
              
              <span class="badge estado-badge ${p.Estado ? "bg-success" : "bg-danger"}">
                <i class="fa-solid fa-${p.Estado ? "check-circle" : "times-circle"} me-1"></i>
                ${p.Estado ? "Activo" : "Inactivo"}
              </span>
              
              ${stockBajo ? `
                <span class="badge bg-warning position-absolute" style="top: 10px; left: 10px;">
                  <i class="fa-solid fa-exclamation-triangle me-1"></i>
                  ${sinStock ? "Sin Stock" : "Stock Bajo"}
                </span>
              ` : ""}
            </div>
            
            <div class="card-body">
              <h5 class="card-title fw-bold text-dark mb-2">${p.Nombre}</h5>
              <p class="text-muted small mb-3">
                ${p.Descripcion ? p.Descripcion.substring(0, 80) + (p.Descripcion.length > 80 ? "..." : "") : "Sin descripción"}
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
                          data-descripcion="${p.Descripcion || ""}" 
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
                      <div class="carousel-item ${i === 0 ? "active" : ""}">
                        <img src="${img}" 
                             class="d-block w-100" 
                             style="height: 400px; object-fit: contain;"
                             alt="Imagen ${i+1}">
                      </div>
                    `).join("")}
                  </div>
                  ${imagenes.length > 1 ? `
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel${p.IdProducto}" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  ` : ""}
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
    console.error("❌ Error al cargar productos:", err);
  }
}

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formCrearProducto");
    const lista = document.getElementById("listaProductos");
  
    async function cargarProductos() {
      const res = await fetch("../../api/ProductoService.php?action=listar");
      const data = await res.json();
  
      lista.innerHTML = "";
      data.forEach(p => {
        lista.innerHTML += `
          <div class="card p-2 mb-2">
            <strong>${p.Nombre}</strong> - S/ ${p.Precio} - Stock: ${p.Stock}
          </div>
        `;
      });
    }
  
    form.addEventListener("submit", async e => {
      e.preventDefault();
      const formData = new FormData(form);
      formData.append("action", "crear");
  
      console.log("📤 Enviando:", [...formData.entries()]);
  
      const res = await fetch("../../api/ProductoService.php", {
        method: "POST",
        body: formData
      });
  
      const raw = await res.text();
      console.log("📥 Respuesta cruda:", raw);
  
      try {
        const data = JSON.parse(raw);
        if (data.success) {
          alert("✅ Producto creado");
          form.reset();
          bootstrap.Modal.getInstance(document.getElementById("modalCrear")).hide();
          cargarProductos();
        } else {
          alert("❌ Error al crear producto");
        }
      } catch (err) {
        console.error("❌ Error parseando JSON:", err);
      }
    });
  
    cargarProductos();
  });
  
  
// 🚀 Editar producto
document.getElementById("formEditarProducto").addEventListener("submit", async e => {
  e.preventDefault();
  const formData = new FormData(e.target);
  formData.append("action", "editar");

  console.log("📤 Datos enviados en editar:");
  for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }

  try {
    const res = await fetch("../../api/ProductoService.php", {
      method: "POST",
      body: formData,
    });

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
        cancelButtonColor: "#3085d6",
      }).then(async result => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append("action", "eliminar");
          formData.append("IdProducto", id);

          const res = await fetch("../../api/ProductoService.php", {
            method: "POST",
            body: formData,
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
    const response = await fetch("../../api/ProductoService.php?action=estadisticas");

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

    document.getElementById("totalProductos").textContent = stats.totalProductos || 0;
    document.getElementById("productosActivos").textContent = stats.productosActivos || 0;
    document.getElementById("productosStockBajo").textContent = stats.productosStockBajo || 0;
    document.getElementById("valorInventario").textContent =
      `S/ ${Number(stats.valorTotalInventario || 0).toFixed(2)}`;
  } catch (error) {
    console.error("❌ Error al cargar estadísticas:", error);
  }
}

// 🚀 Inicializar
cargarProductos();
cargarEstadisticas();
