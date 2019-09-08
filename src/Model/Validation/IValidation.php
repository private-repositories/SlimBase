<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : IValidation.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Model\Validation;

use Valitron\Validator;

interface IValidation
{
    public function validate(Validator $v) : array;

    public function getData(): array;
    public function getRules(): array;
}
