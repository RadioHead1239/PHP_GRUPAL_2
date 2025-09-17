<?php
class Usuario {
    private $idUsuario;
    private $nombre;
    private $correo;
    private $claveHash;
    private $rol;
    private $estado;
    private $fechaRegistro;

    public function __construct(
        $idUsuario = null,
        $nombre = null,
        $correo = null,
        $claveHash = null,
        $rol = null,
        $estado = true,
        $fechaRegistro = null
    ) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->claveHash = $claveHash;
        $this->rol = $rol;
        $this->estado = $estado;
        $this->fechaRegistro = $fechaRegistro;
    }

    // Getters y Setters
    public function getIdUsuario() {
        return $this->idUsuario;
    }
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getCorreo() {
        return $this->correo;
    }
    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getClaveHash() {
        return $this->claveHash;
    }
    public function setClaveHash($claveHash) {
        $this->claveHash = $claveHash;
    }

    public function getRol() {
        return $this->rol;
    }
    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getFechaRegistro() {
        return $this->fechaRegistro;
    }
    public function setFechaRegistro($fechaRegistro) {
        $this->fechaRegistro = $fechaRegistro;
    }
}
?>
