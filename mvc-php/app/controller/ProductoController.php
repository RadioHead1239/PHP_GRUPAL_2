<?php
require_once __DIR__ . '/../dao/ProductoDAO.php';

class ProductoController {
    private $productoDAO;

    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    public function crear($data) {
        $producto = new Producto();
        $producto->setNombre($data['nombre']);
        $producto->setImagen($data['imagen']);
        $producto->setDescripcion($data['descripcion']);
        $producto->setPrecio($data['precio']);
        $producto->setStock($data['stock']);
        $producto->setEstado($data['estado']);
        return $this->productoDAO->crearProducto($producto);
    }

    public function listar() {
        return $this->productoDAO->leerProductos();
    }

    public function obtenerPorId($id) {
        return $this->productoDAO->leerProductoPorId($id);
    }

    public function editar($data, $files = []) {
    $producto = new Producto();
    $producto->setIdProducto($data['IdProducto']);
    $producto->setNombre($data['Nombre']);
    $producto->setDescripcion($data['Descripcion']);
    $producto->setPrecio($data['Precio']);
    $producto->setStock($data['Stock']);
    $producto->setEstado($data['Estado']);

    // Imagen opcional
    if (isset($files['Imagen']) && $files['Imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImg = time() . "_" . $files['Imagen']['name'];
        move_uploaded_file($files['Imagen']['tmp_name'], __DIR__ . "/../../assets/img/" . $nombreImg);
        $producto->setImagen($nombreImg);
    } else {
        // Mantener la imagen actual si no se sube nueva
        $producto->setImagen($data['ImagenActual'] ?? '');
    }

    return $this->productoDAO->actualizarProducto($producto);
}


    public function eliminar($id) {
        return $this->productoDAO->eliminarProducto($id);
    }
}
