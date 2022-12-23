<?php

  require 'global.php';

  /**
  * Get and decode json data
  */
  $json = json_decode(file_get_contents('php://input'), true);

  if(!empty($json)) :

    $acao = $json['acao'];

    /**
    * Start the class
    */
    $class = new paths();


    /**
    * execute the create function
    */
    if($acao === 'criar_pasta') :

      $path = $json['path'];
      $name = $json['name'];

      $result = $class->CREATE($path, $name);
      echo preparar_resposta_json($result);

    endif;



    /**
    * execute the update function
    */
    if($acao === 'renomear_pasta') :

      $path = $json['path'];
      $nName = $json['nName'];

      $result = $class->UPDATE($path, $nName);
      echo preparar_resposta_json($result);

    endif;



    /**
    * execute the dalete function
    */
    if($acao === 'deletar_pasta') :

      $path = $json['path'];

      $result = $class->DELETE($path);
      echo preparar_resposta_json($result);

    endif;

  else :

    echo preparar_resposta_json("EMPTY");;

  endif;
