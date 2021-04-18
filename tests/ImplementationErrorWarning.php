<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\ErrorWarning;

/**
 * Class ImplementationErrorWarning.
 */
class ImplementationErrorWarning extends ErrorWarning
{
    /**
     * @param string $error
     */
    public function addErrorMessage(string $error): void
    {
        parent::addErrorMessage($error);
    }

    public function resetErrorMessage(): void
    {
        parent::resetErrorMessage();
    }

    /**
     * @param string $warning
     */
    public function addWarningMessage(string $warning): void
    {
        parent::addWarningMessage($warning);
    }

    public function resetWarningMessage(): void
    {
        parent::resetWarningMessage();
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    public function addErrorField(string $field, string $reasons): void
    {
        parent::addErrorField($field, $reasons);
    }

    /**
     * @param string|null $field
     */
    public function resetErrorField(string $field = null): void
    {
        parent::resetErrorField($field);
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    public function addWarningField(string $field, string $reasons): void
    {
        parent::addWarningField($field, $reasons);
    }

    /**
     * @param string|null $field
     */
    public function resetWarningField(string $field = null): void
    {
        parent::resetWarningField($field);
    }
}
