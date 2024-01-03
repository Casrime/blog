<?php

require '../vendor/autoload.php';

use Framework\HttpFoundation\Request;
use Framework\HttpKernel\Kernel;

session_start();
$kernel = new Kernel();
$response = $kernel->handle(new Request());
$response->send();
