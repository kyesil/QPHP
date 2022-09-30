<?php


define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']); // your public folder.
define('QPHP_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

require QPHP_PATH.'/core/app.php';

$APP = new Q_APP();

$APP->start();
