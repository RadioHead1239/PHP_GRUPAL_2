<?php
require_once __DIR__ . '/../dao/ClienteDAO.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $clienteDAO;

    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    public function crear($data) {
        $cliente = new Cliente();
        $cliente->setNombre($data['nombre']);
        $cliente->setApellido($data['apellido']);
        $cliente->setEmail($data['email']);
        $cliente->setTelefono($data['telefono']);
        $cliente->setDireccion($data['direccion']);
        $cliente->setEstado($data['estado']);
        return $this->clienteDAO->crearCliente($cliente);
    }

    public function listar() {
        return $this->clienteDAO->leerClientes();
    }

    public function obtenerPorId($id) {
        return $this->clienteDAO->leerClientePorId($id);
    }

    public function editar($data) {
        $cliente = new Cliente();
        $cliente->setIdCliente($data['IdCliente']);
        $cliente->setNombre($data['Nombre']);
        $cliente->setApellido($data['Apellido']);
        $cliente->setEmail($data['Email']);
        $cliente->setTelefono($data['Telefono']);
        $cliente->setDireccion($data['Direccion']);
        $cliente->setEstado($data['Estado']);
        return $this->clienteDAO->actualizarCliente($cliente);
    }

    public function eliminar($id) {
        return $this->clienteDAO->eliminarCliente($id);
    }
}
