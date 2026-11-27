<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Resolve application root (supports default sibling or custom cPanel application folder)
$appPath = __DIR__ . '/..';
if (!file_exists($appPath . '/vendor/autoload.php')) {
    if (file_exists(dirname(__DIR__) . '/cityonline/vendor/autoload.php')) {
        $appPath = dirname(__DIR__) . '/cityonline';
    } elseif (file_exists(dirname(__DIR__) . '/laravel/vendor/autoload.php')) {
        $appPath = dirname(__DIR__) . '/laravel';
    }
}

// Auto-bootstrap environment on fresh cPanel deployment (Zero CLI / Terminal needed)
$envPath = $appPath . '/.env';
if (!file_exists($envPath) && file_exists($appPath . '/.env.example')) {
    @copy($appPath . '/.env.example', $envPath);
}

// Auto-ensure required storage directories exist
$storageDirs = [
    $appPath . '/storage/framework/cache/data',
    $appPath . '/storage/framework/sessions',
    $appPath . '/storage/framework/views',
    $appPath . '/storage/logs',
    $appPath . '/storage/app/public',
    $appPath . '/bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
}

// Check if maintenance mode is active
if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader
require $appPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request
$app = require_once $appPath . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
