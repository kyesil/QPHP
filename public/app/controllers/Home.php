<?php
class HomeC extends Q_Controller
{
  function _init()
  { 
    // initially run this method before actions 
  }
  public function home()
  {
    $this->viewVars['myvar'] = "hello viewvar"; //send var to view
    $this->viewVars['lang'] =$this->lang; //send var to view
   
  }

  public function page2()
  {
    $this->viewVars['myvar'] = "hello page2"; //send var to view
    
  }
}
