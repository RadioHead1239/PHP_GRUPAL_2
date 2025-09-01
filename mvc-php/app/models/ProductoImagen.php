<?php
class ProductoImagen {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function agregarImagen($idProducto, $urlImagen, $esPrincipal = false) {
        if (empty($urlImagen)) {
            return ["La URL de la imagen es obligatoria."];
        }

        $stmt = $this->db->prepare("INSERT INTO ProductoImagen (IdProducto, UrlImagen, EsPrincipal) VALUES (?, ?, ?)");
        $stmt->execute([$idProducto, $urlImagen, $esPrincipal ? 1 : 0]);

        return true;
    }

    public function obtenerImagenes($idProducto) {
        $stmt = $this->db->prepare("SELECT * FROM ProductoImagen WHERE IdProducto = ?");
        $stmt->execute([$idProducto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
