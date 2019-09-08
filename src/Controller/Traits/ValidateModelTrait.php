<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : ValidateModelTrait.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller\Traits;

trait ValidateModelTrait
{

    protected function validateModel(string $validateFunction = 'validate'): array
    {
        $errors = $this->model->$validateFunction($this->validator);

        if (!empty($errors)) {
            $this->logger->info(
                "Validation errors raised for {$this->model::getClassName()} request.",
                [$this->model->id, $errors]
            );
          //Validation Errors Response - Trait
          //return $this->container->responseFactory::validationErrorsResponse()->setData($errors)->build($response);
        }
    }
}
