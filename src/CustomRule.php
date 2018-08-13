<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Class CustomRule.
 */
abstract class CustomRule
{
    /**
     * @param $value
     *
     * @return mixed
     */
    abstract public function applyRule($value);
}
