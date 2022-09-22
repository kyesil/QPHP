<?php
function qwput(...$args)
{
   foreach ($args as $arg) {
      if (is_object($arg) || is_array($arg) || is_resource($arg)) {
         $output = print_r($arg, true);
      } else {
         $output = (string) $arg;
      }
      fwrite(fopen('php://stdout', 'w'), $output . "\n");
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
         $url = substr(URI_PATH, strlen(BASE_URL) ); //remove first char
      } else $url = URI_PATH; 
      
      $pathlang=null;
      if (LANG_PATH) {
         $pathlang = substr($url , 1,2); 
         $url = substr($url , 3);  //remove /en
      
      }
      $paths = explode('/', strtolower($url));
      $cont = INDEX_PATH;
      $action = INDEX_PATH;

      if (DEFAULT_CONT != '') {
         $cont = DEFAULT_CONT;
         if (!empty($paths[2]))
            $action = $paths[2];
      } else {
         if (!empty($paths[1]))
            $cont = $paths[1];
         if (!empty($paths[2]))
            $action = $paths[2];
      }
      $contClass = $cont . 'C';
      if (file_exists(C_PATH . $cont  . '.php')) {
         require(C_PATH . $cont . '.php');
         return new $contClass($cont, $action, $contClass, $paths,$pathlang);
      } else
         exit("404: controller file not found: " . C_PATH . $cont . '.php');
   }
}
