<?php

/**
* Changing paths
*/
class paths
{

  /**
  * Create path
  */
  public function CREATE($path, $name)
  {

    try {

      $v_dir = R_DIR.$path.'/'.$name;

      if (!is_dir($v_dir)) :

        mkdir($v_dir, 0755, true);
        return ["result" => "sucesso", "msg" => "Pasta criada com sucesso!"];
        
      else :
          
        return ["result" => "sucesso", "msg" => "Esta pasta ja existe."];

      endif;

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }


  /**
  * Update path
  */
  public function UPDATE($path, $nName)
  {

    try {

      $v_dir = R_DIR.$path;

      if (is_dir($v_dir)) :

        $ex_dir = explode('/', $path);
        $dir_name = array_pop($ex_dir);
        $uni_dir = implode('/', $ex_dir);

        $list_dir = array_diff(
          scandir(R_DIR.$uni_dir),
          ['.', '..']
        );

        if(!in_array($nName, $list_dir)) :

          $new = R_DIR.$uni_dir.'/'.$nName;

          rename(R_DIR.$path, $new);
          return ["result" => "sucesso", "msg" => "Nome da pasta alterado com sucesso!"];

        else :

          return ["result" => "erro", "msg" => "O nome da pasta já existe."];

        endif;

      else :

        return ["result" => "erro", "msg" => "Esta pasta não existe."];

      endif;


    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }

  /**
  * Delete path
  */
  public function DELETE($path)
  {

    try {

      $dir = R_DIR.$path;

      if (is_dir($dir)) :

        if(@rmdir($dir)) :

          return ["result" => "sucesso", "msg" => "Pasta excluida com sucesso!"];

        else :

          return ["result" => "erro", "msg" => "Erro ao excluir a pasta existe arquivos!"];

        endif;

      else :

        return ["result" => "erro", "msg" => "Esta pasta não existe."];

      endif;

    } catch (\Exception $e) {

      return 'ERROR' . $e->getMessage();

    }
  }
}
