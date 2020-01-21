<?php

define ('PATH', __DIR__);
define('APP_PATH', PATH . '/app');

require APP_PATH . '/core/app.php';

$APP = new Y_APP();

$APP->start();

?>
