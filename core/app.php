<?php

class Y_APP
{



   function __construct()
   {
      define('APP_PATH', PATH . '/app');
      include APP_PATH . '/config.php';
      require_once APP_PATH . '/BootH.php';

      $this->_initSession();

      define("C_PATH", APP_PATH . '/controllers/');
      define("L_PATH", APP_PATH . '/library/');
      define("V_PATH", APP_PATH . '/views/');
      define("M_PATH", APP_PATH . '/modals/');
      set_include_path(L_PATH . PATH_SEPARATOR . M_PATH); // auto loadt libs & modals
      spl_autoload_register();

      define("URI", $_SERVER['REQUEST_URI']);


      require_once PATH . '/core/controller.php';
   }

   public function _initSession()
   {
      $rid = 'q_' . $_SERVER['REMOTE_ADDR'];
      $r = g($rid);      //var_dump($r, $t / $r[1], $r[1]);
      if ($r == null)
         return s($rid, array(time() - 3, 1));
      $t = time() - $r[0];
      $r[1]++;
      if ($t / $r[1] < 0.15 && $r[1] > 100) {
         sleep(3);
         exit('easy');
      }
      if ($r[1] > 5000)
         $r = null; //geçerli sorgudan sonra sıfırlama
      s($rid, $r, 10);
   }

   function start()
   {
      $route = explode('?', URI);
      if (!empty(BASE_PATH)) {
         if ($r = substr($route[0], strlen(BASE_PATH)))  $route[0] = $r;
         else echo "BASE_PATH don't match";
      }
      $route = explode('/', strtolower($route[0]));

      if (Y_AUTOVIEW == false) {
         if (empty($route[1]))
            $route[1] = 'home';
         if (empty($route[2]))
            $route[2] = 'home';
      } else {
         if (empty($route[1]))  $route[2] = "home";
         else
            $route[2] = $route[1];
            
         $route[1] = Y_AUTOVIEW; /// to do : auto render  view with defaults
      }

      var_dump($route);

      $route[0] = $route[1] . 'C';

      if (file_exists(C_PATH . $route[1] . '.php')) {
         require(C_PATH . $route[1] . '.php');
         return new $route[0]($route);
      } else {
      }
   }
}
