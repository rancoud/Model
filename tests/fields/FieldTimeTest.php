<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTimeTest
 */
class FieldTimeTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'];
    protected $countInputs = 14;

    public function testFieldTime()
    {
        $rule = new Field('time');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeDefault()
    {
        $rule = new Field('time', [], '00:04:04');
        $expected = ['00:04:04', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeNotNull()
    {
        $rule = new Field('time', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeNotNullDefault()
    {
        $rule = new Field('time', ['not_null'], '00:04:04');
        $expected = ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimePk()
    {
        $rule = new Field('time', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimePkNotNull()
    {
        $rule = new Field('time', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeFk()
    {
        $rule = new Field('time', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeFkNotNull()
    {
        $rule = new Field('time', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeUnsigned()
    {
        $rule = new Field('time', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeUnsignedNotNull()
    {
        $rule = new Field('time', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeUnsignedNotNullDefault()
    {
        $rule = new Field('time', ['unsigned', 'not_null'], '00:04:04');
        $expected = ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeMin()
    {
        $rule = new Field('time', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeMax()
    {
        $rule = new Field('time', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }

    public function testFieldTimeRange()
    {
        $rule = new Field('time', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class];
        $message = [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value',
            null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value'];

        for ($i = 0; $i < $this->countInputs; $i++) {
            try{
                $output = $rule->formatValue($this->inputs[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            }
            catch(FieldException $e){
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], get_class($e));
            }
        }
    }
}