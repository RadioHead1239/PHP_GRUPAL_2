<?php
class LogAcceso {
    private $idLog;
    private $idUsuario;
    private $accion;
    private $fecha;

    public function getIdLog() { return $this->idLog; }
    public function setIdLog($idLog) { $this->idLog = $idLog; }

    public function getIdUsuario() { return $this->idUsuario; }
    public function setIdUsuario($idUsuario) { $this->idUsuario = $idUsuario; }

    public function getAccion() { return $this->accion; }
    public function setAccion($accion) { $this->accion = $accion; }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; }
}
