<?php
//this file and parameters can be  required don't  remove or comment variables

define('BASE_URL', ''); // it just delete as many chars as BASE_URL length from the start. for example /blog:  path rendering igrore /blog path
define('DEFAULT_CONT', ''); // default controller preselect for  controller. for example actions: /page1, /page2 ...  with same controller  and if missing action run name of controller method
define('AUTO_RENDER_VIEW', true); // auto render phtml  file in  views folder which matc controller/action.phtml
define('INDEX_PATH', 'home'); // if missing controller or action in url  it select home/home action and view

define('LANG_MODE', ''); // false for disable,   if enabled: /en/home/page2 > it's ignore first path 2 chars (/en/), "json" for load key value from /langs/*.json file, "php" for import keyvalue from php file, "view" for render pages from /views/en/*, dont forget enable from _main.php
define('LANG_DEFAULT', false); // redirect if its set like /en/ , for browser lang :  substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
define('LANG_FOLDER',ROOT_PATH. '/langs/');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DB', 'test');
define('DB_PORT', '3306');

define('DEV_MODE', '1'); 



ini_set('display_errors', DEV_MODE);
ini_set('display_startup_errors', DEV_MODE);
error_reporting(DEV_MODE?(E_ALL):(E_ALL & ~E_DEPRECATED & ~E_STRICT));