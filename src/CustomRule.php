<?php

declare(strict_types=1);

namespace Rancoud\Model;

abstract class CustomRule
{
    abstract public function applyRule(mixed $value);
}
