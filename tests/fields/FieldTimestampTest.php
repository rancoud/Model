<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTimestampTest
 */
class FieldTimestampTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, '',
        '1970-01-01 00:00:00', '2038-01-19 03:14:07',
        '1969-01-01 00:00:00', '2038-01-19 03:14:08',
        '2000-01-01 50:00:00', '2000-51-91 50:00:00',
        '2000-01-01'];
    protected int $countInputs = 14;

    public function testFieldTimestamp(): void
    {
        $rule = new Field('timestamp');
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampDefault(): void
    {
        $rule = new Field('timestamp', [], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampNotNull(): void
    {
        $rule = new Field('timestamp', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampNotNullDefault(): void
    {
        $rule = new Field('timestamp', ['not_null'], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampPk(): void
    {
        $rule = new Field('timestamp', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampPkNotNull(): void
    {
        $rule = new Field('timestamp', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampFk(): void
    {
        $rule = new Field('timestamp', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampFkNotNull(): void
    {
        $rule = new Field('timestamp', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampUnsigned(): void
    {
        $rule = new Field('timestamp', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampUnsignedNotNull(): void
    {
        $rule = new Field('timestamp', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampUnsignedNotNullDefault(): void
    {
        $rule = new Field('timestamp', ['unsigned', 'not_null'], '2000-01-01 00:00:00');
        $expected = ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampMin(): void
    {
        $rule = new Field('timestamp', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampMax(): void
    {
        $rule = new Field('timestamp', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
    public function testFieldTimestampRange(): void
    {
        $rule = new Field('timestamp', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class,
            '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'];
        $message = [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value',
            null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null];

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
