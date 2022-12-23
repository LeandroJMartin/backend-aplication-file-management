<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

$total_sucesso = [];

try {
    foreach($_FILES as $array_arq) {
        $pasta = "upload/";
        $ext = pathinfo($array_arq['name'], PATHINFO_EXTENSION);
        $conteudo_temp = $array_arq['tmp_name'];
        $novo_nome = uniqid().".$ext";
    
        if (move_uploaded_file($conteudo_temp, $pasta.$novo_nome)) {
            array_push($total_sucesso, "sucesso");
        }
    }
    
    if (count($total_sucesso) === count($_FILES)) {
        echo json_encode("sucesso");
    } else {
        echo json_encode("falha");
    }
} catch (\Throwable $th) {
    echo json_encode($th);
}
