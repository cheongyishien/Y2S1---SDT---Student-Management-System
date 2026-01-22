<?php
session_start();

// Define App Root
define('APP_ROOT', dirname(dirname(__FILE__)) . '/app');

require_once '../app/config/database.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';

$app = new App();
