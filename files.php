<?php

  require 'global.php';

  /**
  * Somente teste
  */

  
  if ( isset($_POST['acao']) && !empty($_POST['acao']) ) {
    $acao = $_POST['acao'];
  }

  if ( isset($_POST['fileID']) && !empty($_POST['fileID']) ) {
    $fileID = $_POST['fileID'];
  }

  if ( isset($_POST['path']) && !empty($_POST['path']) ) {
    $path = $_POST['path'];
  }

  
  if( isset($acao) && $acao === "upload" ) {

    $files = new files();
    $result = $files->UPLOAD($path);

    echo preparar_resposta_json($result);

  }



  if( isset($fileID) && isset($newNameFile) && isset($acao) && $acao === "renomear" ) {

    $renomear = new files();
    $result = $renomear->UPDATE($fileID, $newNameFile);

    echo preparar_resposta_json($result);

  }

 
  if( isset($fileID) && isset($acao) && $acao === "deletar" ) {

    $delete = new files();
    $result = $delete->DELETE($fileID);

    echo preparar_resposta_json($result);

  }

  $request_json = json_decode(file_get_contents('php://input'), true);

  if ($request_json) {

    $acao = $request_json['acao'];
    $arrayID = $request_json['arrayID'];


    if( $acao == "multipleDelete" ) {

      $deleteMultiple = new files();
      $result = $deleteMultiple->DELETEMULTIPLE($arrayID);
  
      echo preparar_resposta_json($result);
  
    }


    if( $acao === "multipleDownload" ) {

      $multipleDownload = new files();
      $result = $multipleDownload->DOWNLOADZIP($arrayID);
  
    }
  }
