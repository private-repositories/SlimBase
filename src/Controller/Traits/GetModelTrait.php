<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : GetModelTrait.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

trait GetModelTrait
{

  /**
   * Sets and returns based on the controller namespace
   * @return [object] An instantiated model
   */
    protected function getModelFromController(ServerRequestInterface $request): object
    {
        $name = static::class;
        //Returns: Define the name of the class from the controller
        //Replace the last controller
        $name = substr($name, 0, -10);
        //Switch the Path Controller with Model
        $name = str_replace("Controller", "Model", $name);
        if (!\class_exists($name)) {
            throw new \RuntimeException("Controller Get Model cannot find model for {$name}", 1);
        }
        $this->model = new $name;

        return $this->model;
    }


    protected function getModel(ServerRequestInterface $request): void
    {
        try {
            //Get Object Trait
            $resource = $this->getModelFromController();
            $this->class_name = $resource->getClassName(false);

            $this->model = $resource::findFromRequest($request);
        } catch (ModelNotFoundException $ex) {
            $uri = $request->getUri()->getPath();
            $message = "A {$this->class_name} with was not found for patch request to {$uri}.";
            $this->logger->notice("Get request failed with not found. {$message}");
            //Not found Response - Trait
            //return $this->container->responseFactory::notFoundResponse()->build($response);
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
