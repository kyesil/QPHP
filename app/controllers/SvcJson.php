<?php
class SvcJsonC extends Y_Controller
{

  function _init()
  {
    $this->autoRender = false; // disable view render for api

    header('Content-Type: application/json');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
  }

  public function getdata()
  {
    $result = array('status' => 'ok', 'data' => 'exampledata');
    echo json_encode($result);
  }

 
}
