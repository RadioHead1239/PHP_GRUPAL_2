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

<!-- Contenido principal -->
<div class="p-4 flex-grow-1">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-receipt"></i> Gestión de Ventas</h2>
    <button class="btn btn-primary">
      <i class="fa-solid fa-cart-plus"></i> Nueva Venta
    </button>
  </div>

  <!-- Filtros de Reporte -->
  <div class="card shadow-sm p-3 mb-4">
    <h5><i class="fa-solid fa-filter"></i> Filtros de Reporte</h5>
    <form id="filtroForm" class="row g-3 mt-2">
      <div class="col-md-4">
        <label for="fechaInicio" class="form-label">Fecha Inicio</label>
        <input type="date" id="fechaInicio" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="fechaFin" class="form-label">Fecha Fin</label>
        <input type="date" id="fechaFin" class="form-control">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="button" id="btnFiltrar" class="btn btn-success w-100">
          <i class="fa-solid fa-magnifying-glass"></i> Generar Reporte
        </button>
      </div>
    </form>
  </div>

  <!-- Tabla de Ventas -->
  <div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="fa-solid fa-table"></i> Listado de Ventas</h5>
      <div>
        <button class="btn btn-outline-danger btn-sm" id="btnExportPDF">
          <i class="fa-solid fa-file-pdf"></i> Exportar PDF
        </button>
        <button class="btn btn-outline-success btn-sm" id="btnExportExcel">
          <i class="fa-solid fa-file-excel"></i> Exportar Excel
        </button>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover align-middle" id="tablaVentas">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Usuario</th>
            <th>Fecha</th>
            <th>Total (S/)</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- Ejemplo de registros -->
          <tr>
            <td>1</td>
            <td>Juan Pérez</td>
            <td>Victor</td>
            <td>2025-09-15</td>
            <td>350.00</td>
            <td>
              <button class="btn btn-sm btn-info ver-pdf">
                <i class="fa-solid fa-file-pdf"></i> Ver PDF
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Ana Gómez</td>
            <td>Juana</td>
            <td>2025-09-16</td>
            <td>180.50</td>
            <td>
              <button class="btn btn-sm btn-info ver-pdf">
                <i class="fa-solid fa-file-pdf"></i> Ver PDF
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include '../layout/footer.php'; ?>

<!-- Scripts de exportación -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const tabla = document.getElementById("tablaVentas");

  // 👉 Exportar a PDF
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

    // Generar tabla en PDF
    doc.autoTable({
      head: [["#", "Cliente", "Usuario", "Fecha", "Total", "Acciones"]],
      body: data,
    });
    doc.save("ventas.pdf");
  });

  // 👉 Exportar a Excel
  document.getElementById("btnExportExcel").addEventListener("click", () => {
    let wb = XLSX.utils.book_new();
    let ws = XLSX.utils.table_to_sheet(tabla);
    XLSX.utils.book_append_sheet(wb, ws, "Ventas");
    XLSX.writeFile(wb, "ventas.xlsx");
  });

  // 👉 Generar Reporte por Fecha
  document.getElementById("btnFiltrar").addEventListener("click", () => {
    const inicio = document.getElementById("fechaInicio").value;
    const fin = document.getElementById("fechaFin").value;

    if (!inicio || !fin) {
      alert("⚠️ Selecciona ambas fechas.");
      return;
    }

    alert(`Generar reporte desde ${inicio} hasta ${fin} (aquí conectarás con PHP y SP).`);
  });

  // 👉 Simular "Ver PDF" de una venta
  document.querySelectorAll(".ver-pdf").forEach(btn => {
    btn.addEventListener("click", () => {
      alert("Aquí se abriría el PDF de la venta seleccionada.");
    });
  });
});
</script>
