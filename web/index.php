<?php
///** @var Silex\Application $app */
//$app = require __DIR__."/../app.php";
//$app->run();

use Core\Kernel;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$kernel = new Kernel('prod', false);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);