<?php
class AdminC extends Q_Controller
{
  function _init()
  {     
    $this->viewVars['lang'] =$this->lang; //send var to view
    $this->viewVars['page'] =$this->action; //send var to view
    $this->viewVars['sessid']= sessH::check(10); //basic auth check  sessH.php

  }
  public function home()
  {
  }

  public function visitors()
  {
  }
  public function sponsors()
  {
  }

}
