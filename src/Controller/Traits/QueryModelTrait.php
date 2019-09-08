<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : QueryModelTrait.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

trait QueryModelTrait
{
    protected function queryModel(ServerRequestInterface $request): void
    {
        try {
            $resource = $this->getSetModel();
            $this->class_name = $resource->getClassName(false);

            //Find Object Trait
            $params = $request->getQueryParams();
            $objects = $resource::where($params)->get();

            if (!$objects) {
                $this->logger->notice(
                    "Get All {$class_name} returned no objects for supplied params ".print_r($params, true)
                );
              //Not found Response - Trait
              //return $this->container->responseFactory::notFoundResponse()->build($response);
            }
            return $objects;
        } catch (\RuntimeException $runtimeException) {
            $this->logger->error(
                "Request failed due to Runtime exception.",
                [$runtimeException]
            );
            //Runtime Exception Response - Trait
            //return $this->container->responseFactory::runtimeExceptionResponse()->build($response);
        }
    }
}
