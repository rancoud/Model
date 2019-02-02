<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldDatetimeTest
 */
class FieldDatetimeTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'];
    protected $countInputs = 11;

    public function testFieldDatetime()
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

    public function testFieldDatetimeDefault()
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

    public function testFieldDatetimeNotNull()
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

    public function testFieldDatetimeNotNullDefault()
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

    public function testFieldDatetimePk()
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

    public function testFieldDatetimePkNotNull()
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

    public function testFieldDatetimeFk()
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

    public function testFieldDatetimeFkNotNull()
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

    public function testFieldDatetimeUnsigned()
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

    public function testFieldDatetimeUnsignedNotNull()
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

    public function testFieldDatetimeUnsignedNotNullDefault()
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

    public function testFieldDatetimeMin()
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

    public function testFieldDatetimeMax()
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

    public function testFieldDatetimeRange()
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
