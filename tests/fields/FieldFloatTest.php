<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldFloatTest
 */
class FieldFloatTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected $countInputs = 7;

    public function testFieldFloat()
    {
        $rule = new Field('float');
        $expected = [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatDefault()
    {
        $rule = new Field('float', [], 999.0);
        $expected = [999.0, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatNotNull()
    {
        $rule = new Field('float', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatNotNullDefault()
    {
        $rule = new Field('float', ['not_null'], 999.0);
        $expected = [999.0, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatPk()
    {
        $rule = new Field('float', ['pk']);
        $expected = [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatPkNotNull()
    {
        $rule = new Field('float', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatFk()
    {
        $rule = new Field('float', ['fk']);
        $expected = [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatFkNotNull()
    {
        $rule = new Field('float', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatUnsigned()
    {
        $rule = new Field('float', ['unsigned']);
        $expected = [null, null, FieldException::class, 0.0, 10.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatUnsignedNotNull()
    {
        $rule = new Field('float', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatUnsignedNotNullDefault()
    {
        $rule = new Field('float', ['unsigned', 'not_null'], 999.0);
        $expected = [999.0, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatMin()
    {
        $rule = new Field('float', ['min:50']);
        $expected = [null, null, FieldException::class, 50.0, 50.0, 50.5, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatMax()
    {
        $rule = new Field('float', ['max:20']);
        $expected = [null, null, FieldException::class, -1.0, 10.0, 20.0, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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

    public function testFieldFloatRange()
    {
        $rule = new Field('float', ['range:25,30']);
        $expected = [null, null, FieldException::class, 25.0, 25.0, 30.0, FieldException::class];
        $message = [null, null, 'Invalid float value', null, null, null, 'Invalid float value'];

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
