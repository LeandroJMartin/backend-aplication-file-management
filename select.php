<?php

  require 'global.php';

  /**
  * Get and decode json data
  */
  $request = json_decode(file_get_contents('php://input'), true);

  if(!empty($request)) {

    /**
    * Search all folders and files in the directory
    */
    $select = new selectt();
    $result = $select->SELECT($request['path']);

    echo preparar_resposta_json($result);
    exit();

  } else {

    echo preparar_resposta_json('EMPTY');

  };
