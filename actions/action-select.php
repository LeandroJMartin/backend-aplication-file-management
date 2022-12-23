<?php

date_default_timezone_set('America/Sao_Paulo');

/**
 * Class select paths and files
 */
class selectt
{
  
  public function SELECT($path) {
    try {
      define('SDIR', R_DIR.$path.'/');
      
      if (file_exists(SDIR)) :
        
        $list_dir = array_diff(
          scandir(SDIR),
          ['.', '..']
        );
        
        function order_array($itens){
            require 'inc/connect.php';

            $fileInfo = pathinfo(SDIR.$itens);
            $type = filetype(SDIR.$itens);

            if ($type === 'file') {
              $query = $db->prepare('SELECT id FROM files WHERE name LIKE ?');
              $query->execute(['%'.$itens.'%']);
              $file_ID = $query->fetchObject();
            }

            if ($type === 'file' && $fileInfo['extension'] !== "DS_Store") {
              return(array(
                'fileID' => $file_ID ? $file_ID : null,
                'name' => $itens,
                'type' => $type,
                'fileInfo' => $fileInfo,
                'fileSize' => number_format(filesize(SDIR.$itens) / 1048576, 2) . ' MB',
                'timeCreate' => Date('d-m-Y H:i:s', filemtime(SDIR.$itens)),
              ));
            }
            
            if ($type === 'dir') {
              return(array(
                'name' => $itens,
                'type' => $type,
                'fileInfo' => $fileInfo,
                'fileSize' => number_format(filesize(SDIR.$itens) / 1048576, 2) . ' MB',
                'timeCreate' => Date('d-m-Y H:i:s', filemtime(SDIR.$itens)),
              ));
            }
          }

          function filter($v){
            return !(is_null($v));
          }

          $NewArray = array_values(array_filter($list_dir));
          $NewArray = array_map('order_array', $NewArray);
          $NewArray = array_values(array_filter($NewArray, 'filter'));
          return json_encode($NewArray);

      else :

          return json_encode("Não existe nenhuma pasta ou arquivo com este nome");

      endif;

    } catch (\Exception $e) {

      return json_encode('ERROR' . $e->getMessage());

    }
  }
}
