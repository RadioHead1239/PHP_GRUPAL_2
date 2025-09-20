<?php
require_once __DIR__ . '/../config/database.php';

class VentaDAO {
    // Reporte de ventas por rango de fechas
    public function leerVentasPorRangoFechas($fechaInicio, $fechaFin) {
        try {
            $sql = "
                SELECT 
                    v.IdVenta,
                    v.Total,
                    v.FechaVenta,
                    c.Nombre as ClienteNombre,
                    c.Correo as ClienteCorreo,
                    c.Telefono as ClienteTelefono,
                    u.Nombre as VendedorNombre,
                    COUNT(dv.IdDetalle) as CantidadProductos
                FROM Venta v
                LEFT JOIN Cliente c ON v.IdCliente = c.IdCliente
                LEFT JOIN Usuario u ON v.IdUsuario = u.IdUsuario
                LEFT JOIN DetalleVenta dv ON v.IdVenta = dv.IdVenta
                WHERE DATE(v.FechaVenta) BETWEEN ? AND ?
                GROUP BY v.IdVenta, v.Total, v.FechaVenta, c.Nombre, c.Correo, c.Telefono, u.Nombre
                ORDER BY v.FechaVenta DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fechaInicio, $fechaFin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer ventas por rango de fechas: " . $e->getMessage());
            return [];
        }
    }
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function crearVenta($data) {
        try {
            $this->conn->beginTransaction();
            
            $idUsuario = $data['idUsuario'];
            $idCliente = $data['idCliente'] ?? null;
            $productos = $data['productos'] ?? [];
            
            // Calcular total
            $total = 0;
            foreach ($productos as $producto) {
                $total += $producto['cantidad'] * $producto['precio'];
            }
            
            // Crear venta
            $sql = "INSERT INTO Venta (IdUsuario, IdCliente, Total) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$idUsuario, $idCliente, $total]);
            $idVenta = $this->conn->lastInsertId();
            
            // Crear detalles de venta
            foreach ($productos as $producto) {
                $sql = "INSERT INTO DetalleVenta (IdVenta, IdProducto, Cantidad, PrecioUnitario) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    $idVenta, 
                    $producto['idProducto'], 
                    $producto['cantidad'], 
                    $producto['precio']
                ]);
                
                // Actualizar stock
                $sql = "UPDATE Producto SET Stock = Stock - ? WHERE IdProducto = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$producto['cantidad'], $producto['idProducto']]);
            }
            
            // Registrar log
            $sql = "INSERT INTO LogAcceso (IdUsuario, Accion) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$idUsuario, 'Nueva venta creada - ID: ' . $idVenta]);
            
            $this->conn->commit();
            return ['success' => true, 'idVenta' => $idVenta, 'total' => $total];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error crear venta: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al crear venta: ' . $e->getMessage()];
        }
    }

    public function leerVentas() {
        try {
            $sql = "
                SELECT 
                    v.IdVenta,
                    v.Total,
                    v.FechaVenta,
                    c.Nombre as ClienteNombre,
                    c.Correo as ClienteCorreo,
                    c.Telefono as ClienteTelefono,
                    u.Nombre as VendedorNombre,
                    COUNT(dv.IdDetalle) as CantidadProductos
                FROM Venta v
                LEFT JOIN Cliente c ON v.IdCliente = c.IdCliente
                LEFT JOIN Usuario u ON v.IdUsuario = u.IdUsuario
                LEFT JOIN DetalleVenta dv ON v.IdVenta = dv.IdVenta
                GROUP BY v.IdVenta, v.Total, v.FechaVenta, c.Nombre, c.Correo, c.Telefono, u.Nombre
                ORDER BY v.FechaVenta DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer ventas: " . $e->getMessage());
            return [];
        }
    }

    public function leerVentaPorId($id) {
        try {
            $sql = "
                SELECT 
                    v.IdVenta,
                    v.Total,
                    v.FechaVenta,
                    c.Nombre as ClienteNombre,
                    c.Correo as ClienteCorreo,
                    c.Telefono as ClienteTelefono,
                    c.Direccion as ClienteDireccion,
                    u.Nombre as VendedorNombre
                FROM Venta v
                LEFT JOIN Cliente c ON v.IdCliente = c.IdCliente
                LEFT JOIN Usuario u ON v.IdUsuario = u.IdUsuario
                WHERE v.IdVenta = ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer venta: " . $e->getMessage());
            return null;
        }
    }

    public function leerDetalleVenta($id) {
        try {
            $sql = "
                SELECT 
                    dv.IdDetalle,
                    dv.Cantidad,
                    dv.PrecioUnitario,
                    dv.Subtotal,
                    p.Nombre as ProductoNombre,
                    p.Imagen as ProductoImagen
                FROM DetalleVenta dv
                INNER JOIN Producto p ON dv.IdProducto = p.IdProducto
                WHERE dv.IdVenta = ?
                ORDER BY dv.IdDetalle
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer detalle venta: " . $e->getMessage());
            return [];
        }
    }

    public function eliminarVenta($id) {
        try {
            $this->conn->beginTransaction();
            
            // Restaurar stock de productos
            $sql = "
                UPDATE Producto p
                INNER JOIN DetalleVenta dv ON p.IdProducto = dv.IdProducto
                SET p.Stock = p.Stock + dv.Cantidad
                WHERE dv.IdVenta = ?
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            // Eliminar detalles de venta
            $sql = "DELETE FROM DetalleVenta WHERE IdVenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            // Eliminar venta
            $sql = "DELETE FROM Venta WHERE IdVenta = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error eliminar venta: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null) {
        try {
            $stats = [];
            if ($fechaInicio && $fechaFin) {
                // Ventas y estadísticas por rango de fechas
                $stmt = $this->conn->prepare("
                    SELECT 
                        COUNT(*) as ventasHoy,
                        COALESCE(SUM(Total), 0) as ingresosHoy
                    FROM Venta 
                    WHERE DATE(FechaVenta) BETWEEN ? AND ?
                ");
                $stmt->execute([$fechaInicio, $fechaFin]);
                $hoy = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['ventasHoy'] = $hoy['ventasHoy'];
                $stats['ingresosHoy'] = $hoy['ingresosHoy'];

                $stmt = $this->conn->prepare("
                    SELECT COUNT(DISTINCT IdCliente) as clientesHoy
                    FROM Venta 
                    WHERE DATE(FechaVenta) BETWEEN ? AND ?
                ");
                $stmt->execute([$fechaInicio, $fechaFin]);
                $stats['clientesHoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['clientesHoy'];

                $stmt = $this->conn->prepare("
                    SELECT COALESCE(SUM(dv.Cantidad), 0) as productosVendidosHoy
                    FROM DetalleVenta dv
                    INNER JOIN Venta v ON dv.IdVenta = v.IdVenta
                    WHERE DATE(v.FechaVenta) BETWEEN ? AND ?
                ");
                $stmt->execute([$fechaInicio, $fechaFin]);
                $productos = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['productosVendidosHoy'] = $productos['productosVendidosHoy'];
            } else {
                // Estadísticas del día actual
                $stmt = $this->conn->query("
                    SELECT 
                        COUNT(*) as ventasHoy,
                        COALESCE(SUM(Total), 0) as ingresosHoy
                    FROM Venta 
                    WHERE DATE(FechaVenta) = CURDATE()
                ");
                $hoy = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['ventasHoy'] = $hoy['ventasHoy'];
                $stats['ingresosHoy'] = $hoy['ingresosHoy'];

                $stmt = $this->conn->query("
                    SELECT COUNT(DISTINCT IdCliente) as clientesHoy
                    FROM Venta 
                    WHERE DATE(FechaVenta) = CURDATE()
                ");
                $stats['clientesHoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['clientesHoy'];

                $stmt = $this->conn->query("
                    SELECT COALESCE(SUM(dv.Cantidad), 0) as productosVendidosHoy
                    FROM DetalleVenta dv
                    INNER JOIN Venta v ON dv.IdVenta = v.IdVenta
                    WHERE DATE(v.FechaVenta) = CURDATE()
                ");
                $productos = $stmt->fetch(PDO::FETCH_ASSOC);
                $stats['productosVendidosHoy'] = $productos['productosVendidosHoy'];
            }
            return $stats;
        } catch (PDOException $e) {
            error_log("Error obtener estadísticas ventas: " . $e->getMessage());
            return [
                'ventasHoy' => 0,
                'ingresosHoy' => 0,
                'clientesHoy' => 0,
                'productosVendidosHoy' => 0
            ];
        }
    }

    public function leerVentasPorFecha($fecha) {
        try {
            $sql = "
                SELECT 
                    v.IdVenta,
                    v.Total,
                    v.FechaVenta,
                    c.Nombre as ClienteNombre,
                    c.Correo as ClienteCorreo,
                    c.Telefono as ClienteTelefono,
                    u.Nombre as VendedorNombre,
                    COUNT(dv.IdDetalle) as CantidadProductos
                FROM Venta v
                LEFT JOIN Cliente c ON v.IdCliente = c.IdCliente
                LEFT JOIN Usuario u ON v.IdUsuario = u.IdUsuario
                LEFT JOIN DetalleVenta dv ON v.IdVenta = dv.IdVenta
                WHERE DATE(v.FechaVenta) = ?
                GROUP BY v.IdVenta, v.Total, v.FechaVenta, c.Nombre, c.Correo, c.Telefono, u.Nombre
                ORDER BY v.FechaVenta DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$fecha]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error leer ventas por fecha: " . $e->getMessage());
            return [];
        }
    }
}
