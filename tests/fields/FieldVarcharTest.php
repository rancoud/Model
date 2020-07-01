<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldVarcharTest
 */
class FieldVarcharTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected int $countInputs = 7;

    public function testFieldVarchar(): void
    {
        $rule = new Field('varchar');
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
    public function testFieldVarcharDefault(): void
    {
        $rule = new Field('varchar', [], '999');
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
    public function testFieldVarcharNotNull(): void
    {
        $rule = new Field('varchar', ['not_null']);
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
    public function testFieldVarcharNotNullDefault(): void
    {
        $rule = new Field('varchar', ['not_null'], '999');
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
    public function testFieldVarcharPk(): void
    {
        $rule = new Field('varchar', ['pk']);
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
    public function testFieldVarcharPkNotNull(): void
    {
        $rule = new Field('varchar', ['pk', 'not_null']);
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
    public function testFieldVarcharFk(): void
    {
        $rule = new Field('varchar', ['fk']);
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
    public function testFieldVarcharFkNotNull(): void
    {
        $rule = new Field('varchar', ['fk', 'not_null']);
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
    public function testFieldVarcharUnsigned(): void
    {
        $rule = new Field('varchar', ['unsigned']);
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
    public function testFieldVarcharUnsignedNotNull(): void
    {
        $rule = new Field('varchar', ['unsigned', 'not_null']);
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
    public function testFieldVarcharUnsignedNotNullDefault(): void
    {
        $rule = new Field('varchar', ['unsigned', 'not_null'], '999');
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
    public function testFieldVarcharMin(): void
    {
        $rule = new Field('varchar', ['min:3']);
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
    public function testFieldVarcharMax(): void
    {
        $rule = new Field('varchar', ['max:3']);
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
    public function testFieldVarcharRange(): void
    {
        $rule = new Field('varchar', ['range:3,4']);
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
