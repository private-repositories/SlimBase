<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : GenericModelController.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Interop\Container\ContainerInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

//Traits
//phpcs:ignore PSR2.Namespaces.UseDeclaration.MultipleDeclarations
use DavegTheMighty\SlimBase\Controller\Traits {
    FillModelTrait,
    GetModelTrait,
    NewModelTrait,
    QueryModelTrait,
    SaveModelTrait,
    ValidateModelTrait
};

/**
 * Defines the standard Crud routes for a controller/model
 */
class GenericModelController
{
    use FillModelTrait;
    use GetModelTrait;
    use NewModelTrait;
    use QueryModelTrait;
    use SaveModelTrait;
    use ValidateModelTrait;

    /**
     * @var ContainerInterface
     */
    protected $container;
    protected $logger;
    protected $validator;
    protected $model;
    protected $class_name;

    /**
     * GenericModelController constructor.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->logger;
        $this->validator = $container->validator;
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function get(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $this->getModel();
        return $response
            ->withStatus(201)
            ->write(json_encode($this->model, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function getAll(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $objects = $this->queryModel($request);
        return $response
            ->withStatus(200)
            ->write(json_encode($objects, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function post(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $this->newModel();
        $this->fillModel();
        $this->validateModel();
        $this->saveModel();

        $this->logger->info("Created New {$this->model::getClassName()}", [$this->model->id]);

        return $response
            ->withStatus(201)
            ->withHeader(
                "Content-Location",
                $this->model->getLocation()
            );
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     * @throws \RuntimeException
     */
    public function put(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        //Delegate to Post/Patch
        try {
            //TODO: There is overhead in calling this twice, but can refactor
            $this->getModel();
        } catch (\ModelNotFoundException $e) {
            return $this->post($request, $response, $args);
        }

        return $this->patch($request, $response, $args);
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function patch(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        $this->getModel();
        $this->logger->info("Updating {$this->class_name}", [$this->model->id]);
        $this->fillModel();
        $this->validateModel();
        $this->saveModel();

        $this->logger->info("Updated {$this->model::getClassName()}", [$this->model->id]);

        $response = $response->withHeader($key, $value);

        return $response
            ->withStatus(200)
            ->withHeader(
                "Content-Location",
                $this->model->getLocation()
            );
    }

    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function delete(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
          $this->getModel();

          $this->logger->info("Deleting {$this->class_name}", [$this->model->id]);
          $this->validateModel('validateDelete');

          $object->delete();
          $this->logger->info("Deleted {$this->class_name}", [$this->model->id]);

          return $response->withStatus(200);
    }
}
