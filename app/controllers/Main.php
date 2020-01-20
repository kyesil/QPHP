<?php
class MainC extends Y_Controller {
    
     function init() {

    } 
    public function index() {   
        $this->viewdata['title']="Giriş";   
    }
   
      public function home() {   
        $this->viewdata['title']="home";   
    }
}