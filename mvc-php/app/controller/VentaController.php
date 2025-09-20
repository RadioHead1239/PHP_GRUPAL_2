<?php
require_once __DIR__ . '/../dao/VentaDAO.php';

class VentaController {
    public function obtenerVentasPorRangoFechas($fechaInicio, $fechaFin) {
        return $this->ventaDAO->leerVentasPorRangoFechas($fechaInicio, $fechaFin);
    }
    private $ventaDAO;

    public function __construct() {
        $this->ventaDAO = new VentaDAO();
    }

    public function crear($data) {
        return $this->ventaDAO->crearVenta($data);
    }

    public function listar() {
        return $this->ventaDAO->leerVentas();
    }

    public function obtenerPorId($id) {
        return $this->ventaDAO->leerVentaPorId($id);
    }

    public function obtenerDetalle($id) {
        return $this->ventaDAO->leerDetalleVenta($id);
    }

    public function eliminar($id) {
        return $this->ventaDAO->eliminarVenta($id);
    }

    public function obtenerEstadisticas($fechaInicio = null, $fechaFin = null) {
        if ($fechaInicio && $fechaFin) {
            return $this->ventaDAO->obtenerEstadisticas($fechaInicio, $fechaFin);
        }
        return $this->ventaDAO->obtenerEstadisticas();
    }

    public function obtenerVentasPorFecha($fecha) {
        return $this->ventaDAO->leerVentasPorFecha($fecha);
    }
}
