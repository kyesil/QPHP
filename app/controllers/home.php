<?php
class HomeC extends Q_Controller
{
  function _init()
  { 
    // initially run this method before actions 
  }
  public function home()
  {
    $this->viewVars['myvar'] = "hello home"; //send data to view
    $this->renderview();
  }

  public function page2()
  {
    $this->viewVars['myvar'] = "hello page2"; //send data to view
    
  }
}
