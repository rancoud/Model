<?php

/** @noinspection PhpTooManyParametersInspection */

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Rancoud\Model\Field;
use Rancoud\Model\FieldException;

/**
 * Class FieldTest.
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

    /**
     * @throws FieldException
     */
    public function testFieldKeyType(): void
    {
        $field = new Field('int', ['unsigned', 'not_null', 'pk']);
        static::assertTrue($field->isPrimaryKey());
        static::assertFalse($field->isForeignKey());

        $field = new Field('int', ['unsigned', 'not_null', 'fk']);
        static::assertFalse($field->isPrimaryKey());
        static::assertTrue($field->isForeignKey());
    }

    /**
     * @throws FieldException
     */
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

    public function data(): array
    {
        // phpcs:disable
        return [
            // char
            'char' => [
                'field_type' => 'char',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char default' => [
                'field_type' => 'char',
                'rules'      => [],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char not null' => [
                'field_type' => 'char',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'char not null default' => [
                'field_type' => 'char',
                'rules'      => ['not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'char PK' => [
                'field_type' => 'char',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char PK not null' => [
                'field_type' => 'char',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'char FK' => [
                'field_type' => 'char',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char FK not null' => [
                'field_type' => 'char',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'char unsigned' => [
                'field_type' => 'char',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char unsigned not null' => [
                'field_type' => 'char',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'char unsigned not null default' => [
                'field_type' => 'char',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'char min' => [
                'field_type' => 'char',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            'char max' => [
                'field_type' => 'char',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'aze', '-1', '10', '50.', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'char range' => [
                'field_type' => 'char',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            // date
            'date' => [
                'field_type' => 'date',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date default' => [
                'field_type' => 'date',
                'rules'      => [],
                'default'    => '2000-01-01',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => ['2000-01-01', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date not null' => [
                'field_type' => 'date',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date not null default' => [
                'field_type' => 'date',
                'rules'      => ['not_null'],
                'default'    => '2000-01-01',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date PK' => [
                'field_type' => 'date',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date PK not null' => [
                'field_type' => 'date',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date FK' => [
                'field_type' => 'date',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date FK not null' => [
                'field_type' => 'date',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date unsigned' => [
                'field_type' => 'date',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date unsigned not null' => [
                'field_type' => 'date',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date unsigned not null default' => [
                'field_type' => 'date',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '2000-01-01',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => ['2000-01-01', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, 'Null not authorized', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date min' => [
                'field_type' => 'date',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date max' => [
                'field_type' => 'date',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            'date range' => [
                'field_type' => 'date',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01', '9999-12-31', '2000-52-31', '2000-02-31'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01', '9999-12-31', FieldException::class, '2000-03-02'],
                'message'    => [null, null, 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', 'Invalid date value', null, null, 'Invalid date value', null]
            ],
            // datetime
            'datetime' => [
                'field_type' => 'datetime',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime default' => [
                'field_type' => 'datetime',
                'rules'      => [],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime not null' => [
                'field_type' => 'datetime',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime not null default' => [
                'field_type' => 'datetime',
                'rules'      => ['not_null'],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime PK' => [
                'field_type' => 'datetime',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime PK not null' => [
                'field_type' => 'datetime',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime FK' => [
                'field_type' => 'datetime',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime FK not null' => [
                'field_type' => 'datetime',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime unsigned' => [
                'field_type' => 'datetime',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime unsigned not null' => [
                'field_type' => 'datetime',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime unsigned not null default' => [
                'field_type' => 'datetime',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, 'Null not authorized', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime min' => [
                'field_type' => 'datetime',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime max' => [
                'field_type' => 'datetime',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            'datetime range' => [
                'field_type' => 'datetime',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1000-01-01 00:00:00', '9999-12-31 23:59:59', '2000-52-31 23:59:59', '2000-02-31 23:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1000-01-01 00:00:00', '9999-12-31 23:59:59',  FieldException::class, '2000-03-02 23:59:59'],
                'message'    => [null, null, 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', 'Invalid datetime value', null, null, 'Invalid datetime value', null]
            ],
            // enum
            'enum' => [
                'field_type' => 'enum:a,b',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum default' => [
                'field_type' => 'enum:a,b',
                'rules'      => [],
                'default'    => 'a',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => ['a', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum not null' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum not null default' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['not_null'],
                'default'    => 'a',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum PK' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum PK not null' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum FK' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum FK not null' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum unsigned' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum unsigned not null' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum unsigned not null default' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => 'a',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => ['a', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, 'Null not authorized', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum min' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum max' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            'enum range' => [
                'field_type' => 'enum:a,b',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 'a', 'b'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a', 'b'],
                'message'    => [null, null, 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', 'Invalid enum value', null, null]
            ],
            // float
            'float' => [
                'field_type' => 'float',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float default' => [
                'field_type' => 'float',
                'rules'      => [],
                'default'    => 999.0,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999.0, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float not null' => [
                'field_type' => 'float',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float not null default' => [
                'field_type' => 'float',
                'rules'      => ['not_null'],
                'default'    => 999.0,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999.0, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float PK' => [
                'field_type' => 'float',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float PK not null' => [
                'field_type' => 'float',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float FK' => [
                'field_type' => 'float',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float FK not null' => [
                'field_type' => 'float',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, -1.0, 10.0, 50.5, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float unsigned' => [
                'field_type' => 'float',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float unsigned not null' => [
                'field_type' => 'float',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float unsigned not null default' => [
                'field_type' => 'float',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => 999.0,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999.0, FieldException::class, FieldException::class, 0.0, 10.0, 50.5, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float min' => [
                'field_type' => 'float',
                'rules'      => ['min:50'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 50.0, 50.0, 50.5, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float max' => [
                'field_type' => 'float',
                'rules'      => ['max:20'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1.0, 10.0, 20.0, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            'float range' => [
                'field_type' => 'float',
                'rules'      => ['range:25,30'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 25.0, 25.0, 30.0, FieldException::class],
                'message'    => [null, null, 'Invalid float value', null, null, null, 'Invalid float value']
            ],
            // int
            'int' => [
                'field_type' => 'int',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1, 10, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int default' => [
                'field_type' => 'int',
                'rules'      => [],
                'default'    => 999,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999, null, FieldException::class, -1, 10, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int not null' => [
                'field_type' => 'int',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int not null default' => [
                'field_type' => 'int',
                'rules'      => ['not_null'],
                'default'    => 999,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999, FieldException::class, FieldException::class, -1, 10, 50, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int PK' => [
                'field_type' => 'int',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
            ],
            'int PK not null' => [
                'field_type' => 'int',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
            ],
            'int FK' => [
                'field_type' => 'int',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, FieldException::class, 10, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
            ],
            'int FK not null' => [
                'field_type' => 'int',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, 10, 50, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid int value', 'Invalid key value', null, null, 'Invalid int value']
            ],
            'int unsigned' => [
                'field_type' => 'int',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 0, 10, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int unsigned not null' => [
                'field_type' => 'int',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int unsigned not null default' => [
                'field_type' => 'int',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => 999,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [999, FieldException::class, FieldException::class, 0, 10, 50, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int min' => [
                'field_type' => 'int',
                'rules'      => ['min:50'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 50, 50, 50, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int max' => [
                'field_type' => 'int',
                'rules'      => ['max:20'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, -1, 10, 20, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            'int range' => [
                'field_type' => 'int',
                'rules'      => ['range:25,30'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, 25, 25, 30, FieldException::class],
                'message'    => [null, null, 'Invalid int value', null, null, null, 'Invalid int value']
            ],
            // text
            'text' => [
                'field_type' => 'text',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text default' => [
                'field_type' => 'text',
                'rules'      => [],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text not null' => [
                'field_type' => 'text',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'text not null default' => [
                'field_type' => 'text',
                'rules'      => ['not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'text PK' => [
                'field_type' => 'text',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text PK not null' => [
                'field_type' => 'text',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'text FK' => [
                'field_type' => 'text',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text FK not null' => [
                'field_type' => 'text',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'text unsigned' => [
                'field_type' => 'text',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text unsigned not null' => [
                'field_type' => 'text',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'text unsigned not null default' => [
                'field_type' => 'text',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'text min' => [
                'field_type' => 'text',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            'text max' => [
                'field_type' => 'text',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'aze', '-1', '10', '50.', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'text range' => [
                'field_type' => 'text',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            // timestamp
            'timestamp' => [
                'field_type' => 'timestamp',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp default' => [
                'field_type' => 'timestamp',
                'rules'      => [],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => ['2000-01-01 00:00:00', null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp not null' => [
                'field_type' => 'timestamp',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp not null default' => [
                'field_type' => 'timestamp',
                'rules'      => ['not_null'],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp PK' => [
                'field_type' => 'timestamp',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp PK not null' => [
                'field_type' => 'timestamp',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp FK' => [
                'field_type' => 'timestamp',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp FK not null' => [
                'field_type' => 'timestamp',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp unsigned' => [
                'field_type' => 'timestamp',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp unsigned not null' => [
                'field_type' => 'timestamp',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp unsigned not null default' => [
                'field_type' => 'timestamp',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '2000-01-01 00:00:00',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => ['2000-01-01 00:00:00', FieldException::class, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, 'Null not authorized', 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp min' => [
                'field_type' => 'timestamp',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp max' => [
                'field_type' => 'timestamp',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            'timestamp range' => [
                'field_type' => 'timestamp',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '1970-01-01 00:00:00', '2038-01-19 03:14:07', '1969-01-01 00:00:00', '2038-01-19 03:14:08', '2000-01-01 50:00:00', '2000-51-91 50:00:00', '2000-01-01'],
                'expected'   => [null, null, FieldException::class, FieldException::class, '1970-01-01 00:00:10', '1970-01-01 00:00:50', FieldException::class, '1970-01-01 00:00:00', '2038-01-19 03:14:07', FieldException::class, FieldException::class, FieldException::class, FieldException::class, '2000-01-01 00:00:00'],
                'message'    => [null, null, 'Invalid timestamp value', 'Invalid timestamp value', null, null, 'Invalid timestamp value', null, null, 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', 'Invalid timestamp value', null]
            ],
            // time
            'time' => [
                'field_type' => 'time',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time default' => [
                'field_type' => 'time',
                'rules'      => [],
                'default'    => '00:04:04',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => ['00:04:04', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time not null' => [
                'field_type' => 'time',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time not null default' => [
                'field_type' => 'time',
                'rules'      => ['not_null'],
                'default'    => '00:04:04',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time PK' => [
                'field_type' => 'time',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time PK not null' => [
                'field_type' => 'time',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time FK' => [
                'field_type' => 'time',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time FK not null' => [
                'field_type' => 'time',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time unsigned' => [
                'field_type' => 'time',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time unsigned not null' => [
                'field_type' => 'time',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time unsigned not null default' => [
                'field_type' => 'time',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '00:04:04',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => ['00:04:04', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time min' => [
                'field_type' => 'time',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time max' => [
                'field_type' => 'time',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            'time range' => [
                'field_type' => 'time',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '11:12:00', '11:12:60', '11:62:50', '31:12:50', '00:00:00', '23:59:59', '24:59:59'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, '11:12:00', FieldException::class,  FieldException::class, FieldException::class, '00:00:00', '23:59:59', FieldException::class],
                'message'    => [null, null, 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', 'Invalid time value', null, 'Invalid time value', 'Invalid time value', 'Invalid time value', null, null, 'Invalid time value']
            ],
            // varchar
            'varchar email' => [
                'field_type' => 'varchar',
                'rules'      => ['email'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email default' => [
                'field_type' => 'varchar',
                'rules'      => ['email'],
                'default'    => 'az@az',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => ['az@az', null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email not null' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email not null default' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'not_null'],
                'default'    => 'az@az',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email PK' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email PK not null' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email FK' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email FK not null' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email unsigned' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email unsigned not null' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email unsigned not null default' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'unsigned', 'not_null'],
                'default'    => 'az@az',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => ['az@az', FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, 'Null not authorized', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email min' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'min:1'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email max' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            'varchar email range' => [
                'field_type' => 'varchar',
                'rules'      => ['email', 'range:1,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', '@', 'a@', '@a', 'a@a'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 'a@a'],
                'message'    => [null, null, 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid email value', 'Invalid min length', 'Invalid email value', 'Invalid email value', 'Invalid email value', null]
            ],
            // varchar
            'varchar' => [
                'field_type' => 'varchar',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar default' => [
                'field_type' => 'varchar',
                'rules'      => [],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar not null' => [
                'field_type' => 'varchar',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'varchar not null default' => [
                'field_type' => 'varchar',
                'rules'      => ['not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'varchar PK' => [
                'field_type' => 'varchar',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar PK not null' => [
                'field_type' => 'varchar',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'varchar FK' => [
                'field_type' => 'varchar',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar FK not null' => [
                'field_type' => 'varchar',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'varchar unsigned' => [
                'field_type' => 'varchar',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar unsigned not null' => [
                'field_type' => 'varchar',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [FieldException::class, FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => ['Invalid default value', 'Null not authorized', null, null, null, null, null]
            ],
            'varchar unsigned not null default' => [
                'field_type' => 'varchar',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => '999',
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => ['999', FieldException::class, 'azerty', '-1', '10', '50.5', ''],
                'message'    => [null, 'Null not authorized', null, null, null, null, null]
            ],
            'varchar min' => [
                'field_type' => 'varchar',
                'rules'      => ['min:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azerty', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            'varchar max' => [
                'field_type' => 'varchar',
                'rules'      => ['max:3'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'aze', '-1', '10', '50.', ''],
                'message'    => [null, null, null, null, null, null, null]
            ],
            'varchar range' => [
                'field_type' => 'varchar',
                'rules'      => ['range:3,4'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, 'azer', FieldException::class, FieldException::class, '50.5', FieldException::class],
                'message'    => [null, null, null, 'Invalid min length', 'Invalid min length', null, 'Invalid min length']
            ],
            // year
            'year' => [
                'field_type' => 'year',
                'rules'      => [],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year default' => [
                'field_type' => 'year',
                'rules'      => [],
                'default'    => 2000,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [2000, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year not null' => [
                'field_type' => 'year',
                'rules'      => ['not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year not null default' => [
                'field_type' => 'year',
                'rules'      => ['not_null'],
                'default'    => 2000,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year PK' => [
                'field_type' => 'year',
                'rules'      => ['pk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year PK not null' => [
                'field_type' => 'year',
                'rules'      => ['pk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year FK' => [
                'field_type' => 'year',
                'rules'      => ['fk'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year FK not null' => [
                'field_type' => 'year',
                'rules'      => ['fk', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year unsigned' => [
                'field_type' => 'year',
                'rules'      => ['unsigned'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year unsigned not null' => [
                'field_type' => 'year',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => ['Invalid default value', 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year unsigned not null default' => [
                'field_type' => 'year',
                'rules'      => ['unsigned', 'not_null'],
                'default'    => 2000,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [2000, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2155, FieldException::class],
                'message'    => [null, 'Null not authorized', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, 'Invalid year value']
            ],
            'year min' => [
                'field_type' => 'year',
                'rules'      => ['min:1999'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class, 1999, 1999, 2000, 2155, FieldException::class],
                'message'    => [null, null, 'Invalid year value', null, null, null, 'Invalid year value', null, null, null, null, 'Invalid year value']
            ],
            'year max' => [
                'field_type' => 'year',
                'rules'      => ['max:2005'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, FieldException::class, 1901, 2000, 2005, 2005],
                'message'    => [null, null, 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', 'Invalid year value', null, null, null, null]
            ],
            'year range' => [
                'field_type' => 'year',
                'rules'      => ['range:1999,2050'],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, '', 1900, '1901', 2000, 2155, '2156'],
                'expected'   => [null, null, FieldException::class, 1999, 1999, 1999, FieldException::class, 1999, 1999, 2000, 2050, 2050],
                'message'    => [null, null, 'Invalid year value', null, null, null, 'Invalid year value', null, null, null, null, null]
            ],
            // custom
            'custom rule' => [
                'field_type' => 'char',
                'rules'      => [new MyRule()],
                'default'    => false,
                'input'      => [false, null, 'azerty', '-1', '10', 50.50, ''],
                'expected'   => [null, null, FieldException::class, '-1', '10', '50.5', ''],
                'message'    => [null, null, 'invalid azerty value', null, null, null, null]
            ],
        ];
        // phpcs:enable
    }

    // endregion

    /**
     * @dataProvider data
     *
     * @param string $fieldType
     * @param array  $rules
     * @param mixed  $default
     * @param array  $input
     * @param array  $expected
     * @param array  $message
     *
     * @throws FieldException
     */
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
                static::assertSame($expected[$i], \get_class($e));
            }
        }
    }
}
