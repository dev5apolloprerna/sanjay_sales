<?php

/*
| LIVE index.php for:  /home1/getdemo/public_html/sanjay_sales/index.php
| Laravel project in:  /home1/getdemo/sanjay_sales
*/

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Laravel project folder (from public_html/sanjay_sales go up 2 levels, then into sanjay_sales)
$laravel = __DIR__.'/../../sanjay_sales';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravel.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravel.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $laravel.'/bootstrap/app.php';

// Tell Laravel that THIS folder (public_html/sanjay_sales) is its public folder,
// so public_path('uploads') = /home1/getdemo/public_html/sanjay_sales/uploads
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
