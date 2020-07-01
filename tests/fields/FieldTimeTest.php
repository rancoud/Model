<?php

declare(strict_types=1);

namespace Rancoud\Model\Fields\Test;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTimeTest
 */
class FieldTimeTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'];
    protected int $countInputs = 14;

    public function testFieldTime(): void
    {
        $rule = new Field('time');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeDefault(): void
    {
        $rule = new Field('time', [], '00:04:04');
        $expected = ['00:04:04', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeNotNull(): void
    {
        $rule = new Field('time', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeNotNullDefault(): void
    {
        $rule = new Field('time', ['not_null'], '00:04:04');
        $expected = ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimePk(): void
    {
        $rule = new Field('time', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimePkNotNull(): void
    {
        $rule = new Field('time', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeFk(): void
    {
        $rule = new Field('time', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeFkNotNull(): void
    {
        $rule = new Field('time', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeUnsigned(): void
    {
        $rule = new Field('time', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeUnsignedNotNull(): void
    {
        $rule = new Field('time', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeUnsignedNotNullDefault(): void
    {
        $rule = new Field('time', ['unsigned', 'not_null'], '00:04:04');
        $expected = ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeMin(): void
    {
        $rule = new Field('time', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeMax(): void
    {
        $rule = new Field('time', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
    public function testFieldTimeRange(): void
    {
        $rule = new Field('time', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

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
