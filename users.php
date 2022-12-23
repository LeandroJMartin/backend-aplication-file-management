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
    * execute the select function
    */
    if($acao === 'select') :

      $result = $class->SELECT();
      echo $result;

    endif;


    /**
    * execute the create function
    */
    if($acao === 'create') :

      $name  = $json['name'];
      $unit  = $json['unit'];
      $user  = $json['user'];
      $pass  = $json['pass'];
      $level = $json['level'];

      $result = $class->CREATE($name, $unit, $user, $pass, $level);
      echo $result;

    endif;


    /**
    * execute the update function
    */
    if($acao === 'update') :

      $id_user  = $json['id_user'];
      $name     = $json['name'];
      $unit     = $json['unit'];
      $user     = $json['user'];
      $pass     = $json['pass'];

      $result = $class->UPDATE($id_user, $name, $unit, $user, $pass);
      echo $result;

    endif;


    /**
    * execute the dalete function
    */
    if($acao === 'delete') :

      $id_user = $json['id_user'];

      $result = $class->DELETE($id_user);
      echo $result;

    endif;

  else :

    echo 'EMPTY';

  endif;
