<?php
class DefaultC extends Y_Controller
{
  function _init()
  { // initially run this method before actions 
    $this->action = "default";
    $this->view = "default/page.phtml";
  }

  function default()
  { // every page view render from here

    $this->viewdata['vData'] = "hello view data "; //send data to view
  }
   /// this controller can be set autoView from config. See /app/config.php
}
