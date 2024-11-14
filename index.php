<?php
require_once 'backend/config.php';
require_once 'backend/autoload.php';

$router = require_once 'backend/routes.php';
$router->run();
