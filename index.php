<?php
include_once 'autoload.php';
use Core\App;
use Core\DB\Connect;
$rootPath = str_replace($_SERVER["DOCUMENT_ROOT"],'',dirname(__FILE__));
Connect::init('config.php');
$app = new App($rootPath);
$app->start();