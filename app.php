<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : app.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

date_default_timezone_set("UTC");

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->overload();

// Instantiate the app
// Composer Autoloader does not work for functions/constants, so rather than
// wrapping the settings in an arbitrary class, we use a require statement.
$applicationsettings = require __DIR__ . '/src/Config/Settings.php';

// To be correctly placed in the app settings, the sub array is required
if ($applicationsettings['settings']['displayErrorDetails']) {
    error_reporting(E_ALL);
    ini_set("display_errors", "true");
}

$app = new \Slim\App($applicationsettings);

require __DIR__ . "/src/Config/Dependencies.php";
require __DIR__ . "/src/Config/Middleware.php";
require __DIR__ . "/src/Config/Loader.php";

//TODO: Define Routes

$app->run();
