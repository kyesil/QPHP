<?php

abstract class Q_Controller
{

   public $viewVars = [];
   public $autoRender = AUTO_RENDER_VIEW;
   public $view;
   public $action;
   public $controller;
   public $class;
   public $paths;
   public $lang;
   public $apiMode = false;

   function __construct($cont, $action, $class, $paths, $pathlang)
   {
      $this->controller = $cont;
      $this->class = $class;
      $this->action = $action;
      $this->paths = $paths;
      $this->lang = $pathlang;

      try {
         $this->router();
      } catch (\Throwable $th) {
         $this->err($th, 500);
      }
   }

   private function router()
   {
      if (file_exists(C_PATH . '_main.php')) {

         include(C_PATH . '_main.php');
         if (function_exists('_main'))
            call_user_func_array('_main', [$this]);
      }

      if (method_exists($this, '_init'))
         call_user_func_array(array($this, '_init'), []);
      if (!class_exists($this->class))  return $this->err("404: class not found: " . $this->class, 404);
      if (method_exists($this,  $this->action)) {
         $action = $this->action;
      } else if (method_exists($this,  DEFAULT_CONT)) {
         $action = DEFAULT_CONT;
      } else  return $this->err("404: method not found: " . $this->class . '>' . $this->action, 404);

      call_user_func_array(array($this, $action), []);

      if ($this->autoRender) $this->renderview($this->controller . '/' . $this->action);
   }

   public function renderview($view = null, $vars = null)
   {
      $this->view = $view . '.phtml';

      if ($vars)
         $this->viewVars = $vars;

      if (!file_exists(V_PATH . $this->view))  return $this->err("404: view not found: " . $this->view, 404);
      if (is_array($this->viewVars))
         extract($this->viewVars);
      require(V_PATH  . $this->view);
   }

   public function apiEnable()
   {
      $this->apiMode = true;
      $this->autoRender = false; // disable view render for api
      header("Access-Control-Allow-Origin: *");
      header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      header('Content-Type: application/json');
   }
   public function err($msg, $code = 404)
   {
      Q_APP::error($code, $msg, $this->apiMode);
   }
}
