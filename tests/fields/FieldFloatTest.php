<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldFloatTest
 */
class FieldFloatTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected int $countInputs = 7;

    public function testFieldFloat(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatNotNullDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatPk(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatPkNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatFk(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatFkNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatUnsigned(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatUnsignedNotNull(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatUnsignedNotNullDefault(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatMin(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatMax(): void
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

    /**
     * @throws FieldException
     */
    public function testFieldFloatRange(): void
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
