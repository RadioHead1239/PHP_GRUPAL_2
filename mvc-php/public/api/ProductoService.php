<?php
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../../app/controller/ProductoController.php";

$controller = new ProductoController();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case "listar":
        echo json_encode($controller->listar());
        break;

    case "crear":
    $data = [
        "Nombre" => $_POST['Nombre'] ?? '',
        "Precio" => $_POST['Precio'] ?? 0,
        "Stock"  => $_POST['Stock'] ?? 0,
        "Descripcion" => $_POST['Descripcion'] ?? '',
        "Estado" => $_POST['Estado'] ?? 1,
        "Imagen" => ""
    ];

    // Imagen
    if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImg = time() . "_" . $_FILES['Imagen']['name'];
        move_uploaded_file($_FILES['Imagen']['tmp_name'], __DIR__ . "/../../assets/img/" . $nombreImg);
        $data["Imagen"] = $nombreImg;
    }

    $ok = $controller->crear($data);
    echo json_encode(["success" => $ok]);
    break;

case "editar":
    if (!isset($_POST['IdProducto'])) {
        echo json_encode(["success" => false, "message" => "Falta IdProducto"]);
        exit;
    }

    $data = $_POST;

    // Imagen nueva opcional
    if (isset($_FILES['Imagen']) && $_FILES['Imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImg = time() . "_" . $_FILES['Imagen']['name'];
        move_uploaded_file($_FILES['Imagen']['tmp_name'], __DIR__ . "/../../assets/img/" . $nombreImg);
        $data["Imagen"] = $nombreImg;
    }

    $ok = $controller->editar($data, $_FILES);
    echo json_encode(["success" => $ok]);
    break;


    default:
        echo json_encode(["success" => false, "message" => "Acción no válida"]);
        break;
}
