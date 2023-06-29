<?php
require_once "../services/conexoes.php";

$id = $_REQUEST['codigo_prd'];

if ($id) {
    $conn = conectarPDO();
    $sql = 'DELETE FROM produtos WHERE codigo_prd=:codigo_prd';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codigo_prd', $id, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        if ($stmt->rowCount()) {
            echo json_encode(array('statusCode' => 200));
        } else {
            echo json_encode(array('statusCode' => 201));
        }
    }
}
