<?php

/** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

namespace Rancoud\Model;

use Rancoud\Database\Database;
use Rancoud\Database\DatabaseException;

abstract class Model extends ErrorWarning
{
    protected Database $database;

    protected string $table;

    /** @var Field[] */
    protected array $fields = [];

    protected array $sqlParams = [];

    protected array $parametersToRemove = [];

    protected int $lastInsertId;

    protected array $callbacksCud = [
        'bc' => [],
        'ac' => [],
        'bu' => [],
        'au' => [],
        'bd' => [],
        'ad' => [],
    ];

    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->setFields();
        $this->setTable();
    }

    abstract protected function setFields(): void;

    abstract protected function setTable(): void;

    protected function isValidField(string $field): bool
    {
        return \array_key_exists($field, $this->fields);
    }

    /** @throws ModelException */
    protected function getCreateSqlFieldsFromParams(): string
    {
        $sqlFields = $sqlValues = [];

        foreach ($this->sqlParams as $field => $value) {
            if (\is_int($field) || !$this->isValidField($field)) {
                continue;
            }

            $sqlFields[] = $field;
            $sqlValues[] = ':' . $field;
        }

        $sql = '(' . \implode(',', $sqlFields) . ') VALUES (' . \implode(',', $sqlValues) . ')';

        if ($sql === '() VALUES ()') {
            $this->addErrorMessage('Insert sql query is empty');

            throw new ModelException('ERROR');
        }

        return $sql;
    }

    /** @throws ModelException */
    protected function checkNotNullFieldIsPresent(string $sql): string
    {
        foreach ($this->fields as $key => $field) {
            if (!$field->isNotNull()) {
                continue;
            }

            if ($field->isPrimaryKey()) {
                continue;
            }

            if (!\array_key_exists($key, $this->sqlParams)) {
                if ($field->getDefault() === false) {
                    $this->addErrorField($key, 'field is required');
                } else {
                    $this->sqlParams[$key] = $field->getDefault();
                    $sql = \str_replace(') VALUES', ',' . $key . ') VALUES', $sql);
                    $sql .= ',:' . $key . ')';
                    $sql = \str_replace('),:' . $key . ')', ',:' . $key . ')', $sql);
                }
            }
        }

        if ($this->hasErrorFields()) {
            $this->addErrorMessage('Missing required fields');

            throw new ModelException('FIELDS');
        }

        return $sql;
    }

    /** @throws ModelException */
    protected function getUpdateSqlFieldsFromParams(): string
    {
        $sql = [];

        $excludePrimaryKey = [];
        foreach ($this->fields as $field => $rule) {
            if ($rule->isPrimaryKey()) {
                $excludePrimaryKey[] = $field;

                break;
            }
        }

        foreach ($this->sqlParams as $field => $rule) {
            if (\in_array($field, $excludePrimaryKey, true)) {
                continue;
            }

            if (\is_int($field) || !$this->isValidField($field)) {
                continue;
            }

            $sql[] = $field . ' = :' . $field;
        }

        $str = \implode(',', $sql);

        if ($str === '') {
            $this->addErrorMessage('Update sql query is empty');

            throw new ModelException('ERROR');
        }

        return $str;
    }

    /** @throws ModelException */
    protected function formatValues(array $params): array
    {
        $newParams = [];

        foreach ($params as $field => $value) {
            if (\is_int($field) || !$this->isValidField($field)) {
                continue;
            }

            try {
                $newParams[$field] = $this->fields[$field]->formatValue($value);
            } catch (FieldException $fieldException) {
                $this->addErrorField($field, $fieldException->getMessage());
            }
        }

        if ($this->hasErrorFields()) {
            $this->addErrorMessage('Formating values invalid');

            throw new ModelException('FIELDS');
        }

        return $newParams;
    }

    /** @throws ModelException */
    public function all(array $params, array $validFields = []): array|int|null
    {
        $this->resetAllErrors();

        if (Helper::isRowsCount($params)) {
            return $this->allCount($params);
        }

        return $this->allRows($params, $validFields);
    }

    /** @throws ModelException */
    protected function allCount(array $params): ?int
    {
        $sql = [];
        $this->sqlParams = [];

        $sql[] = 'SELECT count(*)';
        $sql[] = 'FROM ' . $this->table;
        $sql[] = $this->getSqlAllJoinAndFillSqlParams($params);
        $sql[] = 'WHERE';
        $sql[] = $this->getSqlAllWhereAndFillSqlParams($params);

        try {
            return $this->database->count(\implode(' ', $sql), $this->sqlParams);
        } catch (DatabaseException) {
            $this->addErrorMessage('Error select count all');

            throw new ModelException('ERROR');
        }
    }

    /** @throws ModelException */
    protected function allRows(array $params, array $validFields = []): array
    {
        $sql = [];
        $this->sqlParams = [];

        $orders = Helper::getOrderByOrderField($params, $validFields);
        [$count, $offset] = Helper::getLimitOffsetCount($params);

        $sql[] = 'SELECT ' . $this->getSqlAllSelectAndFillSqlParams($params);
        $sql[] = 'FROM ' . $this->table;
        $sql[] = $this->getSqlAllJoinAndFillSqlParams($params);
        $sql[] = 'WHERE';
        $sql[] = $this->getSqlAllWhereAndFillSqlParams($params);
        $sql[] = 'ORDER BY ' . Helper::implodeOrder($orders);

        if (Helper::hasLimit($params)) {
            $sql[] = 'LIMIT :count OFFSET :offset';
            $this->sqlParams['count'] = $count;
            $this->sqlParams['offset'] = $offset;
        }

        try {
            $rows = $this->database->selectAll(\implode(' ', $sql), $this->sqlParams);
            foreach ($rows as $key => $row) {
                $rows[$key] = $this->formatValues($row);
            }

            return $rows;
        } catch (DatabaseException) {
            $this->addErrorMessage('Error select all');

            throw new ModelException('ERROR');
        }
    }

    /** @noinspection PhpUnusedParameterInspection */
    protected function getSqlAllSelectAndFillSqlParams(array $params): string
    {
        return $this->table . '.*';
    }

    /** @noinspection PhpUnusedParameterInspection */
    protected function getSqlAllJoinAndFillSqlParams(array $params): string
    {
        return '';
    }

    protected function getSqlAllWhereAndFillSqlParams(array $params): string
    {
        return '1=1';
    }

    public function getDatabaseErrors(): ?array
    {
        return $this->database->getErrors();
    }

    public function getDatabaseLastError(): ?array
    {
        return $this->database->getLastError();
    }

    /** @throws ModelException */
    public function one(mixed $id, mixed ...$ids): ?array
    {
        \array_unshift($ids, $id);
        $this->sqlParams = [];

        $sqlWhere = $this->getWhereWithPrimaryKeys($ids);

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . \implode(' AND ', $sqlWhere);

        try {
            $row = $this->database->selectRow($sql, $this->sqlParams);

            return $this->formatValues($row);
        } catch (DatabaseException) {
            $this->addErrorMessage('Error select one');

            throw new ModelException('ERROR');
        }
    }

    /** @throws ModelException */
    public function create(array $args): int
    {
        $this->resetAllErrors();

        $this->sqlParams = $args;

        $this->removeParameters();

        $this->sqlParams = $this->formatValues($this->sqlParams);

        $this->treatParametersAfterClean('create');

        $sqlCreateFields = $this->getCreateSqlFieldsFromParams();

        $sql = 'INSERT INTO ' . $this->table . ' ' . $sqlCreateFields;

        $this->beforeCreate($sql, $this->sqlParams);

        $sql = $this->checkNotNullFieldIsPresent($sql);

        try {
            $this->lastInsertId = $this->database->insert($sql, $this->sqlParams, true);
        } catch (DatabaseException) {
            $this->addErrorMessage('Error creating');

            throw new ModelException('ERROR');
        }

        $this->afterCreate($this->lastInsertId, $this->sqlParams);

        return $this->lastInsertId;
    }

    public function getLastInsertId(): ?int
    {
        return $this->lastInsertId;
    }

    /** @throws ModelException */
    public function update(array $args, mixed $id, mixed ...$ids): void
    {
        $this->resetAllErrors();

        \array_unshift($ids, $id);

        $this->sqlParams = $args;

        $sqlWhere = $this->getWhereWithPrimaryKeys($ids);

        $this->removeParameters();

        $this->sqlParams = $this->formatValues($this->sqlParams);

        $this->treatParametersAfterClean('update');

        $sqlUpdateFields = $this->getUpdateSqlFieldsFromParams();

        $sql = 'UPDATE ' . $this->table . ' SET ' . $sqlUpdateFields . ' WHERE ' . \implode(' AND ', $sqlWhere);

        $this->beforeUpdate($sql, $this->sqlParams);

        try {
            $this->database->update($sql, $this->sqlParams);
        } catch (DatabaseException) {
            $this->addErrorMessage('Error updating');

            throw new ModelException('ERROR');
        }

        $this->afterUpdate($this->sqlParams);
    }

    /** @throws ModelException */
    protected function getWhereWithPrimaryKeys(mixed $ids): array
    {
        $sqlWhere = [];
        foreach ($this->fields as $field => $properties) {
            if ($properties->isPrimaryKey()) {
                $id = \array_shift($ids);
                $this->sqlParams[$field] = \current($this->formatValues([$field => $id]));
                $sqlWhere[] = $field . ' =:' . $field;
            }
        }

        if (\count($sqlWhere) < 1) {
            $this->addErrorMessage('Error no primary key');

            throw new ModelException('ERROR');
        }

        return $sqlWhere;
    }

    /** @throws ModelException */
    public function delete(mixed $id, mixed ...$ids): void
    {
        $this->resetAllErrors();

        \array_unshift($ids, $id);
        $this->sqlParams = [];

        $sqlWhere = $this->getWhereWithPrimaryKeys($ids);

        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . \implode(' AND ', $sqlWhere);

        $this->beforeDelete($sql, $this->sqlParams);

        try {
            $this->database->delete($sql, $this->sqlParams);
        } catch (DatabaseException) {
            $this->addErrorMessage('Error deleting');

            throw new ModelException('ERROR');
        }

        $this->afterDelete($this->sqlParams);
    }

    protected function removeParameters(): void
    {
        foreach ($this->parametersToRemove as $parameterName) {
            if (\array_key_exists($parameterName, $this->sqlParams)) {
                unset($this->sqlParams[$parameterName]);
            }
        }
    }

    /**
     * @throws ModelException
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function treatParametersAfterClean(string $mode): void {}

    /**
     * @throws ModelException
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function beforeCallbacks(array $callbacks, string &$sql, array &$params): void
    {
        foreach ($callbacks as $callback) {
            $ret = null;

            if (\is_array($callback) && \is_object($callback[0])) {
                $ret = $callback[0]->{$callback[1]}($sql, $params);
            } elseif (\is_callable($callback)) {
                $ret = $callback($sql, $params);
            }

            if (\is_array($ret)) {
                [$sql, $params] = $ret;
            }
        }
    }

    /**
     * @throws ModelException
     *
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function afterCallbacks(array $callbacks, mixed ...$params): void
    {
        $indexArrayParams = 0;
        if (\count($params) === 2) {
            $indexArrayParams = 1;
        }

        foreach ($callbacks as $callback) {
            $ret = null;

            if (\is_callable($callback)) {
                $ret = $callback(...$params);
            }

            if (\is_array($ret)) {
                $params[$indexArrayParams] = $ret;
            }
        }
    }

    /** @throws ModelException */
    protected function beforeCreate(string &$sql, array &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bc'], $sql, $params);
    }

    /** @throws ModelException */
    protected function afterCreate(mixed $newId, array $params): void
    {
        $this->afterCallbacks($this->callbacksCud['ac'], $newId, $params);
    }

    /** @throws ModelException */
    protected function beforeUpdate(string &$sql, array &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bu'], $sql, $params);
    }

    /** @throws ModelException */
    protected function afterUpdate(array $params): void
    {
        $this->afterCallbacks($this->callbacksCud['au'], $params);
    }

    /** @throws ModelException */
    protected function beforeDelete(string &$sql, array &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bd'], $sql, $params);
    }

    /** @throws ModelException */
    protected function afterDelete(array $params): void
    {
        $this->afterCallbacks($this->callbacksCud['ad'], $params);
    }

    public function addBeforeCreate(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['bc'][$name] = $callback;
    }

    public function addAfterCreate(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['ac'][$name] = $callback;
    }

    public function addBeforeUpdate(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['bu'][$name] = $callback;
    }

    public function addAfterUpdate(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['au'][$name] = $callback;
    }

    public function addBeforeDelete(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['bd'][$name] = $callback;
    }

    public function addAfterDelete(string $name, array|\Closure $callback): void
    {
        $this->callbacksCud['ad'][$name] = $callback;
    }

    public function removeBeforeCreate(string $name): void
    {
        unset($this->callbacksCud['bc'][$name]);
    }

    public function removeAfterCreate(string $name): void
    {
        unset($this->callbacksCud['ac'][$name]);
    }

    public function removeBeforeUpdate(string $name): void
    {
        unset($this->callbacksCud['bu'][$name]);
    }

    public function removeAfterUpdate(string $name): void
    {
        unset($this->callbacksCud['au'][$name]);
    }

    public function removeBeforeDelete(string $name): void
    {
        unset($this->callbacksCud['bd'][$name]);
    }

    public function removeAfterDelete(string $name): void
    {
        unset($this->callbacksCud['ad'][$name]);
    }
}
