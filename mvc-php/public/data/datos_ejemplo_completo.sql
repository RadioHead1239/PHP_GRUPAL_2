-- Datos de ejemplo completos para el Sistema de Ventas
-- Incluye usuarios, clientes, productos y ventas de prueba

-- Insertar usuarios de ejemplo usando stored procedures
CALL sp_CrearUsuario('Victor Admin', 'victor@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');
CALL sp_CrearUsuario('Juana Vendedor', 'juana@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vendedor');
CALL sp_CrearUsuario('Carlos Vendedor', 'carlos@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Vendedor');

-- Insertar clientes de ejemplo
INSERT INTO Cliente (IdCliente, Nombre, Correo, Telefono, Direccion, FechaRegistro) VALUES
(1, 'Juan Pérez', 'juan.perez@email.com', '1234567890', 'Calle Falsa 123, Ciudad XYZ', '2023-09-18 14:30:00'),
(2, 'María González', 'maria.gonzalez@email.com', '0987654321', 'Avenida Libertad 456, Ciudad ABC', '2023-09-19 09:15:00'),
(3, 'Carlos López', 'carlos.lopez@email.com', '1122334455', 'Calle Real 789, Ciudad QRS', '2023-09-20 16:45:00'),
(4, 'Ana García', 'ana.garcia@email.com', '5566778899', 'Plaza Mayor 321, Ciudad DEF', '2023-09-21 11:20:00'),
(5, 'Luis Martínez', 'luis.martinez@email.com', '9988776655', 'Boulevard Central 654, Ciudad GHI', '2023-09-22 08:45:00'),
(6, 'Elena Rodríguez', 'elena.rodriguez@email.com', '4455667788', 'Calle Principal 987, Ciudad JKL', '2023-09-23 13:10:00'),
(7, 'Miguel Torres', 'miguel.torres@email.com', '3344556677', 'Avenida Norte 654, Ciudad MNO', '2023-09-24 10:30:00'),
(8, 'Sofia Herrera', 'sofia.herrera@email.com', '2233445566', 'Plaza Sur 321, Ciudad PQR', '2023-09-25 15:45:00');

-- Insertar productos de ejemplo usando stored procedures
CALL sp_CrearProducto('PC Gaming Plus', 'https://sercoplus.com/69700-large_default/pc-gaming-plus-ryzen-7-7800x3d-32gb-ddr5-ssd-1tb-rx-9070xt-16gb-f-850w-case-argb.jpg;https://sercoplus.com/69702-large_default/pc-gaming-plus-ryzen-7-7800x3d-32gb-ddr5-ssd-1tb-rx-9070xt-16gb-f-850w-case-argb.jpg', 'Ryzen 7 7800X3D, 32GB DDR5, SSD 1TB, RX 9070XT 16GB, F/850W, CASE ARGB', 7426.42, 3, 1);

CALL sp_CrearProducto('Laptop Gamer', 'https://sercoplus.com/69707-large_default/monitor-asus-tuf-gaming-vg27aql5a-27-f.jpg', 'Laptop potente con procesador i7, 16GB RAM y RTX 3060', 3500.00, 5, 1);

CALL sp_CrearProducto('Mouse Inalámbrico Logitech', 'mouse1.jpg;mouse2.jpg', 'Mouse inalámbrico ergonómico con sensor óptico de alta precisión y batería de larga duración', 45.00, 25, 1);

CALL sp_CrearProducto('Teclado Mecánico RGB', 'teclado1.jpg;teclado2.jpg', 'Teclado mecánico con switches Cherry MX, retroiluminación RGB y diseño gaming', 120.00, 15, 1);

CALL sp_CrearProducto('Monitor 24" Full HD', 'monitor1.jpg', 'Monitor LED de 24 pulgadas con resolución Full HD, tiempo de respuesta 1ms y conectividad HDMI/VGA', 180.00, 8, 1);

CALL sp_CrearProducto('Auriculares Gaming', 'auriculares1.jpg;auriculares2.jpg', 'Auriculares gaming con sonido surround 7.1, micrófono retráctil y control de volumen', 85.00, 20, 1);

CALL sp_CrearProducto('Webcam HD 1080p', 'webcam1.jpg', 'Cámara web HD con resolución 1080p, micrófono integrado y soporte ajustable', 65.00, 12, 1);

CALL sp_CrearProducto('Tablet Samsung Galaxy', 'tablet1.jpg;tablet2.jpg', 'Tablet Android de 10 pulgadas con pantalla táctil, 64GB de almacenamiento y cámara frontal', 350.00, 3, 1);

CALL sp_CrearProducto('Smartphone iPhone 14', 'iphone1.jpg;iphone2.jpg;iphone3.jpg', 'Smartphone Apple iPhone 14 con pantalla Super Retina XDR, cámara dual de 12MP y iOS 16', 1200.00, 2, 1);

CALL sp_CrearProducto('Impresora Multifuncional', 'impresora1.jpg', 'Impresora multifuncional con impresión, escaneo y copia, conectividad WiFi y tinta de alta duración', 150.00, 6, 1);

CALL sp_CrearProducto('Disco Duro Externo 1TB', 'disco1.jpg', 'Disco duro externo USB 3.0 de 1TB, compatible con PC y Mac, diseño compacto y resistente', 75.00, 18, 1);

CALL sp_CrearProducto('Cable HDMI 2.1', 'cable1.jpg', 'Cable HDMI 2.1 de alta velocidad, soporte para 4K@120Hz y 8K@60Hz, longitud 2 metros', 25.00, 50, 1);

-- Insertar ventas de ejemplo
INSERT INTO Venta (IdVenta, IdUsuario, IdCliente, Total, FechaVenta) VALUES
(1, 1, 1, 350.00, '2023-09-15 14:30:00'),
(2, 2, 2, 180.50, '2023-09-16 10:15:00'),
(3, 1, 3, 2500.00, '2023-09-17 16:45:00'),
(4, 2, 4, 205.00, '2023-09-18 09:30:00'),
(5, 1, 5, 1200.00, '2023-09-19 13:20:00'),
(6, 2, 6, 450.00, '2023-09-20 11:45:00'),
(7, 1, 7, 1800.00, '2023-09-21 15:30:00'),
(8, 2, 8, 95.00, '2023-09-22 08:15:00'),
(9, 1, 1, 500.00, '2023-09-23 12:00:00'),
(10, 2, 2, 300.00, '2023-09-24 14:30:00');

-- Insertar detalles de venta de ejemplo
INSERT INTO DetalleVenta (IdVenta, IdProducto, Cantidad, PrecioUnitario) VALUES
-- Venta 1: Juan Pérez
(1, 3, 2, 45.00),  -- 2 Mouse Inalámbricos
(1, 4, 1, 120.00), -- 1 Teclado Mecánico
(1, 6, 1, 85.00),  -- 1 Auriculares Gaming
(1, 7, 1, 65.00),  -- 1 Webcam HD

-- Venta 2: María González
(2, 5, 1, 180.00), -- 1 Monitor 24"
(2, 7, 1, 65.00),  -- 1 Webcam HD

-- Venta 3: Carlos López
(3, 2, 1, 3500.00), -- 1 Laptop Gamer (precio especial)

-- Venta 4: Ana García
(4, 3, 1, 45.00),   -- 1 Mouse Inalámbrico
(4, 4, 1, 120.00), -- 1 Teclado Mecánico
(4, 6, 1, 85.00),  -- 1 Auriculares Gaming

-- Venta 5: Luis Martínez
(5, 9, 1, 1200.00), -- 1 iPhone 14

-- Venta 6: Elena Rodríguez
(6, 1, 1, 7426.42), -- 1 PC Gaming Plus (precio especial)

-- Venta 7: Miguel Torres
(7, 2, 1, 3500.00), -- 1 Laptop Gamer
(7, 5, 1, 180.00),  -- 1 Monitor 24"
(7, 6, 1, 85.00),   -- 1 Auriculares Gaming

-- Venta 8: Sofia Herrera
(8, 3, 1, 45.00),   -- 1 Mouse Inalámbrico
(8, 12, 2, 25.00),  -- 2 Cables HDMI

-- Venta 9: Juan Pérez (segunda compra)
(9, 8, 1, 350.00),  -- 1 Tablet Samsung

-- Venta 10: María González (segunda compra)
(10, 4, 1, 120.00), -- 1 Teclado Mecánico
(10, 6, 1, 85.00),  -- 1 Auriculares Gaming
(10, 7, 1, 65.00),  -- 1 Webcam HD
(10, 12, 1, 25.00); -- 1 Cable HDMI

-- Insertar logs de acceso de ejemplo
INSERT INTO LogAcceso (IdUsuario, Accion, Fecha) VALUES
(1, 'Login exitoso', '2023-09-15 14:25:00'),
(1, 'Crear nueva venta', '2023-09-15 14:30:00'),
(2, 'Login exitoso', '2023-09-16 10:10:00'),
(2, 'Ver historial de ventas', '2023-09-16 10:12:00'),
(1, 'Login exitoso', '2023-09-17 16:40:00'),
(1, 'Actualizar producto', '2023-09-17 16:42:00'),
(2, 'Login exitoso', '2023-09-18 09:25:00'),
(1, 'Login exitoso', '2023-09-19 13:15:00'),
(1, 'Exportar reporte de ventas', '2023-09-19 13:18:00'),
(2, 'Login exitoso', '2023-09-20 08:30:00'),
(1, 'Login exitoso', '2023-09-21 15:25:00'),
(2, 'Login exitoso', '2023-09-22 08:10:00'),
(1, 'Login exitoso', '2023-09-23 11:55:00'),
(2, 'Login exitoso', '2023-09-24 14:25:00'),
(1, 'Login exitoso', '2023-09-25 09:00:00'),
(2, 'Ver estadísticas de ventas', '2023-09-25 09:05:00'),
(1, 'Actualizar stock de productos', '2023-09-25 09:10:00'),
(2, 'Crear nueva venta', '2023-09-25 09:15:00');

-- Verificar datos insertados
SELECT 'Usuarios:' as Tabla, COUNT(*) as Total FROM Usuario
UNION ALL
SELECT 'Clientes:', COUNT(*) FROM Cliente
UNION ALL
SELECT 'Productos:', COUNT(*) FROM Producto
UNION ALL
SELECT 'Ventas:', COUNT(*) FROM Venta
UNION ALL
SELECT 'Detalles Venta:', COUNT(*) FROM DetalleVenta
UNION ALL
SELECT 'Logs Acceso:', COUNT(*) FROM LogAcceso;
