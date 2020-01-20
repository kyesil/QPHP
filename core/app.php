<?php

class Y_APP {

   private $WAY = ["/n/" => "panel/node/",
       "/z/" => "panel/admin/"];

   function __construct() {
      define('APP_PATH', PATH . '/app');
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

   public function _initSession() {
      $rid = 'q_' . $_SERVER['REMOTE_ADDR'];
      $r = g($rid);      //var_dump($r, $t / $r[1], $r[1]);
      if ($r == null)
         return s($rid, array(time() - 3, 1));
      $t = time() - $r[0];
      $r[1] ++;
      if ($t / $r[1] < 0.15 && $r[1] > 100) {
         sleep(3);
         exit('easy');
      }
      if ($r[1] > 5000)
         $r = null; //geçerli sorgudan sonra sıfırlama
      s($rid, $r, 10);
   }

   function start() {
      $route = explode('?', URI);
      if (!empty(BASE_PATH)){
	  if($r = substr($route[0], strlen(BASE_PATH)))  $route[0]=$r;
		else echo "BASE_PATH don't match";
	  }
      $route = explode('/', strtolower($route[0]));

$route[2]=$route[1];
$route[1]="main";
		

      $route[0] = $route[1] . 'C';

      if (file_exists(C_PATH . $route[1] . '.php')) {
         require(C_PATH . $route[1] . '.php');
         return new $route[0]($route);
      } else {
         echo  '404: file not found'. C_PATH . $route[1] . '.php';
         // to do cutom routing
       /*  if (is_array($this->WAY)) {
            foreach ($this->WAY as $k => $v) {
               if (strpos($route, $k) !== false) {
                  $route = $v . substr($route, strlen($k));
                  header("Location: /$route");
                  break;
               }
            }
         }

         header("Location: /info/fn404/?uri=" . URI);*/
      }
   }

}
