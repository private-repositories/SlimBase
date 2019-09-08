<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : SaveModelTrait.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller\Traits;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException as QE;

trait SaveModelTrait
{
    protected function saveModel(): void
    {
        $object = $this->model;
        try {
            DB::transaction(function () use ($object) {
                $object->save();
            }, 3);
        } catch (QE $queryException) {
            $this->logger->error(
                "Database operation on ".$object::getClassName()." failed with db query errors.",
                [$object->id, $queryException]
            );
            //Query Exception Response - Trait
            //return $this->container->responseFactory::queryExceptionResponse()->build($response);
        } catch (\PDOException $pdoException) {
            $this->logger->error(
                "Unable to complete database operation on ".$object::getClassName().".",
                [$object->id, $pdoException]
            );
            //Database Exception Error - Trait
            //return $this->container->responseFactory::databaseExceptionResponse()->build($response);
        }
    }
}
