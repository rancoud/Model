<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldCharTest
 */
class FieldCharTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected int $countInputs = 7;

    public function testFieldChar(): void
    {
        $rule = new Field('char');
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharDefault(): void
    {
        $rule = new Field('char', [], '999');
        $expected = ['999', null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharNotNull(): void
    {
        $rule = new Field('char', ['not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharNotNullDefault(): void
    {
        $rule = new Field('char', ['not_null'], '999');
        $expected = ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharPk(): void
    {
        $rule = new Field('char', ['pk']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharPkNotNull(): void
    {
        $rule = new Field('char', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharFk(): void
    {
        $rule = new Field('char', ['fk']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharFkNotNull(): void
    {
        $rule = new Field('char', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharUnsigned(): void
    {
        $rule = new Field('char', ['unsigned']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharUnsignedNotNull(): void
    {
        $rule = new Field('char', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharUnsignedNotNullDefault(): void
    {
        $rule = new Field('char', ['unsigned', 'not_null'], '999');
        $expected = ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, 'Null not authorized', null, null, null, null, null];

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
    public function testFieldCharMin(): void
    {
        $rule = new Field('char', ['min:3']);
        $expected = [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class];
        $message = [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length'];

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
    public function testFieldCharMax(): void
    {
        $rule = new Field('char', ['max:3']);
        $expected = [null, null, 'aze', '-1', '10', '50.', ''];
        $message = [null, null, null, null, null, null, null];

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
    public function testFieldCharRange(): void
    {
        $rule = new Field('char', ['range:3,4']);
        $expected = [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class];
        $message = [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length'];

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
