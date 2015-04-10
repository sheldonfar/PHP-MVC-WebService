<?php

define('ROOT_PATH', realpath(dirname(__FILE__)));
define('LIB_PATH', ROOT_PATH . '/lib');
define('APPLICATION_PATH', ROOT_PATH . '/application');

require LIB_PATH . '/Router.php';
Router::run();
?>