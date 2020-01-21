<?php

abstract class Y_Controller
{

   public $viewdata = [];
   public $autoRender = true;
   public $view;
   public $action;
   public $controller;
   public $class;

   function __construct($param)
   {
      $this->controller = $param[1];
      $this->class = $param[0];
      $this->view = $param[1] . '/' . $param[2].'.phtml';
      $this->action =$param[2];
      $this->router();
   }

   private function router()
   {
      if (method_exists($this, '_init'))
         call_user_func_array(array($this, '_init'), []);
      if (class_exists($this->class)) {

         if (method_exists($this, $this->action)) {
            call_user_func_array(array($this, $this->action), []);
            if ($this->autoRender)
               $this->renderview($this->view, $this->viewdata);

         } else
            exit("404: method not found: " . $this->class . '>' .$this->action);
      } else
      exit("404: class not found: " . $this->class);
   }

   public function renderview($path, $data)
   { 
      if(!file_exists(V_PATH.$this->view ))  exit("404: view not found: " . $this->view );
      if (is_array($data))
         extract($data);
      require(V_PATH  . $path );
   }
}
