// Funcionalidades para gestión de ventas
document.addEventListener('DOMContentLoaded', function() {

    // Solo búsqueda desde el calendario de "Hoy"
    cargarVentasPorDia(new Date().toISOString().slice(0, 10));
    inicializarEventos();

    // Evento para el calendario modal
    document.getElementById('btnCalendarioDia').addEventListener('click', function() {
        const picker = document.getElementById('calendarioDiaPicker');
        picker.value = picker.value || new Date().toISOString().slice(0, 10);
        const modal = new bootstrap.Modal(document.getElementById('modalCalendarioDia'));
        modal.show();
    });
    document.getElementById('btnAplicarCalendarioDia').addEventListener('click', function() {
        const fecha = document.getElementById('calendarioDiaPicker').value;
        if (fecha) {
            cargarVentasPorDia(fecha);
            cargarEstadisticasPorDia(fecha);
            bootstrap.Modal.getInstance(document.getElementById('modalCalendarioDia')).hide();
        }
    });
});

// Cargar ventas por día
async function cargarVentasPorDia(fecha) {
    try {
        const response = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=porfecha&fecha=${fecha}`);
        const ventas = await response.json();
        mostrarVentas(ventas);
    } catch (error) {
        console.error('Error al cargar ventas por día:', error);
        mostrarError('Error al cargar las ventas');
    }
}

// Cargar estadísticas por día
async function cargarEstadisticasPorDia(fecha) {
    try {
        const response = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=estadisticas&fechaInicio=${fecha}&fechaFin=${fecha}`);
        const stats = await response.json();
        document.getElementById('ventasHoy').textContent = parseInt(stats.ventasHoy) || 0;
        document.getElementById('ingresosHoy').textContent = `S/ ${(parseFloat(stats.ingresosHoy) || 0).toFixed(2)}`;
        document.getElementById('clientesHoy').textContent = parseInt(stats.clientesHoy) || 0;
        document.getElementById('productosVendidosHoy').textContent = parseInt(stats.productosVendidosHoy) || 0;
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
    }
}


// Cargar lista de ventas
async function cargarVentas() {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=listar');
        const ventas = await response.json();
        
        mostrarVentas(ventas);
    } catch (error) {
        console.error('Error al cargar ventas:', error);
        mostrarError('Error al cargar las ventas');
    }
}

// Mostrar ventas en la tabla
// Paginación de ventas
let ventasPaginadas = [];
let paginaActual = 1;
const ventasPorPagina = 10;

function mostrarVentas(ventas) {
    ventasPaginadas = ventas;
    paginaActual = 1;
    renderPaginaVentas();
}

function renderPaginaVentas() {
    const tbody = document.getElementById('tablaVentasBody');
    if (!tbody) return;
    const paginacion = document.getElementById('paginacionVentas');
    if (!ventasPaginadas || ventasPaginadas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center">No hay ventas registradas</td></tr>';
        if (paginacion) paginacion.innerHTML = '';
        return;
    }
    const totalPaginas = Math.ceil(ventasPaginadas.length / ventasPorPagina);
    const inicio = (paginaActual - 1) * ventasPorPagina;
    const fin = inicio + ventasPorPagina;
    const ventasPagina = ventasPaginadas.slice(inicio, fin);
    tbody.innerHTML = ventasPagina.map(venta => {
        const fecha = new Date(venta.FechaVenta).toLocaleDateString('es-PE', {
            year: 'numeric', month: '2-digit', day: '2-digit'
        });
        const hora = new Date(venta.FechaVenta).toLocaleTimeString('es-PE', {
            hour: '2-digit', minute: '2-digit'
        });
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-receipt text-white"></i>
                        </div>
                        <span class="fw-bold">#${venta.IdVenta.toString().padStart(3, '0')}</span>
                    </div>
                </td>
                <td>
                    <div>
                        <div class="fw-bold">${venta.ClienteNombre || 'Cliente General'}</div>
                        <small class="text-muted">${venta.ClienteCorreo || ''}</small>
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                            <i class="fa-solid fa-user text-white" style="font-size: 0.8rem;"></i>
                        </div>
                        <span>${venta.VendedorNombre}</span>
                    </div>
                </td>
                <td>
                    <div>
                        <div class="fw-bold">${fecha}</div>
                        <small class="text-muted">${hora}</small>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <div class="fw-bold text-primary">${venta.CantidadProductos}</div>
                        <small class="text-muted">productos</small>
                    </div>
                </td>
                <td>
                    <div class="fw-bold text-success">S/ ${parseFloat(venta.Total).toFixed(2)}</div>
                </td>
                <td>
                    <span class="badge bg-success">
                        <i class="fa-solid fa-check-circle me-1"></i>Completada
                    </span>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary ver-detalle" data-bs-toggle="modal" data-bs-target="#modalDetalleVenta" data-venta="${venta.IdVenta}" data-bs-toggle="tooltip" title="Ver detalles">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Imprimir">
                            <i class="fa-solid fa-print"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger eliminar-venta" data-id="${venta.IdVenta}" data-bs-toggle="tooltip" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    // Renderizar paginación
    if (paginacion) {
        let html = '';
        html += `<li class="page-item${paginaActual === 1 ? ' disabled' : ''}"><a class="page-link" href="#" data-page="prev">&laquo;</a></li>`;
        for (let i = 1; i <= totalPaginas; i++) {
            html += `<li class="page-item${paginaActual === i ? ' active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
        }
        html += `<li class="page-item${paginaActual === totalPaginas ? ' disabled' : ''}"><a class="page-link" href="#" data-page="next">&raquo;</a></li>`;
        paginacion.innerHTML = html;
        // Eventos de paginación
        paginacion.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                if (page === 'prev' && paginaActual > 1) {
                    paginaActual--;
                } else if (page === 'next' && paginaActual < totalPaginas) {
                    paginaActual++;
                } else if (!isNaN(parseInt(page))) {
                    paginaActual = parseInt(page);
                }
                renderPaginaVentas();
            });
        });
    }
}

// Inicializar eventos
function inicializarEventos() {
    // Evento para ver detalles de venta
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ver-detalle')) {
            const button = e.target.closest('.ver-detalle');
            const idVenta = button.dataset.venta;
            cargarDetalleVenta(idVenta);
        }
        
        if (e.target.closest('.eliminar-venta')) {
            const button = e.target.closest('.eliminar-venta');
            const idVenta = button.dataset.id;
            confirmarEliminarVenta(idVenta);
        }
    });
}

// Cargar detalles de una venta
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

// Actualizar modal de detalles
function actualizarModalDetalle(venta, detalles) {
    // Actualizar información de la venta
    document.getElementById('numeroVenta').textContent = venta.IdVenta;
    document.getElementById('clienteNombre').textContent = venta.ClienteNombre || 'Cliente General';
    document.getElementById('clienteCorreo').textContent = venta.ClienteCorreo || 'No especificado';
    document.getElementById('clienteTelefono').textContent = venta.ClienteTelefono || 'No especificado';
    document.getElementById('vendedorNombre').textContent = venta.VendedorNombre;
    document.getElementById('fechaVenta').textContent = new Date(venta.FechaVenta).toLocaleString('es-PE');
    document.getElementById('totalVenta').textContent = `S/ ${parseFloat(venta.Total).toFixed(2)}`;
    
    // Actualizar tabla de productos
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

// Confirmar eliminación de venta
function confirmarEliminarVenta(idVenta) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarVenta(idVenta);
        }
    });
}

// Eliminar venta
async function eliminarVenta(idVenta) {
    try {
        const response = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?id=${idVenta}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                title: 'Eliminada',
                text: 'La venta ha sido eliminada exitosamente',
                icon: 'success',
                timer: 2000
            });
            
            // Recargar la lista de ventas
            cargarVentas();
        } else {
            Swal.fire({
                title: 'Error',
                text: result.message || 'Error al eliminar la venta',
                icon: 'error'
            });
        }
    } catch (error) {
        console.error('Error al eliminar venta:', error);
        Swal.fire({
            title: 'Error',
            text: 'Error de conexión al eliminar la venta',
            icon: 'error'
        });
    }
}

// Mostrar error
function mostrarError(mensaje) {
    Swal.fire({
        title: 'Error',
        text: mensaje,
        icon: 'error',
        timer: 3000
    });
}

// Función para exportar ventas
function exportarVentas(formato) {
    if (formato === 'pdf') {
        // Implementar exportación a PDF
        console.log('Exportando ventas a PDF...');
        // Aquí podrías usar jsPDF
    } else if (formato === 'excel') {
        // Implementar exportación a Excel
        console.log('Exportando ventas a Excel...');
        // Aquí podrías usar SheetJS
    }
}

// Función para filtrar ventas
function filtrarVentas() {
    const fechaInicio = document.getElementById('fechaInicio')?.value;
    const fechaFin = document.getElementById('fechaFin')?.value;
    const vendedor = document.getElementById('vendedor')?.value;
    
    // Implementar filtros
    console.log('Filtrando ventas:', { fechaInicio, fechaFin, vendedor });
    
    // Por ahora recargar todas las ventas
    cargarVentas();
}
