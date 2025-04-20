<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\ErrorWarning;

/** @internal */
class ImplementationErrorWarning extends ErrorWarning
{
    public function addErrorMessage(string $error): void
    {
        parent::addErrorMessage($error);
    }

    public function resetErrorMessage(): void
    {
        parent::resetErrorMessage();
    }

    public function addWarningMessage(string $warning): void
    {
        parent::addWarningMessage($warning);
    }

    public function resetWarningMessage(): void
    {
        parent::resetWarningMessage();
    }

    public function addErrorField(string $field, string $reasons): void
    {
        parent::addErrorField($field, $reasons);
    }

    public function resetErrorField(?string $field = null): void
    {
        parent::resetErrorField($field);
    }

    public function addWarningField(string $field, string $reasons): void
    {
        parent::addWarningField($field, $reasons);
    }

    public function resetWarningField(?string $field = null): void
    {
        parent::resetWarningField($field);
    }
}
