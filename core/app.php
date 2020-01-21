<?php

class Y_APP
{



   function __construct()
   {
      define('APP_PATH', PATH . '/app');
      include APP_PATH . '/config.php';
      require_once APP_PATH . '/BootH.php';



      define("C_PATH", APP_PATH . '/controllers/');
      define("L_PATH", APP_PATH . '/library/');
      define("V_PATH", APP_PATH . '/views/');
      define("M_PATH", APP_PATH . '/modals/');
      set_include_path(L_PATH . PATH_SEPARATOR . M_PATH); // auto loadt libs & modals
      spl_autoload_register();

      define("URI", $_SERVER['REQUEST_URI']);
      define("URI_PATH", parse_url(URI, PHP_URL_PATH));

      require_once PATH . '/core/controller.php';
   }



   function start()
   {
      if (!empty(BASE_PATH)) {
         $up = substr(URI_PATH, strlen(BASE_PATH));
      } else $up = URI_PATH;
      $route = explode('/', strtolower($up));

      if (Y_DCONT == false) {
         if (empty($route[1]))
            $route[1] = 'home';
         if (empty($route[2]))
            $route[2] = 'home';
      } else {
         if (empty($route[1]))  $route[2] = "home";
         else
            $route[2] = $route[1];
         $route[1] = Y_DCONT;
      }
      $route[0] = $route[1] . 'C';

      if (file_exists(C_PATH . $route[1] . '.php')) {
         require(C_PATH . $route[1] . '.php');
         return new $route[0]($route);
      } else {
      }
   }
}
