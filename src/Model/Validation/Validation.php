<?php declare(strict_types=1);

/**
 * Title           : SlimBase
 * Filename        : Validation.php
 * Description     :
 * Date            : 08/09/19 10:00
 * Author          : dave.gillard
 * Copyright       : 2019 All rights reserved
 */

/**
 * This validation uses https://github.com/vlucas/valitron
 *
 * Validation is performed as follows:
 *
 *  $validator = new Valitron\Validator();
 *  $errors = $object->validate($validator);
 *
 * The errors array will be empty if there are no validation Errors
 *
 */
namespace DavegTheMighty\SlimBase\Model\Validation;

use Valitron\Validator;

trait Validation
{
    /**
     * Check whether the object is valid
     * @return array of errors if not valid
     */
    public function validate(array $data, array $rules, Validator $v) : array
    {
        $v = $v->withData($data);
        if (empty($rules)) {
            throw new \InvalidArgumentException(
                "No validation rules defined for {$this->getClassName()}"
            );
        }
        $v->rules($rules);
        if (!$v->validate()) {
            // Errors
            return $v->errors();
        }
        return [];
    }
}
