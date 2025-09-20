<?php
require_once __DIR__ . '/../dao/ProductoDAO.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $productoDAO;

    public function __construct() {
        $this->productoDAO = new ProductoDAO();
    }

    // Crear producto
    public function crear($data) {
        $producto = new Producto();
        $producto->setNombre($data['nombre'] ?? $data['Nombre'] ?? null);
        $data['imagen'] = !empty($data['imagen']) ? $data['imagen'] : null;
        $data['descripcion'] = !empty($data['descripcion']) ? $data['descripcion'] : null;
        $producto->setPrecio($data['precio'] ?? $data['Precio'] ?? 0);
        $producto->setStock($data['stock'] ?? $data['Stock'] ?? 0);
        $data['estado'] = isset($data['estado']) ? (int)$data['estado'] : 1;

        error_log("🟢 Crear producto con datos: " . json_encode([
            "Nombre" => $producto->getNombre(),
            "Imagen" => $producto->getImagen(),
            "Descripcion" => $producto->getDescripcion(),
            "Precio" => $producto->getPrecio(),
            "Stock" => $producto->getStock(),
            "Estado" => $producto->getEstado()
        ]));

        return $this->productoDAO->crearProducto($producto);
    }

    // Listar productos
    public function listar() {
        return $this->productoDAO->leerProductos();
    }

    // Obtener producto por ID
    public function obtenerPorId($id) {
        return $this->productoDAO->leerProductoPorId($id);
    }

    // Editar producto
    public function editar($data) {
        $producto = new Producto();
        $producto->setIdProducto($data['id'] ?? $data['IdProducto'] ?? null);
        $producto->setNombre($data['nombre'] ?? $data['Nombre'] ?? null);
        $producto->setImagen($data['imagen'] ?? $data['Imagen'] ?? ($data['ImagenActual'] ?? null));
        $producto->setDescripcion($data['descripcion'] ?? $data['Descripcion'] ?? null);
        $producto->setPrecio($data['precio'] ?? $data['Precio'] ?? 0);
        $producto->setStock($data['stock'] ?? $data['Stock'] ?? 0);
        $producto->setEstado($data['estado'] ?? $data['Estado'] ?? 1);

        error_log("🟡 Editar producto con datos: " . json_encode([
            "IdProducto" => $producto->getIdProducto(),
            "Nombre" => $producto->getNombre(),
            "Precio" => $producto->getPrecio(),
            "Stock" => $producto->getStock(),
            "Estado" => $producto->getEstado()
        ]));

        return $this->productoDAO->actualizarProducto($producto);
    }

    // Eliminar producto
    public function eliminar($id) {
        error_log("🔴 Eliminar producto con ID: " . $id);
        return $this->productoDAO->eliminarProducto($id);
    }
}
