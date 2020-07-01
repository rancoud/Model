<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldEnumTest
 */
class FieldEnumTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'];
    protected int $countInputs = 9;

    public function testFieldEnum(): void
    {
        $rule = new Field('enum:a,b');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumDefault(): void
    {
        $rule = new Field('enum:a,b', [], 'a');
        $expected = ['a', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumNotNull(): void
    {
        $rule = new Field('enum:a,b', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumNotNullDefault(): void
    {
        $rule = new Field('enum:a,b', ['not_null'], 'a');
        $expected = ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumPk(): void
    {
        $rule = new Field('enum:a,b', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumPkNotNull(): void
    {
        $rule = new Field('enum:a,b', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumFk(): void
    {
        $rule = new Field('enum:a,b', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumFkNotNull(): void
    {
        $rule = new Field('enum:a,b', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumUnsigned(): void
    {
        $rule = new Field('enum:a,b', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumUnsignedNotNull(): void
    {
        $rule = new Field('enum:a,b', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumUnsignedNotNullDefault(): void
    {
        $rule = new Field('enum:a,b', ['unsigned', 'not_null'], 'a');
        $expected = ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumMin(): void
    {
        $rule = new Field('enum:a,b', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumMax(): void
    {
        $rule = new Field('enum:a,b', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
    public function testFieldEnumRange(): void
    {
        $rule = new Field('enum:a,b', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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
