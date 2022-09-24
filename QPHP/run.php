<?php


define('PATH', $_SERVER['DOCUMENT_ROOT']);
define('QPHP_PATH', __DIR__);
define('APP_PATH', PATH . '/app');

require QPHP_PATH.'/core/app.php';

$APP = new Q_APP();

$APP->start();
