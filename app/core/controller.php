<?php

abstract class Q_Controller
{

   public $viewVars = [];
   public $autoRender = true;
   public $view;
   public $action;
   public $controller;
   public $class;

   function __construct($param)
   {
      $this->controller = $param[1];
      $this->class = $param[0];

      $this->action = $param[2];
      $this->router();
   }

   private function router()
   {
      if (method_exists($this, '_init'))
         call_user_func_array(array($this, '_init'), []);
      if (class_exists($this->class)) {

         if (method_exists($this, $this->action)) {
            call_user_func_array(array($this, $this->action), []);
         } else
            $this->exit("404: method not found: " . $this->class . '>' . $this->action, 404);
      } else
         $this->exit("404: class not found: " . $this->class, 404);
   }

   public function renderview($view = null, $vars = null)
   {
      if ($view)
         $this->view = $view . '.phtml';
      else
         $this->view = $this->controller . '/' . $this->action . '.phtml';

      if ($vars)
         $this->viewVars = $vars;

      if (!file_exists(V_PATH . $this->view))  $this->exit("404: view not found: " . $this->view, 404);
      if (is_array($this->viewVars))
         extract($this->viewVars);
      require(V_PATH  . $this->view);
   }

   public function apiEnable()
   {
      $this->autoRender = false; // disable view render for api
      header("Access-Control-Allow-Origin: *");
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      header('Content-Type: application/json');
   }
   public function exit($msg, $code = 200)
   {
      http_response_code($code);
      exit($msg);
   }
}
