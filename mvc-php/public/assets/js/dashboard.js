// Funcionalidades del Dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Cargar métricas del dashboard según el rol
    const rol = localStorage.getItem('usuarioRol') || 'Vendedor';
    const idUsuario = localStorage.getItem('usuarioId') || '1';
    
    if (rol === 'Administrador') {
        cargarDashboardAdmin();
    } else {
        cargarDashboardVendedor(idUsuario);
    }
    
    // Cargar gráficos
    cargarGraficos();
});

// Función para cargar dashboard administrativo
async function cargarDashboardAdmin() {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/DashboardService.php?action=admin');
        const data = await response.json();
        
        // Actualizar métricas
        actualizarMetricasAdmin(data);
        
        // Actualizar gráficos
        actualizarGraficosAdmin(data);
        
    } catch (error) {
        console.error('Error al cargar dashboard admin:', error);
    }
}

// Función para cargar dashboard vendedor
async function cargarDashboardVendedor(idUsuario) {
    try {
        const response = await fetch(`/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/DashboardService.php?action=vendedor&idUsuario=${idUsuario}`);
        const data = await response.json();
        
        // Actualizar métricas
        actualizarMetricasVendedor(data);
        
    } catch (error) {
        console.error('Error al cargar dashboard vendedor:', error);
    }
}

// Actualizar métricas del administrador
function actualizarMetricasAdmin(data) {
    // Actualizar cards de métricas
    const cards = document.querySelectorAll('.dashboard-card');
    
    if (cards.length >= 4) {
        // Total de usuarios
        if (cards[0]) {
            const numero = cards[0].querySelector('.card-number');
            const descripcion = cards[0].querySelector('.card-description');
            if (numero) numero.textContent = data.totalUsuarios || 0;
            if (descripcion) descripcion.textContent = 'Usuarios Activos';
        }
        
        // Total de clientes
        if (cards[1]) {
            const numero = cards[1].querySelector('.card-number');
            const descripcion = cards[1].querySelector('.card-description');
            if (numero) numero.textContent = data.totalClientes || 0;
            if (descripcion) descripcion.textContent = 'Clientes Registrados';
        }
        
        // Total de productos
        if (cards[2]) {
            const numero = cards[2].querySelector('.card-number');
            const descripcion = cards[2].querySelector('.card-description');
            if (numero) numero.textContent = data.totalProductos || 0;
            if (descripcion) descripcion.textContent = 'Productos Activos';
        }
        
        // Productos con stock bajo
        if (cards[3]) {
            const numero = cards[3].querySelector('.card-number');
            const descripcion = cards[3].querySelector('.card-description');
            if (numero) numero.textContent = data.productosStockBajo || 0;
            if (descripcion) descripcion.textContent = 'Stock Bajo';
        }
    }
    
    // Actualizar ventas del día
    const ventasHoy = document.getElementById('ventasHoy');
    const ingresosHoy = document.getElementById('ingresosHoy');
    
    if (ventasHoy) ventasHoy.textContent = data.ventasHoy || 0;
    if (ingresosHoy) ingresosHoy.textContent = `S/ ${(data.ingresosHoy || 0).toFixed(2)}`;
    
    // Actualizar actividad reciente
    actualizarActividadReciente(data.actividadReciente || []);
}

// Actualizar métricas del vendedor
function actualizarMetricasVendedor(data) {
    // Actualizar cards de métricas
    const cards = document.querySelectorAll('.dashboard-card');
    
    if (cards.length >= 4) {
        // Ventas hoy
        if (cards[0]) {
            const numero = cards[0].querySelector('.card-number');
            const descripcion = cards[0].querySelector('.card-description');
            if (numero) numero.textContent = data.ventasHoy || 0;
            if (descripcion) descripcion.textContent = 'Ventas Hoy';
        }
        
        // Ingresos hoy
        if (cards[1]) {
            const numero = cards[1].querySelector('.card-number');
            const descripcion = cards[1].querySelector('.card-description');
            if (numero) numero.textContent = `S/ ${(data.ingresosHoy || 0).toFixed(2)}`;
            if (descripcion) descripcion.textContent = 'Ingresos Hoy';
        }
        
        // Clientes atendidos
        if (cards[2]) {
            const numero = cards[2].querySelector('.card-number');
            const descripcion = cards[2].querySelector('.card-description');
            if (numero) numero.textContent = data.clientesHoy || 0;
            if (descripcion) descripcion.textContent = 'Clientes Atendidos';
        }
        
        // Progreso de meta
        if (cards[3]) {
            const numero = cards[3].querySelector('.card-number');
            const descripcion = cards[3].querySelector('.card-description');
            if (numero) numero.textContent = `${Math.round(data.progresoMeta || 0)}%`;
            if (descripcion) descripcion.textContent = 'Meta Diaria';
        }
    }
}

// Actualizar actividad reciente
function actualizarActividadReciente(actividades) {
    const container = document.getElementById('actividadReciente');
    if (!container) return;
    
    if (actividades.length === 0) {
        container.innerHTML = '<p class="text-muted">No hay actividad reciente</p>';
        return;
    }
    
    container.innerHTML = actividades.map(actividad => `
        <div class="d-flex align-items-start mb-3">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                 style="width: 35px; height: 35px;">
                <i class="fa-solid fa-user text-white" style="font-size: 0.8rem;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="fw-bold">${actividad.UsuarioNombre}</div>
                <div class="text-muted small">${actividad.Accion}</div>
                <div class="text-muted" style="font-size: 0.75rem;">
                    ${new Date(actividad.Fecha).toLocaleString('es-PE')}
                </div>
            </div>
        </div>
    `).join('');
}

// Cargar gráficos
function cargarGraficos() {
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no está disponible');
        return;
    }
    
    // Gráfico de ventas por día
    const ctxVentas = document.getElementById('graficoVentas');
    if (ctxVentas) {
        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Ventas',
                    data: [12, 19, 3, 5, 2, 3, 8],
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    // Gráfico de productos más vendidos
    const ctxProductos = document.getElementById('graficoProductos');
    if (ctxProductos) {
        new Chart(ctxProductos, {
            type: 'doughnut',
            data: {
                labels: ['Laptop', 'Mouse', 'Teclado', 'Monitor', 'Otros'],
                datasets: [{
                    data: [30, 25, 20, 15, 10],
                    backgroundColor: [
                        'rgb(99, 102, 241)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)',
                        'rgb(107, 114, 128)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
}

// Función para actualizar gráficos con datos reales
function actualizarGraficosAdmin(data) {
    if (!data.ventasPorDia || !data.topProductos) return;
    
    // Actualizar gráfico de ventas por día
    const ctxVentas = document.getElementById('graficoVentas');
    if (ctxVentas && window.ventasChart) {
        const labels = data.ventasPorDia.map(item => 
            new Date(item.fecha).toLocaleDateString('es-PE', { weekday: 'short' })
        );
        const ventas = data.ventasPorDia.map(item => item.ventas);
        
        window.ventasChart.data.labels = labels;
        window.ventasChart.data.datasets[0].data = ventas;
        window.ventasChart.update();
    }
    
    // Actualizar gráfico de productos
    const ctxProductos = document.getElementById('graficoProductos');
    if (ctxProductos && window.productosChart) {
        const labels = data.topProductos.map(item => item.Nombre);
        const cantidades = data.topProductos.map(item => item.CantidadVendida);
        
        window.productosChart.data.labels = labels;
        window.productosChart.data.datasets[0].data = cantidades;
        window.productosChart.update();
    }
}

// Función para exportar datos
function exportarDatos(formato) {
    const rol = localStorage.getItem('usuarioRol') || 'Vendedor';
    
    if (formato === 'pdf') {
        // Implementar exportación a PDF
        console.log('Exportando a PDF...');
        // Aquí podrías usar jsPDF o similar
    } else if (formato === 'excel') {
        // Implementar exportación a Excel
        console.log('Exportando a Excel...');
        // Aquí podrías usar SheetJS o similar
    }
}

// Función para refrescar datos
function refrescarDashboard() {
    const rol = localStorage.getItem('usuarioRol') || 'Vendedor';
    const idUsuario = localStorage.getItem('usuarioId') || '1';
    
    if (rol === 'Administrador') {
        cargarDashboardAdmin();
    } else {
        cargarDashboardVendedor(idUsuario);
    }
    
    // Mostrar mensaje de actualización
    const mensaje = document.createElement('div');
    mensaje.className = 'alert alert-success alert-dismissible fade show position-fixed';
    mensaje.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    mensaje.innerHTML = `
        <i class="fa-solid fa-check-circle me-2"></i>
        Dashboard actualizado
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(mensaje);
    
    setTimeout(() => {
        mensaje.remove();
    }, 3000);
}
