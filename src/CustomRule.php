<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Class CustomRule.
 */
abstract class CustomRule
{
    abstract public function applyRule($value);
}
