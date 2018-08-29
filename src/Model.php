<?php

declare(strict_types=1);

namespace Rancoud\Model;

use Rancoud\Database\Database;
use Rancoud\Database\DatabaseException;

/**
 * Class Model.
 */
abstract class Model extends ErrorWarning
{
    /** @var Database */
    protected $database;

    /** @var string */
    protected $table;

    /** @var Field[] */
    protected $fields = [];

    /** @var array */
    protected $sqlParams = [];

    /** @var array */
    protected $parametersToRemove = [];

    /** @var int */
    protected $lastInsertId;

    protected static $callbacksCud = [
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

            $sqlFields[] = '`' . $field . '`';
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
                    $sql = \str_replace('`) VALUES', '`,`' . $key . '`) VALUES', $sql);
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

            $sql[] = '`' . $field . '` = :' . $field;
        }

        $str = \implode(',', $sql);

        if (\mb_strlen($str) < 1) {
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
     *
     * @throws ModelException
     *
     * @return array|bool|int
     */
    public function all(array $params)
    {
        $this->resetAllErrors();

        if (Helper::isRowsCount($params)) {
            return $this->allCount($params);
        }

        return $this->allRows($params);
    }

    /**
     * @param array $params
     *
     * @throws ModelException
     *
     * @return bool|int
     */
    protected function allCount(array $params)
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
     *
     * @throws ModelException
     *
     * @return array|bool
     */
    protected function allRows(array $params)
    {
        $sql = [];
        $this->sqlParams = [];

        $orders = Helper::getOrderByOrderField($params);
        list($offset, $count) = Helper::getLimitOffsetCount($params);

        $sql[] = 'SELECT ' . $this->getSqlAllSelectAndFillSqlParams($params);
        $sql[] = 'FROM ' . $this->table;
        $sql[] = $this->getSqlAllJoinAndFillSqlParams($params);
        $sql[] = 'WHERE';
        $sql[] = $this->getSqlAllWhereAndFillSqlParams($params);
        $sql[] = 'ORDER BY ' . Helper::implodeOrder($orders);

        if (Helper::hasLimit($params)) {
            $sql[] = 'LIMIT :offset, :count';
            $this->sqlParams['offset'] = $offset;
            $this->sqlParams['count'] = $count;
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
    public function getDatabaseErrors()
    {
        return $this->database->getErrors();
    }

    /**
     * @return array|null
     */
    public function getDatabaseLastError()
    {
        return $this->database->getLastError();
    }

    /**
     * @param       $id
     * @param array ...$ids
     *
     * @throws ModelException
     *
     * @return array
     */
    public function one($id, ...$ids)
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
     * @return bool|int
     */
    public function create(array $args)
    {
        $this->resetAllErrors();

        $this->sqlParams = [];

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
     * @param       $id
     * @param array ...$ids
     *
     * @throws ModelException
     */
    public function update(array $args, $id, ...$ids): void
    {
        $this->resetAllErrors();

        \array_unshift($ids, $id);
        $this->sqlParams = [];

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
    protected function getWhereWithPrimaryKeys($ids)
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
     * @param       $id
     * @param array ...$ids
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

    protected function removeParameters()
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
     */
    protected function treatParametersAfterClean(string $mode)
    {
    }

    /**
     * @param $callbacks
     * @param $sql
     * @param $params
     *
     * @throws ModelException
     */
    protected function beforeCallbacks($callbacks, &$sql, &$params)
    {
        foreach ($callbacks as $callback) {
            $ret = null;

            if (\is_array($callback) && \is_object($callback[0])) {
                $ret = $callback[0]->{$callback[1]}($sql, $params);
            } elseif (\is_callable($callback)) {
                $ret = \call_user_func($callback, $sql, $params);
            }

            if (\is_array($ret)) {
                $sql = $ret[0];
                $params = $ret[1];
            }
        }
    }

    /**
     * @param       $callbacks
     * @param array ...$params
     *
     * @throws ModelException
     */
    protected function afterCallbacks($callbacks, ...$params)
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
    protected function beforeCreate(&$sql, &$params)
    {
        $this->beforeCallbacks(static::$callbacksCud['bc'], $sql, $params);
    }

    /**
     * @param $newId
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterCreate($newId, $params)
    {
        $this->afterCallbacks(static::$callbacksCud['ac'], $newId, $params);
    }

    /**
     * @param &$sql
     * @param &$params
     *
     * @throws ModelException
     */
    protected function beforeUpdate(&$sql, &$params)
    {
        $this->beforeCallbacks(static::$callbacksCud['bu'], $sql, $params);
    }

    /**
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterUpdate($params)
    {
        $this->afterCallbacks(static::$callbacksCud['au'], $params);
    }

    /**
     * @param &$sql
     * @param &$params
     *
     * @throws ModelException
     */
    protected function beforeDelete(&$sql, &$params)
    {
        $this->beforeCallbacks(static::$callbacksCud['bd'], $sql, $params);
    }

    /**
     * @param $params
     *
     * @throws ModelException
     */
    protected function afterDelete($params)
    {
        $this->afterCallbacks(static::$callbacksCud['ad'], $params);
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addBeforeCreate($name, $callback)
    {
        static::$callbacksCud['bc'][$name] = $callback;
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addAfterCreate($name, $callback)
    {
        static::$callbacksCud['ac'][$name] = $callback;
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addBeforeUpdate($name, $callback)
    {
        static::$callbacksCud['bu'][$name] = $callback;
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addAfterUpdate($name, $callback)
    {
        static::$callbacksCud['au'][$name] = $callback;
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addBeforeDelete($name, $callback)
    {
        static::$callbacksCud['bd'][$name] = $callback;
    }

    /**
     * @param $name
     * @param $callback
     */
    public static function addAfterDelete($name, $callback)
    {
        static::$callbacksCud['ad'][$name] = $callback;
    }

    /**
     * @param $name
     */
    public static function removeBeforeCreate($name)
    {
        unset(static::$callbacksCud['bc'][$name]);
    }

    /**
     * @param $name
     */
    public static function removeAfterCreate($name)
    {
        unset(static::$callbacksCud['ac'][$name]);
    }

    /**
     * @param $name
     */
    public static function removeBeforeUpdate($name)
    {
        unset(static::$callbacksCud['bu'][$name]);
    }

    /**
     * @param $name
     */
    public static function removeAfterUpdate($name)
    {
        unset(static::$callbacksCud['au'][$name]);
    }

    /**
     * @param $name
     */
    public static function removeBeforeDelete($name)
    {
        unset(static::$callbacksCud['bd'][$name]);
    }

    /**
     * @param $name
     */
    public static function removeAfterDelete($name)
    {
        unset(static::$callbacksCud['ad'][$name]);
    }
}
