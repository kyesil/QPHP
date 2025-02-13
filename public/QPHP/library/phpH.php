<?php

class phpH
{
  public static function err($data, $code = 500)
  {
    http_response_code($code);
    exit(json_encode(['data' => $data,  "result" => -$code]));
  }
  
  public static function json($data, $code = 200): void
  {
    echo json_encode(['data' => $data, "result" => $code]);
  }
  public static function randomStr($length = 5, $onlynumber = true)
  {
    $characters = $onlynumber ? '0123456789' : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}

