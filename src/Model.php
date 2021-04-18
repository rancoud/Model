<?php

/** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);

namespace Rancoud\Model;

use Rancoud\Database\Database;
use Rancoud\Database\DatabaseException;

/**
 * Class Model.
 */
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

    /**
     * Model constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        $this->setFields();
        $this->setTable();
    }

    abstract protected function setFields(): void;

    abstract protected function setTable(): void;

    /**
     * @param string $field
     *
     * @return bool
     */
    protected function isValidField(string $field): bool
    {
        return \array_key_exists($field, $this->fields);
    }

    /**
     * @throws ModelException
     *
     * @return string
     */
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

    /**
     * @param string $sql
     *
     * @throws ModelException
     *
     * @return string
     */
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

    /**
     * @throws ModelException
     *
     * @return string
     */
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

    /**
     * @param array $params
     *
     * @throws ModelException
     *
     * @return array
     */
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

    /**
     * @param array $params
     * @param array $validFields
     *
     * @throws ModelException
     *
     * @return array|int|null
     */
    public function all(array $params, array $validFields = [])
    {
        $this->resetAllErrors();

        if (Helper::isRowsCount($params)) {
            return $this->allCount($params);
        }

        return $this->allRows($params, $validFields);
    }

    /**
     * @param array $params
     *
     * @throws ModelException
     *
     * @return int|null
     */
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
        } catch (DatabaseException $databaseException) {
            $this->addErrorMessage('Error select count all');
            throw new ModelException('ERROR');
        }
    }

    /**
     * @param array $params
     * @param array $validFields
     *
     * @throws ModelException
     *
     * @return array
     */
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
        } catch (DatabaseException $databaseException) {
            $this->addErrorMessage('Error select all');
            throw new ModelException('ERROR');
        }
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function getSqlAllSelectAndFillSqlParams(array $params): string
    {
        return $this->table . '.*';
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function getSqlAllJoinAndFillSqlParams(array $params): string
    {
        return '';
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function getSqlAllWhereAndFillSqlParams(array $params): string
    {
        return '1=1';
    }

    /**
     * @return array|null
     */
    public function getDatabaseErrors(): ?array
    {
        return $this->database->getErrors();
    }

    /**
     * @return array|null
     */
    public function getDatabaseLastError(): ?array
    {
        return $this->database->getLastError();
    }

    /**
     * @param mixed $id
     * @param mixed ...$ids
     *
     * @throws ModelException
     *
     * @return array|null
     */
    public function one($id, ...$ids): ?array
    {
        \array_unshift($ids, $id);
        $this->sqlParams = [];

        $sqlWhere = $this->getWhereWithPrimaryKeys($ids);

        $sql = 'SELECT * FROM ' . $this->table . ' WHERE ' . \implode(' AND ', $sqlWhere);

        try {
            $row = $this->database->selectRow($sql, $this->sqlParams);

            return $this->formatValues($row);
        } catch (DatabaseException $databaseException) {
            $this->addErrorMessage('Error select one');
            throw new ModelException('ERROR');
        }
    }

    /**
     * @param array $args
     *
     * @throws ModelException
     *
     * @return int
     */
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
            /* @noinspection PhpFieldAssignmentTypeMismatchInspection */
            $this->lastInsertId = $this->database->insert($sql, $this->sqlParams, true);
        } catch (DatabaseException $de) {
            $this->addErrorMessage('Error creating');
            throw new ModelException('ERROR');
        }

        $this->afterCreate($this->lastInsertId, $this->sqlParams);

        return $this->lastInsertId;
    }

    /**
     * @return int|null
     */
    public function getLastInsertId(): ?int
    {
        return $this->lastInsertId;
    }

    /**
     * @param array $args
     * @param mixed $id
     * @param mixed ...$ids
     *
     * @throws ModelException
     */
    public function update(array $args, $id, ...$ids): void
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
        } catch (DatabaseException $de) {
            $this->addErrorMessage('Error updating');
            throw new ModelException('ERROR');
        }

        $this->afterUpdate($this->sqlParams);
    }

    /**
     * @param $ids
     *
     * @throws ModelException
     *
     * @return array
     */
    protected function getWhereWithPrimaryKeys($ids): array
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

    /**
     * @param mixed $id
     * @param mixed ...$ids
     *
     * @throws ModelException
     */
    public function delete($id, ...$ids): void
    {
        $this->resetAllErrors();

        \array_unshift($ids, $id);
        $this->sqlParams = [];

        $sqlWhere = $this->getWhereWithPrimaryKeys($ids);

        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . \implode(' AND ', $sqlWhere);

        $this->beforeDelete($sql, $this->sqlParams);

        try {
            $this->database->delete($sql, $this->sqlParams);
        } catch (DatabaseException $de) {
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
     * @param string $mode
     *
     * @throws ModelException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function treatParametersAfterClean(string $mode): void
    {
    }

    /**
     * @param $callbacks
     * @param $sql
     * @param $params
     *
     * @throws ModelException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function beforeCallbacks($callbacks, &$sql, &$params): void
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
     * @param       $callbacks
     * @param mixed ...$params
     *
     * @throws ModelException
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function afterCallbacks($callbacks, ...$params): void
    {
        $indexArrayParams = 0;
        if (\count($params) === 2) {
            $indexArrayParams = 1;
        }

        foreach ($callbacks as $callback) {
            $ret = null;

            if (\is_callable($callback)) {
                $ret = \call_user_func_array($callback, $params);
            }

            if (\is_array($ret)) {
                $params[$indexArrayParams] = $ret;
            }
        }
    }

    /**
     * @param &$sql
     * @param &$params
     *
     * @throws ModelException
     */
    protected function beforeCreate(&$sql, &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bc'], $sql, $params);
    }

    /**
     * @param $newId
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterCreate($newId, $params): void
    {
        $this->afterCallbacks($this->callbacksCud['ac'], $newId, $params);
    }

    /**
     * @param &$sql
     * @param &$params
     *
     * @throws ModelException
     */
    protected function beforeUpdate(&$sql, &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bu'], $sql, $params);
    }

    /**
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterUpdate($params): void
    {
        $this->afterCallbacks($this->callbacksCud['au'], $params);
    }

    /**
     * @param &$sql
     * @param &$params
     *
     * @throws ModelException
     */
    protected function beforeDelete(&$sql, &$params): void
    {
        $this->beforeCallbacks($this->callbacksCud['bd'], $sql, $params);
    }

    /**
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterDelete($params): void
    {
        $this->afterCallbacks($this->callbacksCud['ad'], $params);
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addBeforeCreate(string $name, $callback): void
    {
        $this->callbacksCud['bc'][$name] = $callback;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addAfterCreate(string $name, $callback): void
    {
        $this->callbacksCud['ac'][$name] = $callback;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addBeforeUpdate(string $name, $callback): void
    {
        $this->callbacksCud['bu'][$name] = $callback;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addAfterUpdate(string $name, $callback): void
    {
        $this->callbacksCud['au'][$name] = $callback;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addBeforeDelete(string $name, $callback): void
    {
        $this->callbacksCud['bd'][$name] = $callback;
    }

    /**
     * @param string   $name
     * @param \Closure $callback
     */
    public function addAfterDelete(string $name, $callback): void
    {
        $this->callbacksCud['ad'][$name] = $callback;
    }

    /**
     * @param string $name
     */
    public function removeBeforeCreate(string $name): void
    {
        unset($this->callbacksCud['bc'][$name]);
    }

    /**
     * @param string $name
     */
    public function removeAfterCreate(string $name): void
    {
        unset($this->callbacksCud['ac'][$name]);
    }

    /**
     * @param string $name
     */
    public function removeBeforeUpdate(string $name): void
    {
        unset($this->callbacksCud['bu'][$name]);
    }

    /**
     * @param string $name
     */
    public function removeAfterUpdate(string $name): void
    {
        unset($this->callbacksCud['au'][$name]);
    }

    /**
     * @param string $name
     */
    public function removeBeforeDelete(string $name): void
    {
        unset($this->callbacksCud['bd'][$name]);
    }

    /**
     * @param string $name
     */
    public function removeAfterDelete(string $name): void
    {
        unset($this->callbacksCud['ad'][$name]);
    }
}
