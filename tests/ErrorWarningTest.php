<?php

declare(strict_types=1);

use Rancoud\Model\ErrorWarning;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorWarningTest
 */
class ErrorWarningTest extends TestCase
{
    public function testErrorMessage()
    {
        $implem = new ImplementationErrorWarning();
        static::assertSame(false, $implem->hasErrorMessages());
        static::assertSame([], $implem->getErrorMessages());

        $implem->addErrorMessage('error 1');
        static::assertSame(true, $implem->hasErrorMessages());
        static::assertSame(['error 1'], $implem->getErrorMessages());

        $implem->addErrorMessage('error 2');
        static::assertSame(true, $implem->hasErrorMessages());
        static::assertSame(['error 1', 'error 2'], $implem->getErrorMessages());

        $implem->resetErrorMessage();
        static::assertSame(false, $implem->hasErrorMessages());
        static::assertSame([], $implem->getErrorMessages());
    }

    public function testWarningMessage()
    {
        $implem = new ImplementationErrorWarning();
        static::assertSame(false, $implem->hasWarningMessages());
        static::assertSame([], $implem->getWarningMessages());

        $implem->addWarningMessage('warning 1');
        static::assertSame(true, $implem->hasWarningMessages());
        static::assertSame(['warning 1'], $implem->getWarningMessages());

        $implem->addWarningMessage('warning 2');
        static::assertSame(true, $implem->hasWarningMessages());
        static::assertSame(['warning 1', 'warning 2'], $implem->getWarningMessages());

        $implem->resetWarningMessage();
        static::assertSame(false, $implem->hasWarningMessages());
        static::assertSame([], $implem->getWarningMessages());
    }

    public function testErrorField()
    {
        $implem = new ImplementationErrorWarning();
        static::assertSame(false, $implem->hasErrorFields());
        static::assertSame([], $implem->getErrorFields());

        $implem->addErrorField('field 1', 'invalid 1');
        static::assertSame(true, $implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1']], $implem->getErrorFields());

        $implem->addErrorField('field 1', 'invalid 2');
        static::assertSame(true, $implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getErrorFields());

        $implem->addErrorField('field 2', 'invalid 11');
        static::assertSame(true, $implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11']], $implem->getErrorFields());

        $implem->addErrorField('field 2', 'invalid 22');
        static::assertSame(true, $implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11', 'invalid 22']], $implem->getErrorFields());

        $implem->resetErrorField('field 2');
        static::assertSame(true, $implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getErrorFields());

        $implem->resetErrorField();
        static::assertSame(false, $implem->hasErrorFields());
        static::assertSame([], $implem->getErrorFields());
    }

    public function testWarningField()
    {
        $implem = new ImplementationErrorWarning();
        static::assertSame(false, $implem->hasWarningFields());
        static::assertSame([], $implem->getWarningFields());

        $implem->addWarningField('field 1', 'invalid 1');
        static::assertSame(true, $implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1']], $implem->getWarningFields());

        $implem->addWarningField('field 1', 'invalid 2');
        static::assertSame(true, $implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getWarningFields());

        $implem->addWarningField('field 2', 'invalid 11');
        static::assertSame(true, $implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11']], $implem->getWarningFields());

        $implem->addWarningField('field 2', 'invalid 22');
        static::assertSame(true, $implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11', 'invalid 22']], $implem->getWarningFields());

        $implem->resetWarningField('field 2');
        static::assertSame(true, $implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getWarningFields());

        $implem->resetWarningField();
        static::assertSame(false, $implem->hasWarningFields());
        static::assertSame([], $implem->getWarningFields());
    }
}

/**
 * Class ImplementationErrorWarning
 */
class ImplementationErrorWarning extends ErrorWarning
{
    /**
     * @param string $error
     */
    public function addErrorMessage(string $error) : void
    {
        parent::addErrorMessage($error);
    }

    public function resetErrorMessage() : void
    {
        parent::resetErrorMessage();
    }

    /**
     * @param string $warning
     */
    public function addWarningMessage(string $warning) : void
    {
        parent::addWarningMessage($warning);
    }

    public function resetWarningMessage() : void
    {
        parent::resetWarningMessage();
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    public function addErrorField(string $field, string $reasons) : void
    {
        parent::addErrorField($field, $reasons);
    }

    /**
     * @param string|null $field
     */
    public function resetErrorField(string $field = null) : void
    {
        parent::resetErrorField($field);
    }

    /**
     * @param string $field
     * @param string $reasons
     */
    public function addWarningField(string $field, string $reasons)
    {
        parent::addWarningField($field, $reasons);
    }

    /**
     * @param string|null $field
     */
    public function resetWarningField(string $field = null) : void
    {
        parent::resetWarningField($field);
    }

}
