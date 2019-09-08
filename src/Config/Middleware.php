<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : Middleware.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

use Interop\Container\ContainerInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Tuupola\Middleware\CorsMiddleware;

$container = $app->getContainer();

$container["CorsMiddleware"] = function (ContainerInterface $container) {
    //My take on CORS:
    //User A puts a request to website B.
    //Website B returns lots of resources to render the page, including some JS.
    //That JS includes a xhttp request to server C.
    //So, user A’s computer goes hmm, that might be dodge, and asks server C in a pre-flight request,
    //if it is legit for website B to ask for resources from it.
    //The CORS set up on website C will say yah or nay based on the request,
    //and it is for User A’s browser to determine whether to proceed, or prevent the request.
    //
    //So, we accept from all for now, as we are a public resource.
    //This may change if we change to an AWS environment.
    //But even then, this shall not restrict access.
    //
    //As we are not expecting cors, we might want to avoid the logs info level flooding with a line per request.
    return new CorsMiddleware([
        "logger" => $container["logger"],
        "origin" => ["*"],
        //Not sure if this needs to be the full list, and it will reject if others are sent
        "headers.allow" => ["Authorization", "Content-Type"],
        "methods" => ["GET", "POST", "PATCH", "DELETE", "HEAD"],
        "credentials" => true,
        "cache" => 86400,
    ]);
};


/**
 * NOTE: the order in which the middleware is added has an impact on the order it runs.
 * It should be last added, first run.
 */
$app->add("CorsMiddleware");
