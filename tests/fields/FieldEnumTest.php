<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldEnumTest
 */
class FieldEnumTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'];
    protected $countInputs = 9;

    public function testFieldEnum()
    {
        $rule = new Field('enum:a,b');
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumDefault()
    {
        $rule = new Field('enum:a,b', [], 'a');
        $expected = ['a', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumNotNull()
    {
        $rule = new Field('enum:a,b', ['not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumNotNullDefault()
    {
        $rule = new Field('enum:a,b', ['not_null'], 'a');
        $expected = ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumPk()
    {
        $rule = new Field('enum:a,b', ['pk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumPkNotNull()
    {
        $rule = new Field('enum:a,b', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumFk()
    {
        $rule = new Field('enum:a,b', ['fk']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumFkNotNull()
    {
        $rule = new Field('enum:a,b', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumUnsigned()
    {
        $rule = new Field('enum:a,b', ['unsigned']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumUnsignedNotNull()
    {
        $rule = new Field('enum:a,b', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumUnsignedNotNullDefault()
    {
        $rule = new Field('enum:a,b', ['unsigned', 'not_null'], 'a');
        $expected = ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumMin()
    {
        $rule = new Field('enum:a,b', ['min:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumMax()
    {
        $rule = new Field('enum:a,b', ['max:3']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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

    public function testFieldEnumRange()
    {
        $rule = new Field('enum:a,b', ['range:3,4']);
        $expected = [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'];
        $message = [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null];

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