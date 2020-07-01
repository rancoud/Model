<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldVarcharEmailTest
 */
class FieldVarcharEmailTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'];
    protected int $countInputs = 11;

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmail(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailNotNullDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailPk(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailPkNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailFk(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailFkNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailUnsigned(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailUnsignedNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailUnsignedNotNullDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailMin(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailMax(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldVarcharEmailRange(): void
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
