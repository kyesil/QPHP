<?php
phpinfo();
  header("Access-Control-Allow-Origin: *");
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
  header('Content-Type: application/json');
echo json_encode(array('data' => [
    "name"=>"ali",
    "email"=>"ali@local",
], "result" => 200));