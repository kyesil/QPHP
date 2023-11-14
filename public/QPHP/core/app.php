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
      $configPath = APP_PATH . '/qconfig.php';
      if (is_file($configPath)) require $configPath;
      else throw new Exception("qphp config file not found in : $configPath.");

      $routesPath = APP_PATH . '/qroutes.php';
      if (is_file($routesPath)) include $routesPath;

      define('C_PATH', APP_PATH . '/controllers/');
      define('QL_PATH', QPHP_PATH . '/library/');
      define('L_PATH', APP_PATH . '/library/');
      define('M_PATH', APP_PATH . '/models/');

      Q_APP::importDir(QL_PATH);
      Q_APP::importDir(L_PATH);
      Q_APP::importDir(M_PATH);

      define('URI', $_SERVER['REQUEST_URI']);
      define('URI_PATH', parse_url(URI, PHP_URL_PATH));

      require_once QPHP_PATH . '/core/controller.php';
   }

   function start()
   {

      if (!empty(BASE_URL)) {
         $url = substr(URI_PATH, strlen(BASE_URL)); //remove first char
      } else $url = URI_PATH;

      $urllang = null;
      $viewpath = APP_PATH . '/views/';
      $paths = explode('/', $url);

      if (LANG_MODE) {
         if (strlen($paths[1]) === 0 && !empty(LANG_DEFAULT)) { //if home url redirect to default lang
            header('Location: ./' . LANG_DEFAULT . '/');
            exit('301');
         } elseif (strlen($paths[1]) === 2) {// if length 2 it's lang else go ehead
            $urllang = Q_APP::escapeDir($paths[1]);
            $paths = array_slice($paths, 1); //remove first element
            if (LANG_MODE === 'view') {
               $viewpath = APP_PATH . "/views/$urllang/";
               if (!is_dir($viewpath) && !empty(LANG_DEFAULT)) $viewpath = APP_PATH . '/views/' . LANG_DEFAULT . '/';
            }
         }
      }
      
      define('V_PATH', $viewpath);
      $cont = INDEX_PATH;
      $action = INDEX_PATH;

      $routeResult = $this->checkRoute($url);
      if ($routeResult && count($routeResult) >= 2)
         list($cont, $action) = $routeResult;
      elseif (!empty(DEFAULT_CONT)) {
         $cont = DEFAULT_CONT;
         if (!empty($paths[2])) {
            $cont = $paths[1];
            $action = $paths[2];
         } elseif (!empty($paths[1]))
            $action = $paths[1];
      } else {

         if (!empty($paths[3]) && is_dir(C_PATH . $paths[1])) { // sub controller
            $paths[1] = $paths[1] . '/' . $paths[2];
            $paths[2] = $paths[3];
         }

         if (!empty($paths[1]))
            $cont = $paths[1];
         if (!empty($paths[2]))
            $action = $paths[2];
      }

      $contClass = str_replace('/', '_', $cont) . 'C';
      if (file_exists(C_PATH . $cont  . '.php')) {
         require(C_PATH . $cont . '.php');
         return new $contClass($cont, $action, $contClass, $paths, $urllang);
      } else
         Q_APP::error(404, '404: controller file not found: ' . C_PATH . $cont . '.php');
   }

   function checkRoute($url)
   {
      if (!defined('ROUTE_LIST'))  return null;

      foreach (ROUTE_LIST as $key => $value) {
         if (strpos($url, $key) === 0)
            return $value;
      }
      return null;
   }
   static function importDir($dir) // set_include_path not working on hosting
   {
      if (!is_dir($dir)) return false;
      $files = glob($dir . '*.php');
      foreach ($files as $key => $value) {
         include  $value;
      }
   }
   public static function escapeDir($str)
   {
      return preg_replace("/[^a-zA-Z0-9]+/", '', $str);
   }

   public static  function error($code, $msg, $viewVars = null, $raw = false)
   {
      http_response_code($code);
      if (is_array($viewVars))
         extract($viewVars);
      $errorView = V_PATH . 'error/error.phtml';
      if (!$raw && file_exists($errorView)) {
         extract([
            'errCode' => $code,
            'errMsg' => $msg
         ]);
         require($errorView);
         exit();
      } else {
         exit('<pre>' . $msg . '</pre> ');
      }
   }
}
