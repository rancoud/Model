<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldDatetimeTest
 */
class FieldDatetimeTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'];
    protected int $countInputs = 11;

    public function testFieldDatetime(): void
    {
        $rule = new Field('datetime');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeDefault(): void
    {
        $rule = new Field('datetime', [], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeNotNull(): void
    {
        $rule = new Field('datetime', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeNotNullDefault(): void
    {
        $rule = new Field('datetime', ['not_null'], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimePk(): void
    {
        $rule = new Field('datetime', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimePkNotNull(): void
    {
        $rule = new Field('datetime', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeFk(): void
    {
        $rule = new Field('datetime', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeFkNotNull(): void
    {
        $rule = new Field('datetime', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeUnsigned(): void
    {
        $rule = new Field('datetime', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeUnsignedNotNull(): void
    {
        $rule = new Field('datetime', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeUnsignedNotNullDefault(): void
    {
        $rule = new Field('datetime', ['unsigned', 'not_null'], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeMin(): void
    {
        $rule = new Field('datetime', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeMax(): void
    {
        $rule = new Field('datetime', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    /**
     * @throws FieldException
     */
    public function testFieldDatetimeRange(): void
    {
        $rule = new Field('datetime', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'];
        $message = [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value',
            null, null, 'Invalid datetime value', null];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try {
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }
}
