<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// When the app lives in a subdirectory (e.g. /Aborlan_municipality) but is accessed
// without /public in the URL, align REQUEST_URI with the front controller path.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$publicPath = rtrim(dirname($scriptName), '/');
$installPath = rtrim(dirname($publicPath), '/');

if ($installPath !== '' && $installPath !== '/') {
    $path = parse_url($requestUri, PHP_URL_PATH) ?? '/';

    if (str_starts_with($path, $installPath) && ! str_starts_with($path, $publicPath)) {
        $suffix = substr($path, strlen($installPath)) ?: '/';
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $_SERVER['REQUEST_URI'] = $publicPath.$suffix.($query ? '?'.$query : '');
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
