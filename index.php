<?php

define ('PATH', __DIR__);
define ('BASE_PATH', ''); // sub dir

require PATH . '/core/app.php';

$APP = new Y_APP();

$APP->start();

?>
