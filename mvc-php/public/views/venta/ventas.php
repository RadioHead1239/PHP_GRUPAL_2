<!-- Modal para seleccionar formato de impresión -->
<div class="modal fade" id="modalFormatoImpresion" tabindex="-1" aria-labelledby="modalFormatoImpresionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFormatoImpresionLabel">
          <i class="fa-solid fa-print me-2"></i>Seleccionar Formato de Impresión
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <button class="btn btn-primary m-2" id="btnImprimirTicket"><i class="fa-solid fa-receipt me-1"></i>Ticket</button>
        <button class="btn btn-outline-secondary m-2" id="btnImprimirA4"><i class="fa-solid fa-file-pdf me-1"></i>Hoja A4</button>
      </div>
    </div>
  </div>
</div>
<?php
session_start();

// Seguridad: acceso a usuarios autenticados
if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuario/login.php");
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
        <i class="fa-solid fa-receipt me-2"></i>Gestión de Ventas
      </h1>
      <p class="text-muted mb-0">Administra y consulta el historial de ventas</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-primary" id="btnCalendarioDia" data-bs-toggle="tooltip" title="Ventas del día">
        <i class="fa-solid fa-calendar-day me-1"></i>Hoy
      </button>
      <button class="btn btn-primary" onclick="window.location.href='registrarventa.php'">
        <i class="fa-solid fa-cart-plus me-1"></i>Nueva Venta
      </button>
    </div>
  </div>

  <div class="modal fade" id="modalCalendarioDia" tabindex="-1" aria-labelledby="modalCalendarioDiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCalendarioDiaLabel">
            <i class="fa-solid fa-calendar-day me-2"></i>Seleccionar Fecha para Ventas del Día
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <input type="date" id="calendarioDiaPicker" class="form-control form-control-lg mx-auto" style="max-width: 300px; font-size: 1.5rem;" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnAplicarCalendarioDia">
            <i class="fa-solid fa-check me-1"></i>Ver Ventas de la Fecha
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.1s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-success me-3">
            <i class="fa-solid fa-money-bill-wave"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Ingresos Hoy</h6>
            <h3 class="fw-bold mb-0" id="ingresosHoy">S/ 0.00</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>&nbsp;
            </small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-primary me-3">
            <i class="fa-solid fa-shopping-cart"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Transacciones</h6>
            <h3 class="fw-bold mb-0" id="ventasHoy">0</h3>
            <small class="text-info">
              <i class="fa-solid fa-arrow-right me-1"></i>&nbsp;
            </small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.3s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-warning me-3">
            <i class="fa-solid fa-users"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Clientes Atendidos</h6>
            <h3 class="fw-bold mb-0" id="clientesHoy">0</h3>
            <small class="text-success">
              <i class="fa-solid fa-arrow-up me-1"></i>&nbsp;
            </small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="dashboard-card animate-fadeInUp" style="animation-delay: 0.4s;">
        <div class="d-flex align-items-center">
          <div class="card-icon bg-info me-3">
            <i class="fa-solid fa-box"></i>
          </div>
          <div>
            <h6 class="text-muted mb-1">Productos Vendidos Hoy</h6>
            <h3 class="fw-bold mb-0" id="productosVendidosHoy">0</h3>
            <small class="text-info">
              <i class="fa-solid fa-arrow-right me-1"></i>&nbsp;
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-custom animate-fadeInUp" style="animation-delay: 0.6s;">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
          <i class="fa-solid fa-table me-2"></i>Listado de Ventas
        </h5>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-danger btn-sm" id="btnExportPDF">
            <i class="fa-solid fa-file-pdf me-1"></i>PDF
          </button>
          <button class="btn btn-outline-success btn-sm" id="btnExportExcel">
            <i class="fa-solid fa-file-excel me-1"></i>Excel
          </button>
        </div>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="tablaVentas">
          <thead>
            <tr>
              <th class="border-0">#</th>
              <th class="border-0">Cliente</th>
              <th class="border-0">Vendedor</th>
              <th class="border-0">Fecha</th>
              <th class="border-0">Productos</th>
              <th class="border-0">Total</th>
              <th class="border-0">Estado</th>
              <th class="border-0">Acciones</th>
            </tr>
          </thead>
          <tbody id="tablaVentasBody">
          </tbody>
        </table>
      </div>
      <nav class="mt-3">
        <ul class="pagination justify-content-center" id="paginacionVentas"></ul>
      </nav>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetalleVenta" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">
          <i class="fa-solid fa-receipt me-2"></i>Detalle de Venta #<span id="numeroVenta"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <h6 class="card-title text-primary">
                  <i class="fa-solid fa-user me-2"></i>Información del Cliente
                </h6>
                <p><strong>Nombre:</strong> <span id="clienteNombre"></span></p>
                <p><strong>Correo:</strong> <span id="clienteCorreo"></span></p>
                <p><strong>Teléfono:</strong> <span id="clienteTelefono"></span></p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 bg-light">
              <div class="card-body">
                <h6 class="card-title text-primary">
                  <i class="fa-solid fa-cash-register me-2"></i>Información de la Venta
                </h6>
                <p><strong>Vendedor:</strong> <span id="vendedorNombre"></span></p>
                <p><strong>Fecha:</strong> <span id="fechaVenta"></span></p>
                <p><strong>Total:</strong> <span class="fw-bold text-success" id="totalVenta"></span></p>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">
              <i class="fa-solid fa-box-open me-2"></i>Productos Vendidos
            </h6>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody id="detalleProductos">
                </tbody>
                <tfoot>
                  <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total:</td>
                    <td class="text-success" id="totalDetalle">S/ 0.00</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">
          <i class="fa-solid fa-print me-1"></i>Enviar Factura
        </button>
      </div>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Scripts de exportación -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Script de ventas -->
<script src="../../assets/js/ventas.js"></script>
<script>
let ventaSeleccionada = null;
let detallesVentaSeleccionada = null;
// Evento para imprimir desde la tabla
document.addEventListener('click', function(e) {
  if (e.target.closest('.btn-outline-info')) {
    const btn = e.target.closest('.btn-outline-info');
    const row = btn.closest('tr');
    const idVenta = row.querySelector('.ver-detalle').dataset.venta;
    obtenerVentaYMostrarModal(idVenta);
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const btns = document.querySelectorAll('.btn-imprimir-detalle');
  btns.forEach(btn => {
    btn.addEventListener('click', function() {
      const idVenta = document.getElementById('numeroVenta').textContent;
      obtenerVentaYMostrarModal(idVenta);
    });
  });
});

function obtenerVentaYMostrarModal(idVenta) {
  fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=obtener&id=${idVenta}`)
    .then(r => r.json())
    .then(venta => {
      fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=detalle&id=${idVenta}`)
        .then(r2 => r2.json())
        .then(detalles => {
          ventaSeleccionada = venta;
          detallesVentaSeleccionada = detalles;
          const modal = new bootstrap.Modal(document.getElementById('modalFormatoImpresion'));
          modal.show();
        });
    });
}

document.getElementById('btnImprimirTicket').addEventListener('click', function() {
  if (!ventaSeleccionada) return;
  window.open(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/pdf/ticket_pdf.php?id=${ventaSeleccionada.IdVenta || ventaSeleccionada.id || ventaSeleccionada.ID || ventaSeleccionada.id_venta}`, '_blank');
  bootstrap.Modal.getInstance(document.getElementById('modalFormatoImpresion')).hide();
});
document.getElementById('btnImprimirA4').addEventListener('click', function() {
  if (!ventaSeleccionada) return;
  window.open(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/pdf/a4_pdf.php?id=${ventaSeleccionada.IdVenta || ventaSeleccionada.id || ventaSeleccionada.ID || ventaSeleccionada.id_venta}`, '_blank');
  bootstrap.Modal.getInstance(document.getElementById('modalFormatoImpresion')).hide();
});


//este es el moda lpara enviar la venta
function actualizarModalDetalle(venta, detalles) {
    document.getElementById('numeroVenta').textContent = venta.IdVenta;
    document.getElementById('clienteNombre').textContent = venta.ClienteNombre || 'Cliente General';
    document.getElementById('clienteCorreo').textContent = venta.ClienteCorreo || 'No especificado';
    document.getElementById('clienteTelefono').textContent = venta.ClienteTelefono || 'No especificado';
    document.getElementById('vendedorNombre').textContent = venta.VendedorNombre;
    document.getElementById('fechaVenta').textContent = new Date(venta.FechaVenta).toLocaleString('es-PE');
    document.getElementById('totalVenta').textContent = `S/ ${parseFloat(venta.Total).toFixed(2)}`;
    
    const tbody = document.getElementById('detalleProductos');
    if (tbody) {
        tbody.innerHTML = detalles.map(detalle => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${detalle.ProductoImagen ? detalle.ProductoImagen.split(';')[0] : '../../assets/img/no-image.png'}" 
                             class="me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        <div>
                            <div class="fw-bold">${detalle.ProductoNombre}</div>
                        </div>
                    </div>
                </td>
                <td class="text-center">${detalle.Cantidad}</td>
                <td class="text-end">S/ ${parseFloat(detalle.PrecioUnitario).toFixed(2)}</td>
                <td class="text-end fw-bold">S/ ${parseFloat(detalle.Subtotal).toFixed(2)}</td>
            </tr>
        `).join('');
        
        // Actualizar total
        const total = detalles.reduce((sum, detalle) => sum + parseFloat(detalle.Subtotal), 0);
        document.getElementById('totalDetalle').textContent = `S/ ${total.toFixed(2)}`;
    }
}

async function cargarDetalleVenta(idVenta) {
    try {
        // Cargar información de la venta
        const responseVenta = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=obtener&id=${idVenta}`);
        const venta = await responseVenta.json();
        
        // Cargar detalles de productos
        const responseDetalle = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=detalle&id=${idVenta}`);
        const detalles = await responseDetalle.json();
        
        // Actualizar modal
        actualizarModalDetalle(venta, detalles);

        
    } catch (error) {
        console.error('Error al cargar detalles de venta:', error);
        mostrarError('Error al cargar los detalles de la venta');
    }
}

function numeroATexto(num) {
  const unidades = ['','uno','dos','tres','cuatro','cinco','seis','siete','ocho','nueve'];
  const decenas = ['','diez','veinte','treinta','cuarenta','cincuenta','sesenta','setenta','ochenta','noventa'];
  const centenas = ['','cien','doscientos','trescientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos'];
  let entero = Math.floor(num);
  let dec = Math.round((num - entero) * 100);
  let texto = '';
  if (entero === 0) texto = 'cero';
  else if (entero < 10) texto = unidades[entero];
  else if (entero < 100) texto = decenas[Math.floor(entero/10)] + (entero%10 ? ' y ' + unidades[entero%10] : '');
  else if (entero < 1000) texto = centenas[Math.floor(entero/100)] + (entero%100 ? ' ' + numeroATexto(entero%100) : '');
  else if (entero < 10000) texto = unidades[Math.floor(entero/1000)] + ' mil' + (entero%1000 ? ' ' + numeroATexto(entero%1000) : '');
  texto = texto.trim();
  if (dec > 0) texto += ` con ${dec}/100`;
  texto += ' soles';
  return texto;
}

function generarPDFA4(venta, detalles) {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  let y = 20;
  doc.setFontSize(18);
  doc.setTextColor(40, 40, 120);
  doc.text('C.C. PLAZA TEC - TIENDA 133', 105, y, { align: 'center' });
  doc.setFontSize(10);
  doc.setTextColor(80, 80, 80);
  doc.text('CENTRO CÍVICO', 105, y + 7, { align: 'center' });
  doc.text('Repuestos de componentes', 105, y + 13, { align: 'center' });
  y += 22;
  doc.setFontSize(12);
  doc.setTextColor(0, 0, 0);
  doc.text(`Comprobante de Venta #${venta.IdVenta}`, 20, y);
  doc.text(`Fecha: ${new Date(venta.FechaVenta).toLocaleString('es-PE')}`, 140, y);
  y += 8;
  doc.text(`Cliente: ${venta.ClienteNombre || 'PÚBLICO EN GENERAL'}`, 20, y);
  doc.text(`Vendedor: ${venta.VendedorNombre}`, 140, y);
  y += 10;
  const columns = [
    { header: 'Cant', dataKey: 'Cantidad' },
    { header: 'Producto', dataKey: 'ProductoNombre' },
    { header: 'P. Unit', dataKey: 'PrecioUnitario' },
    { header: 'Subtotal', dataKey: 'Subtotal' }
  ];
  const rows = detalles.map(det => ({
    Cantidad: det.Cantidad,
    ProductoNombre: det.ProductoNombre,
    PrecioUnitario: `S/ ${parseFloat(det.PrecioUnitario).toFixed(2)}`,
    Subtotal: `S/ ${parseFloat(det.Subtotal).toFixed(2)}`
  }));
  doc.autoTable({
    startY: y,
    head: [columns.map(col => col.header)],
    body: rows.map(row => columns.map(col => row[col.dataKey])),
    theme: 'grid',
    headStyles: { fillColor: [40, 40, 120], textColor: 255 },
    bodyStyles: { textColor: 50 },
    styles: { fontSize: 11 },
    margin: { left: 20, right: 20 }
  });
  y = doc.lastAutoTable.finalY + 10;
  doc.setFontSize(13);
  doc.setTextColor(40, 40, 120);
  doc.text(`TOTAL: S/ ${parseFloat(venta.Total).toFixed(2)}`, 20, y);
  y += 8;
  doc.setFontSize(10);
  doc.setTextColor(80, 80, 80);
  doc.text(`SON: ${numeroATexto(parseFloat(venta.Total)).toUpperCase()}`, 20, y);
  y += 10;
  doc.setFontSize(11);
  doc.setTextColor(80, 80, 80);
  doc.text('¡Gracias por su compra!', 105, y, { align: 'center' });
  doc.text('www.plazatec.com', 105, y + 7, { align: 'center' });
  doc.save(`venta_${venta.IdVenta}.pdf`);
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('btnCalendarioDia').addEventListener('click', function() {
    const picker = document.getElementById('calendarioDiaPicker');
    const filtro = document.getElementById('fechaInicio');
    if (filtro && filtro.value) picker.value = filtro.value;
    const modal = new bootstrap.Modal(document.getElementById('modalCalendarioDia'));
    modal.show();
  });

  document.getElementById('btnAplicarCalendarioDia').addEventListener('click', function() {
    const fecha = document.getElementById('calendarioDiaPicker').value;
    if (fecha) {
      document.getElementById('fechaInicio').value = fecha;
      document.getElementById('fechaFin').value = fecha;
      bootstrap.Modal.getInstance(document.getElementById('modalCalendarioDia')).hide();
      if (typeof actualizarPorRango === 'function') actualizarPorRango();
    }
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabla = document.getElementById("tablaVentas");

  document.getElementById("btnExportPDF").addEventListener("click", () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Reporte de Ventas", 14, 15);

    let data = [];
    tabla.querySelectorAll("tbody tr").forEach(row => {
      let rowData = [];
      row.querySelectorAll("td").forEach(cell => rowData.push(cell.innerText));
      data.push(rowData);
    });

    doc.autoTable({
      head: [["#", "Cliente", "Vendedor", "Fecha", "Productos", "Total", "Estado", "Acciones"]],
      body: data,
    });
    doc.save("ventas.pdf");
  });

  document.getElementById("btnExportExcel").addEventListener("click", () => {
    let wb = XLSX.utils.book_new();
    let ws = XLSX.utils.table_to_sheet(tabla);
    XLSX.utils.book_append_sheet(wb, ws, "Ventas");
    XLSX.writeFile(wb, "ventas.xlsx");
  });
});

async function cargarEstadisticas() {
  try {
    const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=estadisticas');
    const stats = await response.json();
    
  document.getElementById('ventasHoy').textContent = stats.ventasHoy || 0;
  document.getElementById('ingresosHoy').textContent = `S/ ${(stats.ingresosHoy || 0).toFixed(2)}`;
  document.getElementById('clientesHoy').textContent = stats.clientesHoy || 0;
  document.getElementById('productosVendidosHoy').textContent = stats.productosVendidosHoy || 0;
  } catch (error) {
    console.error('Error al cargar estadísticas:', error);
  }
}

cargarEstadisticas();
</script>
