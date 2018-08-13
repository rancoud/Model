<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldYearTest
 */
class FieldYearTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'];
    protected $countInputs = 12;

    public function testFieldYear()
    {
        $rule = new Field('year');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearDefault()
    {
        $rule = new Field('year', [], 2000);
        $expected = [2000, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearNotNull()
    {
        $rule = new Field('year', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearNotNullDefault()
    {
        $rule = new Field('year', ['not_null'], 2000);
        $expected = [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearPk()
    {
        $rule = new Field('year', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearPkNotNull()
    {
        $rule = new Field('year', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearFk()
    {
        $rule = new Field('year', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearFkNotNull()
    {
        $rule = new Field('year', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearUnsigned()
    {
        $rule = new Field('year', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearUnsignedNotNull()
    {
        $rule = new Field('year', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearUnsignedNotNullDefault()
    {
        $rule = new Field('year', ['unsigned', 'not_null'], 2000);
        $expected = [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2155, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, 'Invalid year value'];

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

    public function testFieldYearMin()
    {
        $rule = new Field('year', ['min:1999']);
        $expected = [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class,
            1999, 1999, 2000, 2155, FieldException::class];
        $message = [null, null, 'Invalid year value', null, null, null, 'Invalid year value',
            null, null, null, null, 'Invalid year value'];

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

    public function testFieldYearMax()
    {
        $rule = new Field('year', ['max:2005']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class,
            FieldException::class, 1901, 2000, 2005, 2005];
        $message = [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value',
            'Invalid year value', null, null, null, null];

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

    public function testFieldYearRange()
    {
        $rule = new Field('year', ['range:1999,2050']);
        $expected = [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class,
            1999, 1999, 2000, 2050, 2050];
        $message = [null, null, 'Invalid year value', null, null, null, 'Invalid year value',
            null, null, null, null, null];

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