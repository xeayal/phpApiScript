<?php
use core\Router as Router;

$app = new Router();

// require_once('product.php');
require_once('merchant.php');
require_once('whatsapp.php');

$app->handleRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);