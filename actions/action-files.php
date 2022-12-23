<?php


/**
 * Changing paths and paths
 */
class files {
  
  /**
   * Create file
   */
  public function UPLOAD($path) {
    try {
      $data = date('Y-m-d');
      
      require 'inc/connect.php';
      
      // MOVE O ARQUIVO RECEBIDO
      $total_sucesso = [];
      
      foreach($_FILES as $array_arq) {

        $pasta         = R_DIR.$path.'/';
        $file_name     = $array_arq['name'];
        $ext           = pathinfo($file_name, PATHINFO_EXTENSION);
        $conteudo_temp = $array_arq['tmp_name'];
        $novo_nome     = pathinfo($array_arq['name'], PATHINFO_FILENAME).".$ext";

        $insert = $db->prepare('INSERT INTO files (data, name, path, type) VALUES(:data, :name, :path, :type)');
        $insert->bindParam(":data", $data);
        $insert->bindParam(":name", $file_name);
        $insert->bindParam(":path", $path);
        $insert->bindParam(":type", $ext);
      
          if ( move_uploaded_file($conteudo_temp, $pasta.$novo_nome) ) {
            if ($insert->execute()) {

              array_push($total_sucesso, "sucesso");

            } else {

              return ["result" => "erro", "msg" => "erro no banco."];

            }
          } else {

            return ["result" => "erro", "msg" => "caminho invalido"];

          }
      }
      
      if (count($total_sucesso) === count($_FILES)) {

          return ["result" => "sucess", "msg" => "Arquivo adicionado com sucesso!"];
          exit;

      } else {

          return ["result" => "erro", "msg" => "caminho inválido."];
          exit;

      }

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }



  /**
  * Update path
  */
  public function UPDATE($fileID, $newNameFile) {
    try {
      require 'inc/connect.php';

      $query = $db->prepare('SELECT * FROM files WHERE id = ?');
      $query->execute([$fileID]);
      $res = $query->fetch(PDO::FETCH_ASSOC);

      $query = $db->prepare('UPDATE files SET name = ? WHERE id = ?');

      if ( file_exists(R_DIR.$res['path'].'/'.$res['name']) ) {
        if ( rename(R_DIR.$res['path'].'/'.$res['name'], R_DIR.$res['path'].'/'.$newNameFile.'.'.$res['type']) ) {
          if ( $query->execute([$newNameFile.'.'.$res['type'], $fileID]) ) {
            return ["result" => "sucesso", "msg" => "arquivo renomeado"];
          } else {
            return ["result" => "erro", "msg" => "occorreu um erro"];
          }
        } else {
          return ["result" => "erro", "msg" => "arquivo não encontrado"];
        }
      } else {
        return ["result" => "erro", "msg" => "arquivo não encontrado"];
      }

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }



  /**
  * Delete path
  */
  public function DELETE($fileID) {
    try {
      require 'inc/connect.php';

      $query = $db->prepare('SELECT * FROM files WHERE id = ?');
      $query->execute([$fileID]);
      $res = $query->fetch(PDO::FETCH_ASSOC);

      if ( $res ) {
        if ( file_exists(R_DIR.$res['path'].'/'.$res['name']) ) {
          if ( unlink(R_DIR.$res['path'].'/'.$res['name']) ) {
            $query = $db->prepare('DELETE FROM files WHERE id = ?');
  
            if ($query->execute([$fileID])) {
              return ["result" => "sucesso", "msg" => "arquivo deletado"];
            } else {
              return ["result" => "erro", "msg" => "occorreu um erro"];
            }
          }
        }
      }
      

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }


  /**
  * Delete path
  */
  public function DELETEMULTIPLE($array) {
    try {
      require 'inc/connect.php';

      $deletados = [];

      if ( is_array($array) ) {

        foreach( $array as $fileID ) {
          $query = $db->prepare('SELECT * FROM files WHERE id = ?');
          $query->execute([$fileID]);
          $res = $query->fetch(PDO::FETCH_ASSOC);
    
          if ( $res ) {

            if ( file_exists(R_DIR.$res['path'].'/'.$res['name']) ) {

              if ( unlink(R_DIR.$res['path'].'/'.$res['name']) ) {
                $query = $db->prepare('DELETE FROM files WHERE id = ?');
      
                if ( $query->execute([$fileID]) ) {

                  array_push($deletados, 1);

                } else {
                  return ["result" => "erro", "msg" => "occorreu um erro"];
                }
              }

            } else {
              return ["result" => "erro", "msg" => "arquivo não existe."];
            }

          }
        }

        if ( count($deletados) === count($array) ) {
          return ["result" => "sucesso", "msg" => "arquivos deletados."];
        }

      } else {
        return ["result" => "erro", "msg" => "occorreu um erro na chamada."];
      }

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }


  /**
  * Multiple downloads
  */
  public function DOWNLOADZIP($arrayID) {

    if ( !is_array($arrayID) || empty($arrayID) ) {
      exit;
    }

    require 'inc/connect.php';
    require 'zip.php';

    $array_files = [];
    $rootPath;

    foreach($arrayID as $id) {
      $query = $db->prepare('SELECT * FROM files WHERE id = ?');
      $query->execute([$id]);
      $res = $query->fetch(PDO::FETCH_ASSOC);
  
      if ( $res ) {
        $rootPath = DIR.DIRECTORY_SEPARATOR.$res['path'].'/';
        $pathFile = R_DIR.$res['path'].'/'.$res['name'];
        array_push($array_files, $pathFile);
      }
    }

    try {
    $arquivo = 'arquivos.zip';

    $retorno_zip = zip_files($rootPath, $arquivo);

    if (isset($arquivo) && file_exists($arquivo)) {

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Cache-Control: public");
      header("Content-Description: File Transfer");
      header("Content-type: application/zip");
      header("Content-Disposition: attachment; filename=".basename($arquivo));
      header("Content-Transfer-Encoding: binary");
      header("Content-Length: ".filesize($arquivo));

      ob_clean();
      flush();
      ob_end_flush();

      @readfile($arquivo);

      unlink($arquivo);
      exit;
  }

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }

  }
}
