<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

define('APP_START', microtime());
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
session_start();

include 'autoloader.php';

require_once 'start/Config.php';
require_once '3thParty/index.php';
require_once 'helpers/global_helper.php';
require_once 'routes/index.php';