<?php
class Sub_HomeC extends Q_Controller
{
  function _init()
  { 

  }
  public function home()
  {
    $this->viewVars['myvar'] = "my data"; //send var to view
   
  }

  public function page2()
  {
    $this->viewVars['myvar'] = "my data"; //send var to view
    
  }
}
