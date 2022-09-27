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
      else throw new Exception("qphp config file not found in : $configPath. You can find one here : https://github.com/kyesil/QPHP/tree/master/public/app/");

      $routePath = APP_PATH . '/qroutes.php';
      if (is_file($routePath)) include $routePath;

      define("C_PATH", APP_PATH . '/controllers/');
      define("L_PATH", QPHP_PATH . '/library/');
      define("V_PATH", APP_PATH . '/views/');
      define("M_PATH", APP_PATH . '/models/');
      $autoIncludes = L_PATH;
      if (is_dir(M_PATH))
         $autoIncludes .= PATH_SEPARATOR . M_PATH;
      set_include_path($autoIncludes);
      spl_autoload_register();

      define("URI", $_SERVER['REQUEST_URI']);
      define("URI_PATH", parse_url(URI, PHP_URL_PATH));

      require_once QPHP_PATH . '/core/controller.php';
   }

   function start()
   {
      if (!empty(BASE_URL)) {
         $url = substr(URI_PATH, strlen(BASE_URL)); //remove first char
      } else $url = URI_PATH;

      $urllang = null;
      if (LANG_FROM_URL) {
         $urllang = escapeshellcmd(substr($url, 1, 2));
         $url = substr($url, 3);  //remove /en
      }
      $paths = explode('/', strtolower($url));
      $cont = INDEX_PATH;
      $action = INDEX_PATH;


      $routeResult = $this->checkRoute($url);
      if ($routeResult && count($routeResult) >= 2)
         list($cont, $action) = $routeResult;
      elseif (DEFAULT_CONT != '') {
         $cont = DEFAULT_CONT;
         if (!empty($paths[1]))
            $action = $paths[1];
      } else {
         if (!empty($paths[1]))
            $cont = $paths[1];
         if (!empty($paths[2]))
            $action = $paths[2];
      }
      $contClass = $cont . 'C';
      if (file_exists(C_PATH . $cont  . '.php')) {
         require(C_PATH . $cont . '.php');
         return new $contClass($cont, $action, $contClass, $paths, $urllang);
      } else
         Q_APP::error(404, "404: controller file not found: " . C_PATH . $cont . '.php');
   }

   function checkRoute($url)
   {
      if (!defined("QROUTES"))  return null;

      foreach (QROUTES as $key => $value) {
         if (str_starts_with($url, $key))  //todo regex 
            return $value;
      }
      return null;
   }
   public static  function error($code, $msg, $raw = false)
   {
      http_response_code($code);
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
