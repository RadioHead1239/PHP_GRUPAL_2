<?php
class Producto {
    private $idProducto;
    private $nombre;
    private $imagen;
    private $descripcion;
    private $precio;
    private $stock;
    private $estado;
    private $fechaRegistro;

    public function getIdProducto() { return $this->idProducto; }
    public function setIdProducto($idProducto) { $this->idProducto = $idProducto; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getImagen() { return $this->imagen; }
    public function setImagen($imagen) { $this->imagen = $imagen; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio) { $this->precio = $precio; }

    public function getStock() { return $this->stock; }
    public function setStock($stock) { $this->stock = $stock; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getFechaRegistro() { return $this->fechaRegistro; }
    public function setFechaRegistro($fechaRegistro) { $this->fechaRegistro = $fechaRegistro; }
}
