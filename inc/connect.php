<?php

/**
 * Configuration for database connection
 */

try
{

  $driverOptions = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];
  $db = new PDO('mysql:host=localhost;dbname=namedb', 'root', 'root', $driverOptions);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e)
{

  echo 'ERROR' . $e->getMessage();

}
