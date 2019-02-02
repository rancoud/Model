<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldDateTest
 */
class FieldDateTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'];
    protected $countInputs = 11;

    public function testFieldDate()
    {
        $rule = new Field('date');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateDefault()
    {
        $rule = new Field('date', [], '2000-01-01');
        $expected = ['2000-01-01', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateNotNull()
    {
        $rule = new Field('date', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateNotNullDefault()
    {
        $rule = new Field('date', ['not_null'], '2000-01-01');
        $expected = ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDatePk()
    {
        $rule = new Field('date', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDatePkNotNull()
    {
        $rule = new Field('date', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateFk()
    {
        $rule = new Field('date', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateFkNotNull()
    {
        $rule = new Field('date', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateUnsigned()
    {
        $rule = new Field('date', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateUnsignedNotNull()
    {
        $rule = new Field('date', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateUnsignedNotNullDefault()
    {
        $rule = new Field('date', ['unsigned', 'not_null'], '2000-01-01');
        $expected = ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateMin()
    {
        $rule = new Field('date', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateMax()
    {
        $rule = new Field('date', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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

    public function testFieldDateRange()
    {
        $rule = new Field('date', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'];
        $message = [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value',
            null, null, 'Invalid date value', null];

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
