<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : FIllModelTrait.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Controller\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait FillModelTrait
{
    protected function fillModel(ServerRequestInterface $request): void
    {
        $body = $request->getParsedBody();
        //Fill Object Trait
        $this->model->fill($body);
    }
}
