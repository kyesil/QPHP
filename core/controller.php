<?php

abstract class Y_Controller {

   public $route = [];
   public $viewdata = [];
   public $autoRender = true;

   function __construct($param) {
      $this->route = $param;
      $this->router();
   }

   private function router() {
      if (method_exists($this, 'init'))
         call_user_func_array(array($this, 'init'), []);
      if (class_exists($this->route[0])) {

         if (method_exists($this, $this->route[2])) {
            call_user_func_array(array($this, $this->route[2]), []);
            if ($this->autoRender)
               $this->view($this->route[1] . '/' . $this->route[2], $this->viewdata);
         } else
            echo "404: method not found " . $this->route[0] . '>' . $this->route[2];
      } else
         echo "404: class not found " . $this->route[0];
   }

   function view($path, $data) {
      if (is_array($data))
         extract($data);
      require(V_PATH  . $path . '.phtml');
   }

}
