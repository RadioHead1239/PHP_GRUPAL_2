<?php
require_once __DIR__ . '/../dao/DashboardDAO.php';

class DashboardController {
    private $dashboardDAO;

    public function __construct() {
        $this->dashboardDAO = new DashboardDAO();
    }

    public function obtenerAdmin() {
        return $this->dashboardDAO->obtenerDashboardAdmin();
    }

    public function obtenerVendedor($idUsuario) {
        return $this->dashboardDAO->obtenerDashboardVendedor($idUsuario);
    }

    public function obtenerEstadisticas() {
        return $this->dashboardDAO->obtenerEstadisticasGenerales();
    }
}
