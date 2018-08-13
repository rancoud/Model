<?php

declare(strict_types=1);

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTest
 */
class FieldTest extends TestCase
{
    protected $inputs = [false, null, 'azerty', '-1', '10', 50.50];
    protected $countInputs = 6;

    public function testFieldExceptionInvalidType()
    {
        static::expectException(FieldException::class);
        static::expectExceptionMessage('Incorrect Type. Valid type: int, float, char, varchar, text, date, datetime, time, timestamp, year, enum:v1,v2');

        new Field('invalid');
    }

    public function testFieldExceptionInvalidRule()
    {
        static::expectException(FieldException::class);
        static::expectExceptionMessage('Incorrect Rule. Valid rule: pk, fk, unsigned, email, not_null, max, min, range, max:int, min:int, range:int,int');

        new Field('int', ['invalid']);
    }

    public function testFieldKeyType()
    {
        $field = new Field('int', ['unsigned', 'not_null', 'pk']);
        static::assertTrue($field->isPrimaryKey());
        static::assertFalse($field->isForeignKey());
        
        $field = new Field('int', ['unsigned', 'not_null', 'fk']);
        static::assertFalse($field->isPrimaryKey());
        static::assertTrue($field->isForeignKey());
    }

    public function testFieldDefault()
    {
        $field = new Field('int', ['unsigned', 'not_null', 'pk']);
        static::assertSame(false, $field->getDefault());

        $field = new Field('int', ['unsigned', 'not_null', 'pk'], 8);
        static::assertSame(8, $field->getDefault());

        $countAssert = 1;

        try {
            new Field('varchar', ['email'], '8');
        }catch (FieldException $fieldException){
            static::assertSame('Invalid email value', $fieldException->getMessage());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }
}