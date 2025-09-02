<?php
class DetalleVenta {
    private $idDetalle;
    private $idVenta;
    private $idProducto;
    private $cantidad;
    private $precioUnitario;
    private $subtotal;

    public function getIdDetalle() { return $this->idDetalle; }
    public function setIdDetalle($idDetalle) { $this->idDetalle = $idDetalle; }

    public function getIdVenta() { return $this->idVenta; }
    public function setIdVenta($idVenta) { $this->idVenta = $idVenta; }

    public function getIdProducto() { return $this->idProducto; }
    public function setIdProducto($idProducto) { $this->idProducto = $idProducto; }

    public function getCantidad() { return $this->cantidad; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }

    public function getPrecioUnitario() { return $this->precioUnitario; }
    public function setPrecioUnitario($precioUnitario) { $this->precioUnitario = $precioUnitario; }

    public function getSubtotal() { return $this->subtotal; }
    public function setSubtotal($subtotal) { $this->subtotal = $subtotal; }
}
