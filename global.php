<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");

ini_set('default_charset','UTF-8');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('DIR', $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'app_vazoli/'.'storage/');
define('R_DIR', '../storage/');

require 'actions/action-files.php';
require 'actions/action-paths.php';
require 'actions/action-select.php';
require 'actions/action-users.php';

/**
* global function to get the last name of a string
*/
function after_last($iden, $inthat)
{
  if (!is_bool(strrpos($inthat, $iden))) :
   return substr($inthat, strrpos($inthat, $iden)+strlen($iden));
  endif;
}


// GERAR JWT TOKEN
function gerar_token($payload_user) {
  function corrigirBase64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
  }

  $header = corrigirBase64UrlEncode('{"alg": "HS256", "typ", "JWT"}');
  $payload = corrigirBase64UrlEncode('{"sub": "'.md5(time()).'", '.str_replace(['{', '}'], ['', ''], json_encode($payload_user)).', "iat": '.time().'}');
  $signature = corrigirBase64UrlEncode(hash_hmac('sha256', $header.'.'.$payload, 'keyLogin', true));

  return $header.'.'.$payload.'.'.$signature;
}


// ENCODAR JSON
function preparar_resposta_json($data) {
  if (!empty($data)) {
    return json_encode($data);
  }
}
