<?php
function qwput(...$args)
{
  foreach ($args as $arg) {
    if (is_object($arg) || is_array($arg) || is_resource($arg)) {
      $output = print_r($arg, true);
    } else {
      $output = (string) $arg;
    }
    fwrite(fopen('php://stdout', 'w'), $output."\n");
  }
}
class Q_APP
{
   function __construct()
   {
      include APP_PATH . '/config.php';

      define("C_PATH", APP_PATH . '/controllers/');
      define("L_PATH", APP_PATH . '/library/');
      define("V_PATH", APP_PATH . '/views/');
      define("M_PATH", APP_PATH . '/models/');
      set_include_path(L_PATH . PATH_SEPARATOR . M_PATH); // auto loadt libs & modals
      spl_autoload_register();

      define("URI", $_SERVER['REQUEST_URI']);
      define("URI_PATH", parse_url(URI, PHP_URL_PATH));

      require_once APP_PATH . '/core/controller.php';
   }

   function start()
   {
      if (!empty(BASE_URL)) {
         $up = substr(URI_PATH, strlen(BASE_URL)+1);//remove first char
      } else $up = URI_PATH;
      $route = explode('/', strtolower($up));

      if (DEFAULT_CONT == false) {
         if (empty($route[1]))
            $route[1] = 'home';
         if (empty($route[2]))
            $route[2] = 'home';
      } else {
         if (empty($route[1]))  $route[2] = "home";
         else
            $route[2] = $route[1];
         $route[1] = DEFAULT_CONT;
      }
      $route[0] = $route[1] . 'C';

      if (file_exists(C_PATH . $route[1] . '.php')) {
         require(C_PATH . $route[1] . '.php');
         return new $route[0]($route);
      } else
         exit("404: controller file not found: " . C_PATH . $route[1] . '.php');
   }
}


