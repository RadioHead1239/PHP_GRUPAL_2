<?php
require_once __DIR__ . '/../config/database.php';

class DashboardDAO {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function obtenerDashboardAdmin() {
        try {
            $stats = [];
            // Total de usuarios activos
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM Usuario WHERE Estado = 1");
            $stats['totalUsuarios'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            // Total de clientes
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM Cliente");
            $stats['totalClientes'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            // Total de productos activos
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM Producto WHERE Estado = 1");
            $stats['totalProductos'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
            // Total de ventas
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM Venta");
            $stats['totalVentas'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Ventas por mes (últimos 6 meses)
            $stmt = $this->conn->query("
                SELECT DATE_FORMAT(FechaVenta, '%Y-%m') as mes, COUNT(*) as ventas, SUM(Total) as ingresos
                FROM Venta
                GROUP BY mes
                ORDER BY mes DESC
                LIMIT 6
            ");
            $stats['ventasPorMes'] = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

            // Productos más vendidos (top 5)
            $stmt = $this->conn->query("
                SELECT p.Nombre, SUM(dv.Cantidad) as cantidad
                FROM DetalleVenta dv
                INNER JOIN Producto p ON dv.IdProducto = p.IdProducto
                GROUP BY p.IdProducto, p.Nombre
                ORDER BY cantidad DESC
                LIMIT 5
            ");
            $stats['productosMasVendidos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Actividad reciente (últimos 10 logs)
            $stmt = $this->conn->query("
                SELECT la.Accion, la.Fecha, u.Nombre as UsuarioNombre
                FROM LogAcceso la
                INNER JOIN Usuario u ON la.IdUsuario = u.IdUsuario
                ORDER BY la.Fecha DESC
                LIMIT 10
            ");
            $stats['actividadReciente'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Notificaciones
            $notificaciones = [];
            // Stock bajo
            $stmt = $this->conn->query("SELECT Nombre, Stock FROM Producto WHERE Stock <= 5 AND Estado = 1");
            $stockBajo = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($stockBajo as $prod) {
                $notificaciones[] = [
                    'tipo' => 'stock',
                    'mensaje' => 'Stock bajo: ' . $prod['Nombre'] . ' (' . $prod['Stock'] . ' unidades restantes)'
                ];
            }
            // Nuevo reporte (ejemplo: ventas del mes)
            $notificaciones[] = [
                'tipo' => 'reporte',
                'mensaje' => 'Ventas del mes disponibles'
            ];
            // Backup exitoso (simulado)
            $notificaciones[] = [
                'tipo' => 'backup',
                'mensaje' => 'Respaldo de datos exitoso'
            ];
            $stats['notificaciones'] = $notificaciones;

            return $stats;
        } catch (PDOException $e) {
            error_log("Error obtener dashboard admin: " . $e->getMessage());
            return [
                'totalUsuarios' => 0,
                'totalClientes' => 0,
                'totalProductos' => 0,
                'totalVentas' => 0,
                'ventasPorMes' => [],
                'productosMasVendidos' => [],
                'actividadReciente' => [],
                'notificaciones' => []
            ];
        }
    }

    public function obtenerDashboardVendedor($idUsuario) {
        try {
            $stats = [];
            
            // Ventas del vendedor hoy
            $stmt = $this->conn->prepare("
                SELECT 
                    COUNT(*) as ventasHoy,
                    COALESCE(SUM(Total), 0) as ingresosHoy
                FROM Venta 
                WHERE IdUsuario = ? AND DATE(FechaVenta) = CURDATE()
            ");
            $stmt->execute([$idUsuario]);
            $hoy = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['ventasHoy'] = $hoy['ventasHoy'];
            $stats['ingresosHoy'] = $hoy['ingresosHoy'];
            
            // Clientes atendidos hoy
            $stmt = $this->conn->prepare("
                SELECT COUNT(DISTINCT IdCliente) as clientesHoy
                FROM Venta 
                WHERE IdUsuario = ? AND DATE(FechaVenta) = CURDATE()
            ");
            $stmt->execute([$idUsuario]);
            $stats['clientesHoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['clientesHoy'];
            
            // Meta diaria (ejemplo: 10 ventas por día)
            $metaDiaria = 10;
            $stats['metaDiaria'] = $metaDiaria;
            $stats['progresoMeta'] = min(($hoy['ventasHoy'] / $metaDiaria) * 100, 100);
            
            // Ventas del mes
            $stmt = $this->conn->prepare("
                SELECT 
                    COUNT(*) as ventasMes,
                    COALESCE(SUM(Total), 0) as ingresosMes
                FROM Venta 
                WHERE IdUsuario = ? 
                AND MONTH(FechaVenta) = MONTH(CURDATE()) 
                AND YEAR(FechaVenta) = YEAR(CURDATE())
            ");
            $stmt->execute([$idUsuario]);
            $mes = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['ventasMes'] = $mes['ventasMes'];
            $stats['ingresosMes'] = $mes['ingresosMes'];
            
            // Productos más vendidos por el vendedor
            $stmt = $this->conn->prepare("
                SELECT 
                    p.Nombre,
                    SUM(dv.Cantidad) as CantidadVendida
                FROM DetalleVenta dv
                INNER JOIN Producto p ON dv.IdProducto = p.IdProducto
                INNER JOIN Venta v ON dv.IdVenta = v.IdVenta
                WHERE v.IdUsuario = ?
                AND MONTH(v.FechaVenta) = MONTH(CURDATE()) 
                AND YEAR(v.FechaVenta) = YEAR(CURDATE())
                GROUP BY p.IdProducto, p.Nombre
                ORDER BY CantidadVendida DESC
                LIMIT 5
            ");
            $stmt->execute([$idUsuario]);
            $stats['topProductos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error obtener dashboard vendedor: " . $e->getMessage());
            return [
                'ventasHoy' => 0,
                'ingresosHoy' => 0,
                'clientesHoy' => 0,
                'metaDiaria' => 10,
                'progresoMeta' => 0,
                'ventasMes' => 0,
                'ingresosMes' => 0,
                'topProductos' => []
            ];
        }
    }

    public function obtenerEstadisticasGenerales() {
        try {
            $stats = [];
            
            // Ventas por mes (últimos 12 meses)
            $stmt = $this->conn->query("
                SELECT 
                    YEAR(FechaVenta) as año,
                    MONTH(FechaVenta) as mes,
                    COUNT(*) as ventas,
                    SUM(Total) as ingresos
                FROM Venta 
                WHERE FechaVenta >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY YEAR(FechaVenta), MONTH(FechaVenta)
                ORDER BY año, mes
            ");
            $stats['ventasPorMes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Distribución de ventas por vendedor
            $stmt = $this->conn->query("
                SELECT 
                    u.Nombre as VendedorNombre,
                    COUNT(v.IdVenta) as TotalVentas,
                    SUM(v.Total) as TotalIngresos
                FROM Venta v
                INNER JOIN Usuario u ON v.IdUsuario = u.IdUsuario
                WHERE MONTH(v.FechaVenta) = MONTH(CURDATE()) 
                AND YEAR(v.FechaVenta) = YEAR(CURDATE())
                GROUP BY u.IdUsuario, u.Nombre
                ORDER BY TotalIngresos DESC
            ");
            $stats['ventasPorVendedor'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Productos con stock bajo
            $stmt = $this->conn->query("
                SELECT 
                    Nombre,
                    Stock,
                    Precio
                FROM Producto 
                WHERE Stock <= 5 AND Estado = 1
                ORDER BY Stock ASC
            ");
            $stats['productosStockBajo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Error obtener estadísticas generales: " . $e->getMessage());
            return [
                'ventasPorMes' => [],
                'ventasPorVendedor' => [],
                'productosStockBajo' => []
            ];
        }
    }
}
