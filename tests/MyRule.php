<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\CustomRule;
use Rancoud\Model\FieldException;

/**
 * @internal
 */
class MyRule extends CustomRule
{
    /**
     * @throws FieldException
     */
    public function applyRule($value)
    {
        if ($value === 'azerty') {
            throw new FieldException('invalid azerty value');
        }

        return $value;
    }
}
