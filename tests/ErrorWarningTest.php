<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;

/**
 * Class ErrorWarningTest.
 */
class ErrorWarningTest extends TestCase
{
    public function testErrorMessage(): void
    {
        $implem = new ImplementationErrorWarning();
        static::assertFalse($implem->hasErrorMessages());
        static::assertSame([], $implem->getErrorMessages());

        $implem->addErrorMessage('error 1');
        static::assertTrue($implem->hasErrorMessages());
        static::assertSame(['error 1'], $implem->getErrorMessages());

        $implem->addErrorMessage('error 2');
        static::assertTrue($implem->hasErrorMessages());
        static::assertSame(['error 1', 'error 2'], $implem->getErrorMessages());

        $implem->resetErrorMessage();
        static::assertFalse($implem->hasErrorMessages());
        static::assertSame([], $implem->getErrorMessages());
    }

    public function testWarningMessage(): void
    {
        $implem = new ImplementationErrorWarning();
        static::assertFalse($implem->hasWarningMessages());
        static::assertSame([], $implem->getWarningMessages());

        $implem->addWarningMessage('warning 1');
        static::assertTrue($implem->hasWarningMessages());
        static::assertSame(['warning 1'], $implem->getWarningMessages());

        $implem->addWarningMessage('warning 2');
        static::assertTrue($implem->hasWarningMessages());
        static::assertSame(['warning 1', 'warning 2'], $implem->getWarningMessages());

        $implem->resetWarningMessage();
        static::assertFalse($implem->hasWarningMessages());
        static::assertSame([], $implem->getWarningMessages());
    }

    public function testErrorField(): void
    {
        $implem = new ImplementationErrorWarning();
        static::assertFalse($implem->hasErrorFields());
        static::assertSame([], $implem->getErrorFields());

        $implem->addErrorField('field 1', 'invalid 1');
        static::assertTrue($implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1']], $implem->getErrorFields());

        $implem->addErrorField('field 1', 'invalid 2');
        static::assertTrue($implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getErrorFields());

        $implem->addErrorField('field 2', 'invalid 11');
        static::assertTrue($implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11']], $implem->getErrorFields()); //phpcs:ignore

        $implem->addErrorField('field 2', 'invalid 22');
        static::assertTrue($implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11', 'invalid 22']], $implem->getErrorFields()); //phpcs:ignore

        $implem->resetErrorField('field 2');
        static::assertTrue($implem->hasErrorFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getErrorFields());

        $implem->resetErrorField();
        static::assertFalse($implem->hasErrorFields());
        static::assertSame([], $implem->getErrorFields());
    }

    public function testWarningField(): void
    {
        $implem = new ImplementationErrorWarning();
        static::assertFalse($implem->hasWarningFields());
        static::assertSame([], $implem->getWarningFields());

        $implem->addWarningField('field 1', 'invalid 1');
        static::assertTrue($implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1']], $implem->getWarningFields());

        $implem->addWarningField('field 1', 'invalid 2');
        static::assertTrue($implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getWarningFields());

        $implem->addWarningField('field 2', 'invalid 11');
        static::assertTrue($implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11']], $implem->getWarningFields()); //phpcs:ignore

        $implem->addWarningField('field 2', 'invalid 22');
        static::assertTrue($implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2'], 'field 2' => ['invalid 11', 'invalid 22']], $implem->getWarningFields()); //phpcs:ignore

        $implem->resetWarningField('field 2');
        static::assertTrue($implem->hasWarningFields());
        static::assertSame(['field 1' => ['invalid 1', 'invalid 2']], $implem->getWarningFields());

        $implem->resetWarningField();
        static::assertFalse($implem->hasWarningFields());
        static::assertSame([], $implem->getWarningFields());
    }
}
