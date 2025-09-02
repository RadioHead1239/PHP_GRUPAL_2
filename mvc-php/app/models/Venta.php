<?php
class Venta {
    private $idVenta;
    private $idUsuario;
    private $idCliente;
    private $total;
    private $fechaVenta;

    public function getIdVenta() { return $this->idVenta; }
    public function setIdVenta($idVenta) { $this->idVenta = $idVenta; }

    public function getIdUsuario() { return $this->idUsuario; }
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }

    public function getIdCliente() { return $this->idCliente; }
    public function setIdCliente($idCliente) { $this->idCliente = $idCliente; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getFechaVenta() { return $this->fechaVenta; }
    public function setFechaVenta($fechaVenta) { $this->fechaVenta = $fechaVenta; }
}
