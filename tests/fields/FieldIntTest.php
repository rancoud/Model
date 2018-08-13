<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldIntTest
 */
class FieldIntTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected $countInputs = 7;

    public function testFieldInt()
    {
        $rule = new Field('int');
        $expected = [null, null, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntDefault()
    {
        $rule = new Field('int', [], 999);
        $expected = [999, null, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntNotNull()
    {
        $rule = new Field('int', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntNotNullDefault()
    {
        $rule = new Field('int', ['not_null'], 999);
        $expected = [999, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntPk()
    {
        $rule = new Field('int', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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

    public function testFieldIntPkNotNull()
    {
        $rule = new Field('int', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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

    public function testFieldIntFk()
    {
        $rule = new Field('int', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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

    public function testFieldIntFkNotNull()
    {
        $rule = new Field('int', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value'];

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

    public function testFieldIntUnsigned()
    {
        $rule = new Field('int', ['unsigned']);
        $expected = [null, null, FieldException::class, 0, 10, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntUnsignedNotNull()
    {
        $rule = new Field('int', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntUnsignedNotNullDefault()
    {
        $rule = new Field('int', ['unsigned', 'not_null'], 999);
        $expected = [999, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class];
        $message = [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntMin()
    {
        $rule = new Field('int', ['min:50']);
        $expected = [null, null, FieldException::class, 50, 50, 50, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntMax()
    {
        $rule = new Field('int', ['max:20']);
        $expected = [null, null, FieldException::class, -1, 10, 20, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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

    public function testFieldIntRange()
    {
        $rule = new Field('int', ['range:25,30']);
        $expected = [null, null, FieldException::class, 25, 25, 30, FieldException::class];
        $message = [null, null, 'Invalid int value', null, null, null, 'Invalid int value'];

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