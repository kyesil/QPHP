<?php
class HomeC extends Y_Controller
{
 
  function _init()
  { // initially run this method before actions 
  }
  public function home()
  {
    $this->viewdata['vData'] = "hello view data "; //send data to view
  }

  public function contact()
  {
    $this->viewdata['vData'] = "hello view data "; //send data to view
  }
}
