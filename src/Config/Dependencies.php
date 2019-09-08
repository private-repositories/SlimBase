<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : Dependencies.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

use Auth0\SDK\Auth0;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Interop\Container\ContainerInterface;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\NullHandler;
use Monolog\Formatter\LineFormatter;

use OAuth2\Request;
use OAuth2\Response;
use OAuth2\Server;
use OAuth2\Storage\Memory;
use OAuth2\TokenType\Bearer;

use Psr\Http\Message\ServerRequestInterface;

use Slim\Views\PhpRenderer;

use Valitron\Validator;

$container = $app->getContainer();

$container['errorHandler'] = function (ContainerInterface $container) {

    $displayErrorDetails = $container->get('settings')['displayErrorDetails'];

    $errorHandler = new \Slim\Handlers\Error($displayErrorDetails);

    return $errorHandler;
};

$container["logger"] = function (ContainerInterface $container) {

    $logsettings = $container->get('settings')['logger'];

    $logname = $logsettings['name'];
    $logpath = $logsettings['path'];
    $loglevel = $logsettings['level'];

    $logger = new Logger($logname);

    $formatter = new LineFormatter(
        "[%datetime%] [%level_name%]: %message% %context%\n",
        null,
        true,
        true
    );

    $rotating = new RotatingFileHandler($logpath, 0, $loglevel);
    $rotating->setFormatter($formatter);
    $logger->pushHandler($rotating);

    return $logger;
};


$container["validator"] = function (ContainerInterface $container) {

    $validator = new Validator();
    //Add Custom Rules, for lookups - e.g. is a room's type_id valid?
    //For this case, this avoids adding a hard dependency to Room to
    //check against the RoomType lookup model
    //TODO: For performance, the rules can be added on a route name basis.

    return $validator;
};
