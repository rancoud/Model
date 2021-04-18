<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\CustomRule;
use Rancoud\Model\FieldException;

class MyRule extends CustomRule
{
    /**
     * @param $value
     *
     * @throws FieldException
     *
     * @return mixed
     */
    public function applyRule($value)
    {
        if ($value === 'azerty') {
            throw new FieldException('invalid azerty value');
        }

        return $value;
    }
}
