<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldVarcharEmailTest
 */
class FieldVarcharEmailTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'];
    protected $countInputs = 11;

    public function testFieldVarcharEmail()
    {
        $rule = new Field('varchar', ['email']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailDefault()
    {
        $rule = new Field('varchar', ['email'], 'az@az');
        $expected = ['az@az', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailNotNull()
    {
        $rule = new Field('varchar', ['email', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailNotNullDefault()
    {
        $rule = new Field('varchar', ['email', 'not_null'], 'az@az');
        $expected = ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailPk()
    {
        $rule = new Field('varchar', ['email', 'pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailPkNotNull()
    {
        $rule = new Field('varchar', ['email', 'pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailFk()
    {
        $rule = new Field('varchar', ['email', 'fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailFkNotNull()
    {
        $rule = new Field('varchar', ['email', 'fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailUnsigned()
    {
        $rule = new Field('varchar', ['email', 'unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailUnsignedNotNull()
    {
        $rule = new Field('varchar', ['email', 'unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailUnsignedNotNullDefault()
    {
        $rule = new Field('varchar', ['email', 'unsigned', 'not_null'], 'az@az');
        $expected = ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailMin()
    {
        $rule = new Field('varchar', ['email', 'min:1']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailMax()
    {
        $rule = new Field('varchar', ['email', 'max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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

    public function testFieldVarcharEmailRange()
    {
        $rule = new Field('varchar', ['email', 'range:1,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'];
        $message = [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null];

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
