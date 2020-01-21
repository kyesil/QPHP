<?php
class DefaultC extends Y_Controller
{//this controller can be use with default look:controller in app/config.php
  function _init()
  { // initially run this method before actions 
    $this->action = "default";
    $this->view = "default/page.phtml";
  }

  function default()
  { // every page view render from here

    $this->viewdata['vData'] = "hello view data "; //send data to view
  }
}
