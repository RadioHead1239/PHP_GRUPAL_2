/**
 * Sistema de Ventas - Scripts Principales
 * Funcionalidades modernas y responsive
 */

// ===== INICIALIZACIÓN =====
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Inicializar tooltips
    initializeTooltips();
    
    // Inicializar animaciones
    initializeAnimations();
    
    // Inicializar funcionalidades responsive
    initializeResponsive();
    
    // Inicializar validaciones
    initializeValidations();
    
    // Inicializar efectos visuales
    initializeVisualEffects();
}

// ===== TOOLTIPS =====
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// ===== ANIMACIONES =====
function initializeAnimations() {
    // Animaciones de entrada escalonadas
    const animatedElements = document.querySelectorAll('.animate-fadeIn, .animate-fadeInUp, .animate-slideInRight');
    
    animatedElements.forEach((element, index) => {
        // Aplicar delay basado en el índice
        const delay = element.style.animationDelay || `${index * 0.1}s`;
        element.style.animationDelay = delay;
        
        // Mostrar elemento después del delay
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0) translateX(0)';
        }, parseFloat(delay) * 1000);
    });
}

// ===== RESPONSIVE =====
function initializeResponsive() {
    // Detectar tamaño de pantalla
    const mediaQueries = {
        xs: window.matchMedia('(max-width: 575px)'),
        sm: window.matchMedia('(min-width: 576px) and (max-width: 767px)'),
        md: window.matchMedia('(min-width: 768px) and (max-width: 991px)'),
        lg: window.matchMedia('(min-width: 992px) and (max-width: 1199px)'),
        xl: window.matchMedia('(min-width: 1200px)')
    };
    
    // Manejar cambios de tamaño
    Object.keys(mediaQueries).forEach(breakpoint => {
        mediaQueries[breakpoint].addListener(handleBreakpointChange);
    });
    
    // Aplicar configuración inicial
    handleBreakpointChange();
}

function handleBreakpointChange() {
    const isMobile = window.innerWidth <= 768;
    const isTablet = window.innerWidth > 768 && window.innerWidth <= 992;
    
    // Ajustar sidebar en móviles
    if (isMobile) {
        adjustSidebarForMobile();
    } else {
        adjustSidebarForDesktop();
    }
    
    // Ajustar tablas
    adjustTablesForScreen();
    
    // Ajustar modales
    adjustModalsForScreen();
}

function adjustSidebarForMobile() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.add('mobile-sidebar');
    }
}

function adjustSidebarForDesktop() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.remove('mobile-sidebar');
    }
}

function adjustTablesForScreen() {
    const tables = document.querySelectorAll('.table-responsive');
    tables.forEach(table => {
        if (window.innerWidth <= 768) {
            table.classList.add('table-sm');
        } else {
            table.classList.remove('table-sm');
        }
    });
}

function adjustModalsForScreen() {
    const modals = document.querySelectorAll('.modal-dialog');
    modals.forEach(modal => {
        if (window.innerWidth <= 576) {
            modal.classList.add('modal-fullscreen-sm-down');
        } else {
            modal.classList.remove('modal-fullscreen-sm-down');
        }
    });
}

// ===== VALIDACIONES =====
function initializeValidations() {
    // Validación de formularios
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Validación en tiempo real
    const inputs = document.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearValidation);
    });
}

function validateField(event) {
    const field = event.target;
    const isValid = field.checkValidity();
    
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
    }
}

function clearValidation(event) {
    const field = event.target;
    field.classList.remove('is-invalid', 'is-valid');
}

// ===== EFECTOS VISUALES =====
function initializeVisualEffects() {
    // Efecto de hover en cards
    const cards = document.querySelectorAll('.card, .dashboard-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Efecto de click en botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Efecto de loading en formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                showLoadingState(submitBtn);
            }
        });
    });
}

function showLoadingState(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';
    button.disabled = true;
    
    // Restaurar después de 3 segundos (simular procesamiento)
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    }, 3000);
}

// ===== FUNCIONES UTILITARIAS =====

// Formatear números como moneda
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-PE', {
        style: 'currency',
        currency: 'PEN'
    }).format(amount);
}

// Formatear fechas
function formatDate(date, options = {}) {
    const defaultOptions = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    };
    
    return new Intl.DateTimeFormat('es-PE', { ...defaultOptions, ...options }).format(new Date(date));
}

// Mostrar notificaciones
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remover después de la duración especificada
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, duration);
}

// Confirmar acciones
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Debounce para búsquedas
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ===== FUNCIONES ESPECÍFICAS DEL SISTEMA =====

// Búsqueda en tiempo real
function initializeSearch() {
    const searchInputs = document.querySelectorAll('input[type="search"], input[id*="buscar"], input[id*="search"]');
    
    searchInputs.forEach(input => {
        const debouncedSearch = debounce(function() {
            performSearch(input.value, input.dataset.target);
        }, 300);
        
        input.addEventListener('input', debouncedSearch);
    });
}

function performSearch(query, target) {
    if (!query.trim()) {
        showAllRows();
        return;
    }
    
    const rows = document.querySelectorAll(target || 'tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matches = text.includes(query.toLowerCase());
        row.style.display = matches ? '' : 'none';
    });
}

function showAllRows() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
}

// Filtros dinámicos
function initializeFilters() {
    const filterSelects = document.querySelectorAll('select[id*="filtro"]');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            applyFilters();
        });
    });
}

function applyFilters() {
    const filters = {};
    const filterSelects = document.querySelectorAll('select[id*="filtro"]');
    
    filterSelects.forEach(select => {
        if (select.value) {
            filters[select.id] = select.value;
        }
    });
    
    // Aplicar filtros a las filas
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        let showRow = true;
        
        Object.keys(filters).forEach(filterKey => {
            const filterValue = filters[filterKey];
            const cellValue = row.querySelector(`[data-${filterKey.replace('filtro', '').toLowerCase()}]`);
            
            if (cellValue && !cellValue.textContent.toLowerCase().includes(filterValue.toLowerCase())) {
                showRow = false;
            }
        });
        
        row.style.display = showRow ? '' : 'none';
    });
}

// ===== INICIALIZACIÓN FINAL =====
document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeFilters();
});

// ===== EXPORTAR FUNCIONES GLOBALES =====
window.SistemaVentas = {
    formatCurrency,
    formatDate,
    showNotification,
    confirmAction,
    debounce
};
