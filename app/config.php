<?php
define('BASE_URL', ''); // it just delete as many chars as BASE_URL length from the start. for example /blog:  path rendering igrore /blog path
define('DEFAULT_CONT', ''); // default controller preselect for single controller usage. for example actions: /page1, /page2 ...  with same controller 
define('LANG_PATH', false); //  if path like : /en/home/page2 > it's ignore first path 2 chars (/en/) 

define('INDEX_PATH', 'home');
define('LANG_FOLDER',PATH. '/langs/');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DB', 'test');
define('DB_PORT', '3306');

define('DEV_MODE', '1'); 



ini_set('display_errors', DEV_MODE);
ini_set('display_startup_errors', DEV_MODE);
error_reporting(DEV_MODE?(E_ALL):(E_ALL & ~E_DEPRECATED & ~E_STRICT));