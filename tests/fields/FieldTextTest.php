<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTextTest
 */
class FieldTextTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50, ''];
    protected $countInputs = 7;

    public function testFieldText()
    {
        $rule = new Field('text');
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextDefault()
    {
        $rule = new Field('text', [], '999');
        $expected = ['999', null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextNotNull()
    {
        $rule = new Field('text', ['not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextNotNullDefault()
    {
        $rule = new Field('text', ['not_null'], '999');
        $expected = ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextPk()
    {
        $rule = new Field('text', ['pk']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextPkNotNull()
    {
        $rule = new Field('text', ['pk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextFk()
    {
        $rule = new Field('text', ['fk']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextFkNotNull()
    {
        $rule = new Field('text', ['fk', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextUnsigned()
    {
        $rule = new Field('text', ['unsigned']);
        $expected = [null, null, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextUnsignedNotNull()
    {
        $rule = new Field('text', ['unsigned', 'not_null']);
        $expected = [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = ['Invalid default value', 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextUnsignedNotNullDefault()
    {
        $rule = new Field('text', ['unsigned', 'not_null'], '999');
        $expected = ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''];
        $message = [null, 'Null not authorized', null, null, null, null, null];

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

    public function testFieldTextMin()
    {
        $rule = new Field('text', ['min:3']);
        $expected = [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class];
        $message = [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length'];

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

    public function testFieldTextMax()
    {
        $rule = new Field('text', ['max:3']);
        $expected = [null, null, 'aze', '-1', '10', '50.', ''];
        $message = [null, null, null, null, null, null, null];

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

    public function testFieldTextRange()
    {
        $rule = new Field('text', ['range:3,4']);
        $expected = [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class];
        $message = [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length'];

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