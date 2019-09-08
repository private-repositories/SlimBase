<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : Settings.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

return [
    //NOTE: The settings sub array is required for correct placement in the slim app when instantiated
    'settings' => [
        'addContentLengthHeader'            => false,
        'displayErrorDetails'               => filter_var(getenv('DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN),
        'determineRouteBeforeAppMiddleware' => true,
        'service_environment' => getenv('SERVICE_ENVIRONMENT'),
        // Logging settings
        'logger' => [
            'name' => getenv('LOG_NAME') ?: 'SlimBase',
            'path' => getenv('LOG_PATH') ?: __DIR__ . '/../../var/logs/SlimBase.log',
            'level' => getenv('LOG_LEVEL') ?: \Psr\Log\LogLevel::DEBUG,
        ],
    ]
];
