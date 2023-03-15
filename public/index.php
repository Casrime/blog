<?php

require '../vendor/autoload.php';

use Framework\HttpFoundation\Request;
use Framework\HttpKernel\Kernel;

$kernel = new Kernel();
$response = $kernel->handle(new Request());
$response->send();
