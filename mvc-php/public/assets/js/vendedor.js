// Funcionalidades específicas para el vendedor
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Búsqueda de clientes
    const buscarClienteModal = document.getElementById('buscarClienteModal');
    const tablaClientesModal = document.getElementById('tablaClientesModal');
    
    if (buscarClienteModal) {
        buscarClienteModal.addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            if (termino.length >= 2) {
                buscarClientes(termino);
            } else {
                tablaClientesModal.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Ingresa al menos 2 caracteres para buscar</td></tr>';
            }
        });
    }

    // Búsqueda de productos
    const buscarProductoModal = document.getElementById('buscarProductoModal');
    const productosModal = document.getElementById('productosModal');
    
    if (buscarProductoModal) {
        buscarProductoModal.addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            if (termino.length >= 2) {
                buscarProductos(termino);
            } else {
                productosModal.innerHTML = '<div class="col-12 text-center text-muted">Ingresa al menos 2 caracteres para buscar</div>';
            }
        });
    }
});

// Función para buscar clientes
async function buscarClientes(termino) {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/ClienteService.php?action=listar');
        const clientes = await response.json();
        
        const clientesFiltrados = clientes.filter(cliente => 
            cliente.Nombre.toLowerCase().includes(termino) ||
            (cliente.Correo && cliente.Correo.toLowerCase().includes(termino)) ||
            (cliente.Telefono && cliente.Telefono.includes(termino))
        );

        const tablaClientesModal = document.getElementById('tablaClientesModal');
        
        if (clientesFiltrados.length === 0) {
            tablaClientesModal.innerHTML = '<tr><td colspan="3" class="text-center text-muted">No se encontraron clientes</td></tr>';
            return;
        }

        tablaClientesModal.innerHTML = clientesFiltrados.map(cliente => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 35px; height: 35px;">
                            <i class="fa-solid fa-user text-white"></i>
                        </div>
                        <div>
                            <div class="fw-bold">${cliente.Nombre}</div>
                            <small class="text-muted">ID: ${cliente.IdCliente}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div>${cliente.Correo || 'No especificado'}</div>
                        <small class="text-muted">${cliente.Telefono || 'No especificado'}</small>
                    </div>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="seleccionarCliente(${cliente.IdCliente}, '${cliente.Nombre}', '${cliente.Correo || ''}', '${cliente.Telefono || ''}')">
                        <i class="fa-solid fa-check me-1"></i>Seleccionar
                    </button>
                </td>
            </tr>
        `).join('');
    } catch (error) {
        console.error('Error al buscar clientes:', error);
        document.getElementById('tablaClientesModal').innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error al cargar clientes</td></tr>';
    }
}

// Función para buscar productos
async function buscarProductos(termino) {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/ProductoService.php?action=listar');
        const productos = await response.json();
        
        const productosFiltrados = productos.filter(producto => 
            producto.Nombre.toLowerCase().includes(termino) ||
            (producto.Descripcion && producto.Descripcion.toLowerCase().includes(termino)) ||
            producto.IdProducto.toString().includes(termino)
        );

        const productosModal = document.getElementById('productosModal');
        
        if (productosFiltrados.length === 0) {
            productosModal.innerHTML = '<div class="col-12 text-center text-muted">No se encontraron productos</div>';
            return;
        }

        productosModal.innerHTML = productosFiltrados.map(producto => {
            const imagenes = producto.Imagen ? producto.Imagen.split(";") : [];
            const primeraImg = imagenes[0] || '../../assets/img/no-image.png';
            
            return `
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <img src="${primeraImg}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${producto.Nombre}">
                        <div class="card-body">
                            <h6 class="card-title">${producto.Nombre}</h6>
                            <p class="card-text small text-muted">${producto.Descripcion ? producto.Descripcion.substring(0, 60) + '...' : 'Sin descripción'}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">S/ ${parseFloat(producto.Precio).toFixed(2)}</span>
                                <span class="badge bg-primary">Stock: ${producto.Stock}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-primary btn-sm w-100" onclick="agregarProducto(${producto.IdProducto}, '${producto.Nombre}', ${producto.Precio}, ${producto.Stock})">
                                <i class="fa-solid fa-plus me-1"></i>Agregar
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    } catch (error) {
        console.error('Error al buscar productos:', error);
        document.getElementById('productosModal').innerHTML = '<div class="col-12 text-center text-danger">Error al cargar productos</div>';
    }
}

// Función para seleccionar cliente
function seleccionarCliente(id, nombre, correo, telefono) {
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarCliente'));
    modal.hide();
    
    // Actualizar campos
    document.getElementById('clienteInput').value = nombre;
    document.getElementById('nombreCliente').textContent = nombre;
    document.getElementById('infoCliente').textContent = `${correo} | ${telefono}`;
    document.getElementById('clienteSeleccionado').style.display = 'block';
    
    // Guardar ID del cliente para la venta
    window.clienteSeleccionado = {
        id: id,
        nombre: nombre,
        correo: correo,
        telefono: telefono
    };
}

// Función para agregar producto a la venta
function agregarProducto(id, nombre, precio, stock) {
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarProducto'));
    modal.hide();
    
    // Verificar si el producto ya está en la venta
    const productoExistente = window.venta.find(p => p.id === id);
    if (productoExistente) {
        productoExistente.cantidad += 1;
    } else {
        // Agregar nuevo producto
        window.venta.push({
            id: id,
            nombre: nombre,
            precio: precio,
            stock: stock,
            cantidad: 1,
            descuento: 0
        });
    }
    
    // Actualizar tabla
    if (typeof renderTabla === 'function') {
        renderTabla();
    }
}

// Función para limpiar búsquedas
function limpiarBusquedas() {
    if (document.getElementById('buscarClienteModal')) {
        document.getElementById('buscarClienteModal').value = '';
    }
    if (document.getElementById('buscarProductoModal')) {
        document.getElementById('buscarProductoModal').value = '';
    }
}

// Limpiar búsquedas cuando se abren los modales
document.addEventListener('show.bs.modal', function(event) {
    if (event.target.id === 'modalBuscarCliente') {
        limpiarBusquedas();
        document.getElementById('tablaClientesModal').innerHTML = '<tr><td colspan="3" class="text-center text-muted">Ingresa al menos 2 caracteres para buscar</td></tr>';
    }
    if (event.target.id === 'modalBuscarProducto') {
        limpiarBusquedas();
        document.getElementById('productosModal').innerHTML = '<div class="col-12 text-center text-muted">Ingresa al menos 2 caracteres para buscar</div>';
    }
});