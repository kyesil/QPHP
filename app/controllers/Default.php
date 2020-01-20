<?php
class DefaultC extends Y_Controller
{
  function init()
  { // initially run this method before actions 
  }

  function home()
  { // every page view render from here

    $this->viewdata['TITLE'] = "Defaulthome";
  }
   /// this controller can be set autoView from config. See /app/config.php
}
