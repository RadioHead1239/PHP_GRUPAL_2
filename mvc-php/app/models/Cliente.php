<?php
class Cliente {
    private $idCliente;
    private $nombre;
    private $correo;
    private $telefono;
    private $direccion;
    private $fechaRegistro;

    public function getIdCliente() { return $this->idCliente; }
    public function setIdCliente($idCliente) { $this->idCliente = $idCliente; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getCorreo() { return $this->correo; }
    public function setCorreo($correo) { $this->correo = $correo; }

    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }

    public function getDireccion() { return $this->direccion; }
    public function setDireccion($direccion) { $this->direccion = $direccion; }

    public function getFechaRegistro() { return $this->fechaRegistro; }
    public function setFechaRegistro($fechaRegistro) { $this->fechaRegistro = $fechaRegistro; }
}
