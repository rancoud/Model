<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\Field;
use Rancoud\Model\FieldException;
use Rancoud\Model\Model;
use Rancoud\Model\ModelException;

/** @internal */
class ImplementModel extends Model
{
    protected array $parametersToRemove = ['param_to_remove'];

    protected bool $isHackWrong = false;

    public function __construct($database)
    {
        parent::__construct($database);
    }

    /** @throws FieldException */
    protected function setFields(): void
    {
        $this->fields = [
            'id'          => new Field('int', ['pk', 'unsigned', 'not_null']),
            'title'       => new Field('varchar', ['max:5']),
            'date_start'  => new Field('datetime', ['not_null']),
            'year_start'  => new Field('year', []),
            'hour_start'  => new Field('time', [], '00:00:00'),
            'hour_stop'   => new Field('time', [], null),
            'is_visible'  => new Field('enum:yes,no', ['not_null'], 'yes'),
            'email'       => new Field('varchar', ['email']),
            'nomaxlimit'  => new Field('varchar', ['max:']),
            'external_id' => new Field('int', ['fk', 'unsigned'])
        ];
    }

    protected function setTable(): void
    {
        $this->table = 'crud_table';
    }

    public function setHackWrongGetSqlAllWhereAndFillSqlParams(bool $bool): void
    {
        $this->isHackWrong = $bool;
    }

    protected function getSqlAllWhereAndFillSqlParams(array $params): string
    {
        return ($this->isHackWrong) ? 'AND' : parent::getSqlAllWhereAndFillSqlParams($params);
    }

    public function removePkFields(): void
    {
        \array_shift($this->fields);
    }

    /** @throws FieldException */
    public function setWrongPk(): void
    {
        $this->fields['wrong_id'] = new Field('int', ['pk', 'unsigned', 'not_null']);
    }

    /** @throws ModelException */
    public function hackCreateSqlFieldsFromParams(): string
    {
        $this->sqlParams = ['invalid', 'key' => 'value'];

        return $this->getCreateSqlFieldsFromParams();
    }

    /** @throws ModelException */
    public function hackUpdateSqlFieldsFromParams(): string
    {
        $this->sqlParams = ['invalid', 'key' => 'value'];

        return $this->getUpdateSqlFieldsFromParams();
    }

    /** @throws FieldException */
    public function usePostgresql(): void
    {
        $this->fields = [
            'date_start' => new Field('timestamp', ['not_null'])
        ];
    }
}
