<?php
class HomeC extends Y_Controller
{
  public $autoView = false;

  function init()
  { // initially run this method before actions 
  }
  public function home()
  {
    $this->viewdata['TITLE'] = "HOME";
  }

  public function pages()
  {
    $this->viewdata['TITLE'] = "Page";
  }
}
