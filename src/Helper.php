<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Class Helper.
 */
class Helper
{
    protected static $countPerPage = 50;
    protected static $rowsCount = 'rows_count';
    protected static $noLimit = 'no_limit';
    protected static $count = 'count';
    protected static $page = 'page';
    protected static $order = 'order';
    protected static $pipeDelimiter = '|';
    protected static $orderDelimiter = ',';
    protected static $defaultOrderBy = 'id';
    protected static $defaultOrderByOrder = 'asc';

    /**
     * @param array $args
     *
     * @return bool
     */
    public static function isRowsCount(array $args): bool
    {
        if (!array_key_exists(self::$rowsCount, $args)) {
            return false;
        }

        return (int) ($args[self::$rowsCount]) === 1;
    }

    /**
     * @param array $args
     *
     * @return bool
     */
    public static function hasLimit(array $args): bool
    {
        if (!array_key_exists(self::$noLimit, $args)) {
            return true;
        }

        return (int) ($args[self::$noLimit]) !== 1;
    }

    /**
     * @param array $args
     *
     * @return int
     */
    public static function getPageNumberForSql(array $args): int
    {
        $page = 0;
        if (!array_key_exists(self::$page, $args)) {
            return $page;
        }

        $page = (int) $args[self::$page];
        if ($page > 0) {
            return --$page;
        }

        return 0;
    }

    /**
     * @param array $args
     *
     * @return int
     */
    public static function getPageNumberForHuman(array $args): int
    {
        $page = 1;
        if (!array_key_exists(self::$page, $args)) {
            return $page;
        }

        $page = (int) $args[self::$page];
        if ($page > 1) {
            return $page;
        }

        return 1;
    }

    /**
     * @param array $args
     *
     * @return int
     */
    public static function getCountPerPage(array $args): int
    {
        if (!array_key_exists(self::$count, $args)) {
            return self::$countPerPage;
        }

        $count = (int) $args[self::$count];
        if ($count <= 0) {
            return self::$countPerPage;
        }

        return $count;
    }

    /**
     * @param array $args
     *
     * @return array
     */
    public static function getLimitOffsetCount(array $args): array
    {
        $count = self::getCountPerPage($args);
        $page = self::getPageNumberForSql($args);

        return [$count * $page, $count];
    }

    /**
     * @param array $args
     * @param array $validFields
     *
     * @return array
     */
    public static function getOrderByOrderField(array $args, array $validFields = []): array
    {
        $fieldsAlreadyTreated = [];

        if (!array_key_exists(self::$order, $args)) {
            return [[self::$defaultOrderBy => self::$defaultOrderByOrder]];
        }

        $order = (string) $args[self::$order];
        $parts = explode(self::$orderDelimiter, $order);

        $results = [];
        foreach ($parts as $part) {
            $field = trim($part);
            $order = self::$defaultOrderByOrder;

            $subparts = explode(self::$pipeDelimiter, $part);
            if (\count($subparts) > 1) {
                $field = trim($subparts[0]);

                if ($subparts[1] === 'asc' || $subparts[1] === 'desc') {
                    $field = trim($subparts[0]);
                    $order = $subparts[1];
                }
            }

            if (self::isValidFieldForOrderBy($field, $validFields)) {
                if (!\in_array($field, $fieldsAlreadyTreated, true)) {
                    $fieldsAlreadyTreated[] = $field;
                    $results[] = [$field => $order];
                }
            }
        }

        if (\count($results) === 0) {
            return [[self::$defaultOrderBy => self::$defaultOrderByOrder]];
        }

        return $results;
    }

    /**
     * @param string $field
     * @param array  $validFields
     *
     * @return bool
     */
    public static function isValidFieldForOrderBy(string $field, array $validFields = []): bool
    {
        return array_key_exists($field, $validFields);
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    public static function hasInvalidPrimaryKey(int $value): bool
    {
        return 1 > $value;
    }

    /**
     * @param array $orders
     *
     * @return string
     */
    public static function implodeOrder(array $orders): string
    {
        $sql = [];

        foreach ($orders as $order) {
            $sql[] = key($order) . ' ' . current($order);
        }

        return implode(',', $sql);
    }
}
