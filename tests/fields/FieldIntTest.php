<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldIntTest
 */
class FieldIntTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected int $countInputs = 7;

    public function testFieldInt(): void
    {
        $rule = new Field('int');
        $expected = [null, null, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntDefault(): void
    {
        $rule = new Field('int', [], 999);
        $expected = [999, null, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntNotNull(): void
    {
        $rule = new Field('int', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntNotNullDefault(): void
    {
        $rule = new Field('int', ['not_null'], 999);
        $expected = [999, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntPk(): void
    {
        $rule = new Field('int', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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
    public function testFieldIntPkNotNull(): void
    {
        $rule = new Field('int', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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
    public function testFieldIntFk(): void
    {
        $rule = new Field('int', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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
    public function testFieldIntFkNotNull(): void
    {
        $rule = new Field('int', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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
    public function testFieldIntUnsigned(): void
    {
        $rule = new Field('int', ['unsigned']);
        $expected = [null, null, FieldException::class, 0, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntUnsignedNotNull(): void
    {
        $rule = new Field('int', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntUnsignedNotNullDefault(): void
    {
        $rule = new Field('int', ['unsigned', 'not_null'], 999);
        $expected = [999, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntMin(): void
    {
        $rule = new Field('int', ['min:50']);
        $expected = [null, null, FieldException::class, 50, 50, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntMax(): void
    {
        $rule = new Field('int', ['max:20']);
        $expected = [null, null, FieldException::class, -1, 10, 20, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
    public function testFieldIntRange(): void
    {
        $rule = new Field('int', ['range:25,30']);
        $expected = [null, null, FieldException::class, 25, 25, 30, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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
