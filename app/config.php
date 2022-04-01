<?php
define('BASE_URL', ''); // it just delete as many chars as BASE_URL length from start.
define('DEFAULT_CONT', ''); // default controller preselect for single controller usage. for example /page1, /page2 ... 

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_DB', 'test');
define('DB_PORT', '3306');

define('DEV_MODE', '1'); 



ini_set('display_errors', DEV_MODE);
ini_set('display_startup_errors', DEV_MODE);
error_reporting(DEV_MODE?(E_ALL):(E_ALL & ~E_DEPRECATED & ~E_STRICT));