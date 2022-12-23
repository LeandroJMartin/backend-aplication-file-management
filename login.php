<?php

  /**
  * Check login and password access user
  */

  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

  require 'global.php';

  
  $get_user = json_decode(file_get_contents('php://input'), true);
  
  if (!empty($get_user)) {
    $user_email = $get_user['email'];
    $user_pass = $get_user['senha'];

    require 'inc/connect.php';

    $connect = $db->prepare("SELECT * FROM users WHERE user = '$user_email' AND pass = '$user_pass'");
    $connect->execute();
    $arqs = $connect->fetchAll(PDO::FETCH_ASSOC);

    $cont = count($arqs);

    foreach ($arqs as $item) {
      $id    = $item['id'];
      $name  = $item['name'];
      $unit  = $item['unit'];
      $user  = $item['user'];
      $pass  = $item['pass'];
      $level = $item['level'];
    }

    if ($cont === 1) {
      if ( $user === $user_email && $pass === $user_pass ) {
        $arqs = array('result' => 'success', 'id' => $id, 'name' => $name, 'unit' => $unit, 'user' => $user, 'level' => $level);
        $token = gerar_token($arqs);

        $connect = $db->prepare("UPDATE users SET ultimoToken='${token}' WHERE user = '$user_email' AND pass = '$user_pass'");
        $connect->execute();

        if ($connect->rowCount() === 1) {
          echo preparar_resposta_json($token);          exit();
        }

      } else {
        echo preparar_resposta_json(['result' => 'erro', 'erro' => "Usuario inválido."]);
      }

    } else {
      echo preparar_resposta_json(['result' => 'erro', 'erro' => "Usuario inválido."]);
      exit();
    }

  } else {
    echo preparar_resposta_json(['result' => 'erro', 'erro' => "Erro na validação de usuario."]);
  };
