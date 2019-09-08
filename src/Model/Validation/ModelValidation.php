<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : ModelValidation.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

namespace DavegTheMighty\SlimBase\Model\Validation;

use Valitron\Validator;

trait ModelValidation
{
    use Validation {
        validate as protected baseValidate;
    }
    /**
     * Check whether the object is valid
     * @return array of errors if not valid
     */
    public function validate(Validator $v) : array
    {
        return $this->baseValidate($this->getData(), $this->getRules(), $v);
    }

    public function getData() : array
    {
        return $this->attributes;
    }

    public function getRules() : array
    {
        return $this->rules;
    }
}
