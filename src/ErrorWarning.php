<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Class ErrorWarning.
 */
class ErrorWarning
{
    protected array $errorMessage = [];
    protected array $errorFields = [];
    protected array $warningMessage = [];
    protected array $warningFields = [];

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessage;
    }

    /**
     * @return bool
     */
    public function hasErrorMessages(): bool
    {
        return \count($this->errorMessage) > 0;
    }

    /**
     * @param string $error
     */
    protected function addErrorMessage(string $error): void
    {
        $this->errorMessage[] = $error;
    }

    protected function resetErrorMessage(): void
    {
        $this->errorMessage = [];
    }

    /**
     * @return array
     */
    public function getErrorFields(): array
    {
        return $this->errorFields;
    }

    /**
     * @return bool
     */
    public function hasErrorFields(): bool
    {
        return \count($this->errorFields) > 0;
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    protected function addErrorField(string $field, string $reasons): void
    {
        if (!isset($this->errorFields[$field])) {
            $this->errorFields[$field] = [];
        }

        $this->errorFields[$field][] = $reasons;
    }

    /**
     * @param string|null $field
     */
    protected function resetErrorField(?string $field = null): void
    {
        if ($field === null) {
            $this->errorFields = [];

            return;
        }

        if (\array_key_exists($field, $this->errorFields)) {
            unset($this->errorFields[$field]);
        }
    }

    /**
     * @return array
     */
    public function getWarningMessages(): array
    {
        return $this->warningMessage;
    }

    /**
     * @return bool
     */
    public function hasWarningMessages(): bool
    {
        return \count($this->warningMessage) > 0;
    }

    /**
     * @param string $warning
     */
    protected function addWarningMessage(string $warning): void
    {
        $this->warningMessage[] = $warning;
    }

    protected function resetWarningMessage(): void
    {
        $this->warningMessage = [];
    }

    /**
     * @return array
     */
    public function getWarningFields(): array
    {
        return $this->warningFields;
    }

    /**
     * @return bool
     */
    public function hasWarningFields(): bool
    {
        return \count($this->warningFields) > 0;
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    protected function addWarningField(string $field, string $reasons): void
    {
        if (!isset($this->warningFields[$field])) {
            $this->warningFields[$field] = [];
        }

        $this->warningFields[$field][] = $reasons;
    }

    /**
     * @param string|null $field
     */
    protected function resetWarningField(?string $field = null): void
    {
        if ($field === null) {
            $this->warningFields = [];

            return;
        }

        if (\array_key_exists($field, $this->warningFields)) {
            unset($this->warningFields[$field]);
        }
    }

    protected function resetAllErrors(): void
    {
        $this->resetErrorField();
        $this->resetErrorMessage();
    }
}
