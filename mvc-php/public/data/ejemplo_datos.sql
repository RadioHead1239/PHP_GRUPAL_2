CREATE DATABASE SistemaVentas;

USE SistemaVentas;
CALL sp_LeerClientes();

SELECT * FROM PRODUCTO


Select * from Venta

INSERT INTO cliente (IdCliente, Nombre, Correo, Telefono, Direccion, FechaRegistro)
VALUES
(1, 'Juan Pérez', 'juan.perez@email.com', '1234567890', 'Calle Falsa 123, Ciudad XYZ', '2023-09-18'),
(2, 'Maria González', 'maria.gonzalez@email.com', '0987654321', 'Avenida Libertad 456, Ciudad ABC', '2023-09-19'),
(3, 'Carlos López', 'carlos.lopez@email.com', '1122334455', 'Calle Real 789, Ciudad QRS', '2023-09-20');



CREATE TABLE Usuario (
    IdUsuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Correo VARCHAR(100) UNIQUE NOT NULL,
    ClaveHash VARCHAR(255) NOT NULL,
    Rol ENUM('Administrador', 'Vendedor') DEFAULT 'Vendedor',
    Estado BOOLEAN DEFAULT TRUE,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE Producto (
    IdProducto INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(150) NOT NULL,
    Imagen varchar (255) not null,
    Descripcion TEXT,
    Precio DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL DEFAULT 0,
    Estado BOOLEAN DEFAULT TRUE,
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



CREATE TABLE Cliente (
    IdCliente INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(150) NOT NULL,
    Correo VARCHAR(100),
    Telefono VARCHAR(20),
    Direccion VARCHAR(255),
    FechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE Venta (
    IdVenta INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT NOT NULL,
    IdCliente INT,
    Total DECIMAL(10,2) NOT NULL,
    FechaVenta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(IdUsuario),
    FOREIGN KEY (IdCliente) REFERENCES Cliente(IdCliente)
);


CREATE TABLE DetalleVenta (
    IdDetalle INT AUTO_INCREMENT PRIMARY KEY,
    IdVenta INT NOT NULL,
    IdProducto INT NOT NULL,
    Cantidad INT NOT NULL,
    PrecioUnitario DECIMAL(10,2) NOT NULL,
    Subtotal DECIMAL(10,2) GENERATED ALWAYS AS (Cantidad * PrecioUnitario) STORED,
    FOREIGN KEY (IdVenta) REFERENCES Venta(IdVenta),
    FOREIGN KEY (IdProducto) REFERENCES Producto(IdProducto)
);


CREATE TABLE LogAcceso (
    IdLog INT AUTO_INCREMENT PRIMARY KEY,
    IdUsuario INT NOT NULL,
    Accion VARCHAR(255),
    Fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdUsuario) REFERENCES Usuario(IdUsuario)
);


DELIMITER $$
CREATE PROCEDURE sp_LoginUsuario(IN u_Correo VARCHAR(100))
BEGIN
    SELECT IdUsuario, Nombre, Correo, ClaveHash, Rol, Estado, FechaRegistro
    FROM Usuario
    WHERE Correo = u_Correo AND Estado = TRUE
    LIMIT 1;
END$$
DELIMITER ;



-- CREAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_CrearUsuario(
    IN u_Nombre VARCHAR(100),
    IN u_Correo VARCHAR(100),
    IN u_ClaveHash VARCHAR(255),
    IN u_Rol ENUM('Administrador','Vendedor')
)
BEGIN
    INSERT INTO Usuario (Nombre, Correo, ClaveHash, Rol) VALUES (u_Nombre, u_Correo, u_ClaveHash, u_Rol);
END $$
DELIMITER ;

-- ejecutar 
CALL sp_CrearUsuario('Victor', 'Victor@correo.com', 'hash123', 'Administrador');
CALL sp_CrearUsuario('Juana Ramos', 'Juana@correo.com', 'hash234', 'Vendedor');

select * from Usuario
select * from Producto
select * from Venta
-- LEER USUARIOS 
DELIMITER $$
CREATE PROCEDURE sp_LeerUsuarios()
BEGIN
    SELECT * FROM Usuario;
END $$
DELIMITER ;

-- ejecutar 
CALL sp_LeerUsuarios();



-- LEER USUARIO POR ID
DELIMITER $$
CREATE PROCEDURE sp_LeerUsuarioPorId(
    IN u_IdUsuario INT
)
BEGIN
    SELECT * FROM Usuario WHERE IdUsuario = u_IdUsuario;
END $$
DELIMITER ;

-- ejecutar
CALL sp_LeerUsuarioPorId(1);



-- ACTUALIZAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_ActualizarUsuario(
	IN u_IdUsuario INT,
	IN u_Nombre VARCHAR(100),
    IN u_Correo VARCHAR(100),
    IN u_ClaveHash VARCHAR(255),
    IN u_Rol ENUM('Administrador','Vendedor'),
    IN u_Estado BOOLEAN
)
BEGIN
    UPDATE Usuario
    SET Nombre = u_Nombre,
        Correo = u_Correo,
        ClaveHash = u_ClaveHash,
        Rol = u_Rol,
        Estado = u_Estado
    WHERE IdUsuario = u_IdUsuario;
END $$
DELIMITER ;

-- ejecutar
CALL sp_ActualizarUsuario(1, 'Martin Gómez', 'Gomez@correo.com', 'hash456', 'Administrador', TRUE);




-- ELIMINAR USUARIO
DELIMITER $$
CREATE PROCEDURE sp_EliminarUsuario(
    IN u_IdUsuario INT
)
BEGIN
    DELETE FROM Usuario WHERE IdUsuario = u_IdUsuario;
END $$
DELIMITER ;


-- Ejecutar
CALL sp_EliminarUsuario(1);


DROP PROCEDURE IF EXISTS sp_CrearProducto;

DELIMITER $$
CREATE PROCEDURE sp_CrearProducto(
    IN p_Nombre VARCHAR(150),
    IN p_Imagen VARCHAR(255),
    IN p_Descripcion TEXT,
    IN p_Precio DECIMAL(10,2),
    IN p_Stock INT,
    IN p_Estado BOOLEAN
)
BEGIN
    INSERT INTO Producto (Nombre, Imagen, Descripcion, Precio, Stock, Estado)
    VALUES (p_Nombre, p_Imagen, p_Descripcion, p_Precio, p_Stock, p_Estado);
END $$
DELIMITER ;

SHOW CREATE PROCEDURE sp_CrearProducto;


-- LISTAR PRODUCTOS
DELIMITER $$
CREATE PROCEDURE sp_LeerProductos()
BEGIN
    SELECT * FROM Producto;
END $$
DELIMITER ;

-- LEER PRODUCTO POR ID
DELIMITER $$
CREATE PROCEDURE sp_LeerProductoPorId(
    IN p_IdProducto INT
)
BEGIN
    SELECT * FROM Producto WHERE IdProducto = p_IdProducto;
END $$
DELIMITER ;


-- ACTUALIZAR PRODUCTO
DELIMITER $$
CREATE PROCEDURE sp_ActualizarProducto(
    IN p_IdProducto INT,
    IN p_Nombre VARCHAR(150),
    IN p_Imagen VARCHAR(255),
    IN p_Descripcion TEXT,
    IN p_Precio DECIMAL(10,2),
    IN p_Stock INT,
    IN p_Estado BOOLEAN
)
BEGIN
    UPDATE Producto
    SET Nombre = p_Nombre,
        Imagen = p_Imagen,
        Descripcion = p_Descripcion,
        Precio = p_Precio,
        Stock = p_Stock,
        Estado = p_Estado
    WHERE IdProducto = p_IdProducto;
END $$
DELIMITER ;

-- ELIMINAR PRODUCTO
DELIMITER $$
CREATE PROCEDURE sp_EliminarProducto(
    IN p_IdProducto INT
)
BEGIN
    DELETE FROM Producto WHERE IdProducto = p_IdProducto;
END $$
DELIMITER ;

CALL sp_EliminarProducto(3);

INSERT INTO Producto (Nombre, Imagen, Descripcion, Precio, Stock, Estado)
VALUES 
('Pc Gaming plus', 'https://sercoplus.com/69700-large_default/pc-gaming-plus-ryzen-7-7800x3d-32gb-ddr5-ssd-1tb-rx-9070xt-16gb-f-850w-case-argb.jpg;https://sercoplus.com/69702-large_default/pc-gaming-plus-ryzen-7-7800x3d-32gb-ddr5-ssd-1tb-rx-9070xt-16gb-f-850w-case-argb.jpg', 'Ryzen 7 7800X3D, 32GB DDR5, SSD 1TB, RX 9070XT 16GB, F/850W, CASE ARGB', 7426.42, 3, 1)

INSERT INTO Producto (Nombre, Imagen, Descripcion, Precio, Stock, Estado)
VALUES 
('Laptop Gamer', 'https://sercoplus.com/69707-large_default/monitor-asus-tuf-gaming-vg27aql5a-27-f.jpg', 'Laptop potente con procesador i7, 16GB RAM y RTX 3060', 3500.00, 5, 1)


-- LISTAR CLIENTE
DELIMITER $$
CREATE PROCEDURE sp_LeerClientes()
BEGIN
    SELECT * FROM Cliente;
END $$
DELIMITER ;

-- ejecutar
CALL sp_LeerClientes();

-- LISTAR CLIENTE POR ID
DELIMITER $$
CREATE PROCEDURE sp_LeerClientePorId(
    IN c_IdCliente INT
)
BEGIN
    SELECT * FROM Cliente WHERE IdCliente = c_IdCliente;
END $$
DELIMITER ;

-- ejecutar
CALL sp_LeerClientePorId(2)




-- ACTUALIZAR CLIENTE
DELIMITER $$
CREATE PROCEDURE sp_ActualizarCliente(
    IN c_IdCliente INT,
    IN c_Nombre VARCHAR(150),
    IN c_Correo VARCHAR(100),
    IN c_Telefono VARCHAR(20),
    IN c_Direccion VARCHAR(255)
)
BEGIN
    UPDATE Cliente
    SET Nombre = c_Nombre,
        Correo = c_Correo,
        Telefono = c_Telefono,
        Direccion = c_Direccion
    WHERE IdCliente = c_IdCliente;
END $$
DELIMITER ;

-- ejecutar
CALL sp_ActualizarCliente(1, 'Aurora M', 'Aurora@gmail.com', '985214752', 'San Juan de Lurigancho');




-- ELIMINAR CLIENTE
DELIMITER $$
CREATE PROCEDURE sp_EliminarCliente(
    IN c_IdCliente INT
)
BEGIN
    DELETE FROM Cliente WHERE IdCliente = c_IdCliente;
END $$
DELIMITER ;

-- ejecutar
CALL sp_EliminarCliente(2);


DELIMITER $$
CREATE PROCEDURE sp_VentasPorCliente (
    IN p_IdCliente INT
)
BEGIN
    SELECT 
        c.IdCliente,
        c.Nombre AS NombreCliente,
        v.IdVenta,
        v.FechaVenta,
        v.Total,
        dv.IdDetalle,
        p.IdProducto,
        p.Nombre AS NombreProducto,
        dv.Cantidad,
        dv.PrecioUnitario,
        dv.Subtotal
    FROM Cliente c
    INNER JOIN Venta v ON c.IdCliente = v.IdCliente
    INNER JOIN DetalleVenta dv ON v.IdVenta = dv.IdVenta
    INNER JOIN Producto p ON dv.IdProducto = p.IdProducto
    WHERE c.IdCliente = p_IdCliente
    ORDER BY v.FechaVenta DESC, v.IdVenta, dv.IdDetalle;
END $$
DELIMITER ;

select * fro

-- ejecutar

SELECT * FROM CLiente

CALL sp_VentasPorCliente(1);

INSERT INTO DetalleVenta (IdVenta, IdProducto, Cantidad, PrecioUnitario)
VALUES (1, 2, 1, 3500.00), (1, 1, 1, 7426.42);