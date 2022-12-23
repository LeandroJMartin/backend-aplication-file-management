<?php

// $source = <absolute pathname to directory/file to be zipped> . DIRECTORY_SEPARATOR;
// $fileName = "myfiles.zip";
// $destination = <absolute pathname to destination directory> . DIRECTORY_SEPARATOR . $fileName;

function zip_files( $source, $destination ) 
{
  $zip = new ZipArchive();

  if($zip->open($destination, ZIPARCHIVE::CREATE) === true) {

    $source = realpath($source);

    if(is_dir($source)) {
      $iterator = new RecursiveDirectoryIterator($source);
      $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
      $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

      foreach($files as $file) {
        $file = realpath($file);
        if(is_dir($file)) {
          $zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
        }elseif(is_file($file)) {
          $zip->addFile($file,str_replace($source . DIRECTORY_SEPARATOR, '', $file));
        }
      }

    }elseif(is_file($source)) {

      $zip->addFile($source,basename($source));

    }
  }

  return $zip->close();
}