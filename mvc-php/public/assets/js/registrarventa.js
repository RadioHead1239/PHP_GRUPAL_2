// Funcionalidades para registrar ventas
let venta = [];
let clienteSeleccionado = null;

document.addEventListener('DOMContentLoaded', function() {
    inicializarEventos();
    renderTabla();
});

// Inicializar eventos
function inicializarEventos() {
    // Eventos de búsqueda de clientes
    const buscarClienteModal = document.getElementById('buscarClienteModal');
    if (buscarClienteModal) {
        buscarClienteModal.addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            if (termino.length >= 2) {
                buscarClientes(termino);
            }
        });
    }
    
    // Eventos de búsqueda de productos
    const buscarProductoModal = document.getElementById('buscarProductoModal');
    if (buscarProductoModal) {
        buscarProductoModal.addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            if (termino.length >= 2) {
                buscarProductos(termino);
            }
        });
    }
    
    // Eventos de métodos de pago
    const metodosPago = document.querySelectorAll('input[name="metodoPago"]');
    metodosPago.forEach(radio => {
        radio.addEventListener('change', function() {
            actualizarMetodoPago(this.value);
        });
    });
}

// Buscar clientes
async function buscarClientes(termino) {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/ClienteService.php?action=listar');
        const clientes = await response.json();
        
        const clientesFiltrados = clientes.filter(cliente => 
            cliente.Nombre.toLowerCase().includes(termino) ||
            (cliente.Correo && cliente.Correo.toLowerCase().includes(termino)) ||
            (cliente.Telefono && cliente.Telefono.includes(termino))
        );
        
        mostrarClientesEnModal(clientesFiltrados);
    } catch (error) {
        console.error('Error al buscar clientes:', error);
    }
}

// Mostrar clientes en modal
function mostrarClientesEnModal(clientes) {
    const tbody = document.getElementById('tablaClientesModal');
    if (!tbody) return;
    
    if (clientes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">No se encontraron clientes</td></tr>';
        return;
    }
    
    tbody.innerHTML = clientes.map(cliente => `
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
}

async function buscarProductos(termino) {
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/ProductoService.php?action=listar');
        const productos = await response.json();
        
        const productosFiltrados = productos.filter(producto => 
            producto.Nombre.toLowerCase().includes(termino) ||
            (producto.Descripcion && producto.Descripcion.toLowerCase().includes(termino)) ||
            producto.IdProducto.toString().includes(termino)
        );
        
        mostrarProductosEnModal(productosFiltrados);
    } catch (error) {
        console.error('Error al buscar productos:', error);
    }
}

// Mostrar productos en modal
function mostrarProductosEnModal(productos) {
    const container = document.getElementById('productosModal');
    if (!container) return;
    
    if (productos.length === 0) {
        container.innerHTML = '<div class="col-12 text-center text-muted">No se encontraron productos</div>';
        return;
    }
    
    container.innerHTML = productos.map(producto => {
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
}

// Seleccionar cliente
function seleccionarCliente(id, nombre, correo, telefono) {
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarCliente'));
    modal.hide();
    
    // Actualizar campos
    document.getElementById('clienteInput').value = nombre;
    document.getElementById('nombreCliente').textContent = nombre;
    document.getElementById('infoCliente').textContent = `${correo} | ${telefono}`;
    document.getElementById('clienteSeleccionado').style.display = 'block';
    
    // Guardar cliente seleccionado
    clienteSeleccionado = {
        id: id,
        nombre: nombre,
        correo: correo,
        telefono: telefono
    };
}

// Agregar producto a la venta
function agregarProducto(id, nombre, precio, stock) {
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarProducto'));
    modal.hide();
    
    // Verificar si el producto ya está en la venta
    const productoExistente = venta.find(p => p.id === id);
    if (productoExistente) {
        if (productoExistente.cantidad < stock) {
            productoExistente.cantidad += 1;
        } else {
            Swal.fire({
                title: 'Stock insuficiente',
                text: 'No hay suficiente stock disponible',
                icon: 'warning'
            });
            return;
        }
    } else {
        // Agregar nuevo producto
        venta.push({
            id: id,
            nombre: nombre,
            precio: precio,
            stock: stock,
            cantidad: 1,
            descuento: 0
        });
    }
    
    // Actualizar tabla
    renderTabla();
}

// Renderizar tabla de productos
function renderTabla() {
    const tbody = document.getElementById('tablaProductos');
    if (!tbody) return;
    
    if (venta.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No hay productos agregados</td></tr>';
        actualizarResumen();
        return;
    }
    
    tbody.innerHTML = venta.map((producto, index) => {
        const subtotal = producto.cantidad * producto.precio * (1 - producto.descuento / 100);
        
        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 35px; height: 35px;">
                            <i class="fa-solid fa-box text-white"></i>
                        </div>
                        <div>
                            <div class="fw-bold">${producto.nombre}</div>
                            <small class="text-muted">ID: ${producto.id}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width: 100px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${index}, -1)">-</button>
                        <input type="number" class="form-control text-center" value="${producto.cantidad}" 
                               min="1" max="${producto.stock}" onchange="cambiarCantidad(${index}, 0, this.value)">
                        <button class="btn btn-outline-secondary" type="button" onclick="cambiarCantidad(${index}, 1)">+</button>
                    </div>
                </td>
                <td class="text-end">S/ ${parseFloat(producto.precio).toFixed(2)}</td>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="width: 80px;">
                        <input type="number" class="form-control text-center" value="${producto.descuento}" 
                               min="0" max="100" onchange="cambiarDescuento(${index}, this.value)">
                        <span class="input-group-text">%</span>
                    </div>
                </td>
                <td class="text-end fw-bold">S/ ${subtotal.toFixed(2)}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${index})">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
    
    actualizarResumen();
}

// Cambiar cantidad de producto
function cambiarCantidad(index, delta, valor = null) {
    const producto = venta[index];
    let nuevaCantidad = valor ? parseInt(valor) : producto.cantidad + delta;
    
    if (nuevaCantidad < 1) nuevaCantidad = 1;
    if (nuevaCantidad > producto.stock) {
        Swal.fire({
            title: 'Stock insuficiente',
            text: `Solo hay ${producto.stock} unidades disponibles`,
            icon: 'warning'
        });
        nuevaCantidad = producto.stock;
    }
    
    producto.cantidad = nuevaCantidad;
    renderTabla();
}

// Cambiar descuento de producto
function cambiarDescuento(index, valor) {
    const descuento = Math.max(0, Math.min(100, parseFloat(valor) || 0));
    venta[index].descuento = descuento;
    renderTabla();
}

// Eliminar producto
function eliminarProducto(index) {
    venta.splice(index, 1);
    renderTabla();
}

// Actualizar resumen de venta
function actualizarResumen() {
    const totalProductos = venta.length;
    const totalUnidades = venta.reduce((sum, p) => sum + p.cantidad, 0);
    const subtotal = venta.reduce((sum, p) => {
        return sum + (p.cantidad * p.precio * (1 - p.descuento / 100));
    }, 0);
    
    const igv = subtotal * 0.18;
    const total = subtotal + igv;
    
    // Actualizar elementos del resumen
    const elementos = {
        'totalProductos': totalProductos,
        'totalUnidades': totalUnidades,
        'subtotal': subtotal.toFixed(2),
        'igv': igv.toFixed(2),
        'total': total.toFixed(2)
    };
    
    Object.keys(elementos).forEach(id => {
        const elemento = document.getElementById(id);
        if (elemento) {
            elemento.textContent = elementos[id];
        }
    });
}

// Actualizar método de pago
function actualizarMetodoPago(metodo) {
    console.log('Método de pago seleccionado:', metodo);
    // Aquí puedes agregar lógica específica para cada método de pago
}

// Guardar venta
async function guardarVenta() {
    if (venta.length === 0) {
        Swal.fire({
            title: 'Venta vacía',
            text: 'Agrega al menos un producto a la venta',
            icon: 'warning'
        });
        return;
    }
    
    const idUsuario = localStorage.getItem('usuarioId') || '1';
    const metodoPago = document.querySelector('input[name="metodoPago"]:checked')?.value || 'efectivo';
    
    const datosVenta = {
        idUsuario: parseInt(idUsuario),
        idCliente: clienteSeleccionado ? clienteSeleccionado.id : null,
        productos: venta.map(p => ({
            idProducto: p.id,
            cantidad: p.cantidad,
            precio: p.precio
        }))
    };
    
    try {
        const response = await fetch('/Proyecto_Grupal/PHP_GRUPAL_2/mvc-php/public/api/VentaService.php?action=crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datosVenta)
        });
        
        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                title: 'Venta registrada',
                text: `Venta #${result.idVenta} registrada exitosamente`,
                icon: 'success',
                timer: 3000
            });
            
            // Limpiar venta
            venta = [];
            clienteSeleccionado = null;
            document.getElementById('clienteInput').value = '';
            document.getElementById('clienteSeleccionado').style.display = 'none';
            renderTabla();
        } else {
            Swal.fire({
                title: 'Error',
                text: result.message || 'Error al registrar la venta',
                icon: 'error'
            });
        }
    } catch (error) {
        console.error('Error al guardar venta:', error);
        Swal.fire({
            title: 'Error',
            text: 'Error de conexión al registrar la venta',
            icon: 'error'
        });
    }
}

// Limpiar venta
function limpiarVenta() {
    Swal.fire({
        title: '¿Limpiar venta?',
        text: 'Se eliminarán todos los productos de la venta actual',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, limpiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            venta = [];
            clienteSeleccionado = null;
            document.getElementById('clienteInput').value = '';
            document.getElementById('clienteSeleccionado').style.display = 'none';
            renderTabla();
        }
    });
}

// Imprimir venta
function imprimirVenta() {
    if (venta.length === 0) {
        Swal.fire({
            title: 'Venta vacía',
            text: 'Agrega al menos un producto a la venta',
            icon: 'warning'
        });
        return;
    }
    
    // Implementar impresión
    console.log('Imprimiendo venta...');
    // Aquí podrías generar un PDF o abrir una ventana de impresión
}
