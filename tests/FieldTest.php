<?php

/** @noinspection PhpTooManyParametersInspection */

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rancoud\Model\Field;
use Rancoud\Model\FieldException;

/**
 * Class FieldTest.
 *
 * @internal
 */
class FieldTest extends TestCase
{
    protected array $inputs = [false, null, 'azerty', '-1', '10', 50.50];

    protected int $countInputs = 6;

    public function testFieldExceptionInvalidType(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessage('Incorrect Type. Valid type: int, float, char, varchar, text, date, datetime, time, timestamp, year, enum:v1,v2'); // phpcs:ignore

        new Field('invalid');
    }

    public function testFieldExceptionInvalidRule(): void
    {
        $this->expectException(FieldException::class);
        $this->expectExceptionMessage('Incorrect Rule. Valid rule: pk, fk, unsigned, email, not_null, max, min, range, max:int, min:int, range:int,int'); // phpcs:ignore

        new Field('int', ['invalid']);
    }

    /** @throws FieldException */
    public function testFieldKeyType(): void
    {
        $field = new Field('int', ['unsigned', 'not_null', 'pk']);
        static::assertTrue($field->isPrimaryKey());
        static::assertFalse($field->isForeignKey());

        $field = new Field('int', ['unsigned', 'not_null', 'fk']);
        static::assertFalse($field->isPrimaryKey());
        static::assertTrue($field->isForeignKey());
    }

    /** @throws FieldException */
    public function testFieldDefault(): void
    {
        $field = new Field('int', ['unsigned', 'not_null', 'pk']);
        static::assertFalse($field->getDefault());

        $field = new Field('int', ['unsigned', 'not_null', 'pk'], 8);
        static::assertSame(8, $field->getDefault());

        $countAssert = 1;

        try {
            new Field('varchar', ['email'], '8');
        } catch (FieldException $fieldException) {
            static::assertSame('Invalid email value', $fieldException->getMessage());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    // region Data Provider

    public static function provideFieldFormatDataCases(): iterable
    {
        // phpcs:disable

        // char
        yield 'char' => [
            'fieldType' => 'char',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char default' => [
            'fieldType' => 'char',
            'rules'     => [],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char not null' => [
            'fieldType' => 'char',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char not null default' => [
            'fieldType' => 'char',
            'rules'     => ['not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char PK' => [
            'fieldType' => 'char',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char PK not null' => [
            'fieldType' => 'char',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char FK' => [
            'fieldType' => 'char',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char FK not null' => [
            'fieldType' => 'char',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char unsigned' => [
            'fieldType' => 'char',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char unsigned not null' => [
            'fieldType' => 'char',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char unsigned not null default' => [
            'fieldType' => 'char',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'char min' => [
            'fieldType' => 'char',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        yield 'char max' => [
            'fieldType' => 'char',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'aze', '-1', '10', '50.', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'char range' => [
            'fieldType' => 'char',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        // date
        yield 'date' => [
            'fieldType' => 'date',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date default' => [
            'fieldType' => 'date',
            'rules'     => [],
            'default'   => '2000-01-01',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => ['2000-01-01', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date not null' => [
            'fieldType' => 'date',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date not null default' => [
            'fieldType' => 'date',
            'rules'     => ['not_null'],
            'default'   => '2000-01-01',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date PK' => [
            'fieldType' => 'date',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date PK not null' => [
            'fieldType' => 'date',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date FK' => [
            'fieldType' => 'date',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date FK not null' => [
            'fieldType' => 'date',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date unsigned' => [
            'fieldType' => 'date',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date unsigned not null' => [
            'fieldType' => 'date',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date unsigned not null default' => [
            'fieldType' => 'date',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '2000-01-01',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date min' => [
            'fieldType' => 'date',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date max' => [
            'fieldType' => 'date',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        yield 'date range' => [
            'fieldType' => 'date',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
            'message'   => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
        ];

        // datetime
        yield 'datetime' => [
            'fieldType' => 'datetime',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime default' => [
            'fieldType' => 'datetime',
            'rules'     => [],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime not null' => [
            'fieldType' => 'datetime',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime not null default' => [
            'fieldType' => 'datetime',
            'rules'     => ['not_null'],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime PK' => [
            'fieldType' => 'datetime',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime PK not null' => [
            'fieldType' => 'datetime',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime FK' => [
            'fieldType' => 'datetime',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime FK not null' => [
            'fieldType' => 'datetime',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime unsigned' => [
            'fieldType' => 'datetime',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime unsigned not null' => [
            'fieldType' => 'datetime',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime unsigned not null default' => [
            'fieldType' => 'datetime',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime min' => [
            'fieldType' => 'datetime',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime max' => [
            'fieldType' => 'datetime',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        yield 'datetime range' => [
            'fieldType' => 'datetime',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59', FieldException::class, '2000-03-02 23:59:59'],
            'message'   => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
        ];

        // enum
        yield 'enum' => [
            'fieldType' => 'enum:a,b',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum default' => [
            'fieldType' => 'enum:a,b',
            'rules'     => [],
            'default'   => 'a',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => ['a', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum not null' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum not null default' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['not_null'],
            'default'   => 'a',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum PK' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum PK not null' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum FK' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum FK not null' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum unsigned' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum unsigned not null' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum unsigned not null default' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => 'a',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum min' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum max' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        yield 'enum range' => [
            'fieldType' => 'enum:a,b',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
            'message'   => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
        ];

        // float
        yield 'float' => [
            'fieldType' => 'float',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float default' => [
            'fieldType' => 'float',
            'rules'     => [],
            'default'   => 999.0,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999.0, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float not null' => [
            'fieldType' => 'float',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float not null default' => [
            'fieldType' => 'float',
            'rules'     => ['not_null'],
            'default'   => 999.0,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999.0, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float PK' => [
            'fieldType' => 'float',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float PK not null' => [
            'fieldType' => 'float',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float FK' => [
            'fieldType' => 'float',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float FK not null' => [
            'fieldType' => 'float',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float unsigned' => [
            'fieldType' => 'float',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float unsigned not null' => [
            'fieldType' => 'float',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float unsigned not null default' => [
            'fieldType' => 'float',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => 999.0,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999.0, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float min' => [
            'fieldType' => 'float',
            'rules'     => ['min:50'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 50.0, 50.0, 50.5, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float max' => [
            'fieldType' => 'float',
            'rules'     => ['max:20'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1.0, 10.0, 20.0, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        yield 'float range' => [
            'fieldType' => 'float',
            'rules'     => ['range:25,30'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 25.0, 25.0, 30.0, FieldException::class],
            'message'   => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
        ];

        // int
        yield 'int' => [
            'fieldType' => 'int',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1, 10, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int default' => [
            'fieldType' => 'int',
            'rules'     => [],
            'default'   => 999,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999, null, FieldException::class, -1, 10, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int not null' => [
            'fieldType' => 'int',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int not null default' => [
            'fieldType' => 'int',
            'rules'     => ['not_null'],
            'default'   => 999,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int PK' => [
            'fieldType' => 'int',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
        ];

        yield 'int PK not null' => [
            'fieldType' => 'int',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
        ];

        yield 'int FK' => [
            'fieldType' => 'int',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
        ];

        yield 'int FK not null' => [
            'fieldType' => 'int',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
        ];

        yield 'int unsigned' => [
            'fieldType' => 'int',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 0, 10, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int unsigned not null' => [
            'fieldType' => 'int',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int unsigned not null default' => [
            'fieldType' => 'int',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => 999,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [999, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int min' => [
            'fieldType' => 'int',
            'rules'     => ['min:50'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 50, 50, 50, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int max' => [
            'fieldType' => 'int',
            'rules'     => ['max:20'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, -1, 10, 20, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        yield 'int range' => [
            'fieldType' => 'int',
            'rules'     => ['range:25,30'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, 25, 25, 30, FieldException::class],
            'message'   => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
        ];

        // text
        yield 'text' => [
            'fieldType' => 'text',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text default' => [
            'fieldType' => 'text',
            'rules'     => [],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text not null' => [
            'fieldType' => 'text',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text not null default' => [
            'fieldType' => 'text',
            'rules'     => ['not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text PK' => [
            'fieldType' => 'text',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text PK not null' => [
            'fieldType' => 'text',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text FK' => [
            'fieldType' => 'text',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text FK not null' => [
            'fieldType' => 'text',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text unsigned' => [
            'fieldType' => 'text',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text unsigned not null' => [
            'fieldType' => 'text',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text unsigned not null default' => [
            'fieldType' => 'text',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'text min' => [
            'fieldType' => 'text',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        yield 'text max' => [
            'fieldType' => 'text',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'aze', '-1', '10', '50.', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'text range' => [
            'fieldType' => 'text',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        // timestamp
        yield 'timestamp' => [
            'fieldType' => 'timestamp',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp default' => [
            'fieldType' => 'timestamp',
            'rules'     => [],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp not null' => [
            'fieldType' => 'timestamp',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp not null default' => [
            'fieldType' => 'timestamp',
            'rules'     => ['not_null'],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp PK' => [
            'fieldType' => 'timestamp',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp PK not null' => [
            'fieldType' => 'timestamp',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp FK' => [
            'fieldType' => 'timestamp',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp FK not null' => [
            'fieldType' => 'timestamp',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp unsigned' => [
            'fieldType' => 'timestamp',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp unsigned not null' => [
            'fieldType' => 'timestamp',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp unsigned not null default' => [
            'fieldType' => 'timestamp',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '2000-01-01 00:00:00',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp min' => [
            'fieldType' => 'timestamp',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp max' => [
            'fieldType' => 'timestamp',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        yield 'timestamp range' => [
            'fieldType' => 'timestamp',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
            'expected'  => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
            'message'   => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
        ];

        // time
        yield 'time' => [
            'fieldType' => 'time',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time default' => [
            'fieldType' => 'time',
            'rules'     => [],
            'default'   => '00:04:04',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => ['00:04:04', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time not null' => [
            'fieldType' => 'time',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time not null default' => [
            'fieldType' => 'time',
            'rules'     => ['not_null'],
            'default'   => '00:04:04',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time PK' => [
            'fieldType' => 'time',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time PK not null' => [
            'fieldType' => 'time',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time FK' => [
            'fieldType' => 'time',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time FK not null' => [
            'fieldType' => 'time',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time unsigned' => [
            'fieldType' => 'time',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time unsigned not null' => [
            'fieldType' => 'time',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time unsigned not null default' => [
            'fieldType' => 'time',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '00:04:04',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time min' => [
            'fieldType' => 'time',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time max' => [
            'fieldType' => 'time',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        yield 'time range' => [
            'fieldType' => 'time',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class, FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
            'message'   => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
        ];

        // varchar
        yield 'varchar email' => [
            'fieldType' => 'varchar',
            'rules'     => ['email'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email default' => [
            'fieldType' => 'varchar',
            'rules'     => ['email'],
            'default'   => 'az@az',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => ['az@az', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email not null default' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'not_null'],
            'default'   => 'az@az',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email PK' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email PK not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email FK' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email FK not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email unsigned' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email unsigned not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email unsigned not null default' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'unsigned', 'not_null'],
            'default'   => 'az@az',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email min' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'min:1'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email max' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        yield 'varchar email range' => [
            'fieldType' => 'varchar',
            'rules'     => ['email', 'range:1,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
            'message'   => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
        ];

        // varchar
        yield 'varchar' => [
            'fieldType' => 'varchar',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar default' => [
            'fieldType' => 'varchar',
            'rules'     => [],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar not null default' => [
            'fieldType' => 'varchar',
            'rules'     => ['not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar PK' => [
            'fieldType' => 'varchar',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar PK not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar FK' => [
            'fieldType' => 'varchar',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar FK not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar unsigned' => [
            'fieldType' => 'varchar',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar unsigned not null' => [
            'fieldType' => 'varchar',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar unsigned not null default' => [
            'fieldType' => 'varchar',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => '999',
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
            'message'   => [null, 'Null not authorized', null, null, null, null, null]
        ];

        yield 'varchar min' => [
            'fieldType' => 'varchar',
            'rules'     => ['min:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        yield 'varchar max' => [
            'fieldType' => 'varchar',
            'rules'     => ['max:3'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'aze', '-1', '10', '50.', ''],
            'message'   => [null, null, null, null, null, null, null]
        ];

        yield 'varchar range' => [
            'fieldType' => 'varchar',
            'rules'     => ['range:3,4'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
            'message'   => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
        ];

        // year
        yield 'year' => [
            'fieldType' => 'year',
            'rules'     => [],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year default' => [
            'fieldType' => 'year',
            'rules'     => [],
            'default'   => 2000,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [2000, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year not null' => [
            'fieldType' => 'year',
            'rules'     => ['not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year not null default' => [
            'fieldType' => 'year',
            'rules'     => ['not_null'],
            'default'   => 2000,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year PK' => [
            'fieldType' => 'year',
            'rules'     => ['pk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year PK not null' => [
            'fieldType' => 'year',
            'rules'     => ['pk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year FK' => [
            'fieldType' => 'year',
            'rules'     => ['fk'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year FK not null' => [
            'fieldType' => 'year',
            'rules'     => ['fk', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year unsigned' => [
            'fieldType' => 'year',
            'rules'     => ['unsigned'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year unsigned not null' => [
            'fieldType' => 'year',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year unsigned not null default' => [
            'fieldType' => 'year',
            'rules'     => ['unsigned', 'not_null'],
            'default'   => 2000,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
            'message'   => [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
        ];

        yield 'year min' => [
            'fieldType' => 'year',
            'rules'     => ['min:1999'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class, 1999, 1999, 2000, 2155, FieldException::class],
            'message'   => [null, null, 'Invalid year value', null, null, null, 'Invalid year value', null, null, null, null, 'Invalid year value']
        ];

        yield 'year max' => [
            'fieldType' => 'year',
            'rules'     => ['max:2005'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2005, 2005],
            'message'   => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, null]
        ];

        yield 'year range' => [
            'fieldType' => 'year',
            'rules'     => ['range:1999,2050'],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
            'expected'  => [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class, 1999, 1999, 2000, 2050, 2050],
            'message'   => [null, null, 'Invalid year value', null, null, null, 'Invalid year value', null, null, null, null, null]
        ];

        // custom
        yield 'custom rule' => [
            'fieldType' => 'char',
            'rules'     => [new MyRule()],
            'default'   => false,
            'input'     => [false, null, 'azerty', '-1', '10', 50.50, ''],
            'expected'  => [null, null, FieldException::class, '-1', '10', '50.5', ''],
            'message'   => [null, null, 'invalid azerty value', null, null, null, null]
        ];

        // phpcs:enable
    }

    // endregion

    /**
     * @dataProvider provideFieldFormatDataCases
     *
     * @throws FieldException
     */
    #[DataProvider('data')]
    public function testFieldFormat(string $fieldType, array $rules, $default, array $input, array $expected, array $message): void // phpcs:ignore
    {
        $rule = new Field($fieldType, $rules, $default);
        for ($i = 0, $max = \count($input); $i < $max; ++$i) {
            try {
                $output = $rule->formatValue($input[$i]);
                static::assertSame($expected[$i], $output);
                static::assertNull($message[$i]);
            } catch (FieldException $e) {
                static::assertSame($message[$i], $e->getMessage());
                static::assertSame($expected[$i], $e::class);
            }
        }
    }
}
