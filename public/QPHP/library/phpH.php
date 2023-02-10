<?php

class phpH
{
  public static function err($data, $code = 500)
  {
    http_response_code($code);
    exit(json_encode(array('data' => $data, "result" => -$code)));
  }
  
  public static function json($data, $code = 200)
  {
    echo json_encode(array('data' => $data, "result" => $code));
  }
  public static function checkAuth($authValue)
  {
    if ($_SESSION['user']['uLevel'] < $authValue) {
      phpH::err('err_notauth', 401);
    }
  }
}

