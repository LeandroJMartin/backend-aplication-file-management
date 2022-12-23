<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

require 'global.php';

$get_post_json = json_decode(file_get_contents('php://input'), true);

if (!empty($get_post_json)) {
    $token = $get_post_json['token_in_sessionStorage'];
    $decoded_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
    
    $user_email = $decoded_token->user;

    require 'inc/connect.php';

    $connect = $db->prepare("SELECT user FROM users WHERE user = '$user_email' AND ultimoToken = '$token'");
    $connect->execute();

    if ($connect->rowCount() === 1) {
        echo preparar_resposta_json(['result' => 'success', 'token' => "válido"]);
    } else {
        echo preparar_resposta_json(['result' => 'erro', 'erro' => "Erro na validação de usuario."]);
    }

} else {
    echo preparar_resposta_json(['result' => 'erro', 'erro' => "Erro na validação de usuario."]);
}