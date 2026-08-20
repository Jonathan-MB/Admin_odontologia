<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// La raiz de Laravel cambia segun donde este montado el sitio:
//   - Local   : public/ vive dentro del proyecto        -> ../
//   - Hostinger: public_html/ y laravel/ son hermanos   -> ../laravel/
// Se detecta sola, asi el mismo archivo sirve en los dos lados.
$raiz = is_dir(__DIR__ . '/../vendor')
    ? __DIR__ . '/..'
    : __DIR__ . '/../laravel';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $raiz . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $raiz . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $raiz . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
