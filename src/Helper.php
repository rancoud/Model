<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Class Helper.
 */
class Helper
{
    protected static int $countPerPage = 50;
    protected static string $rowsCount = 'rows_count';
    protected static string $noLimit = 'no_limit';
    protected static string $count = 'count';
    protected static string $page = 'page';
    protected static string $order = 'order';
    protected static string $pipeDelimiter = '|';
    protected static string $orderDelimiter = ',';
    protected static string $defaultOrderBy = 'id';
    protected static string $defaultOrderByOrder = 'asc';

    public static function isRowsCount(array $args): bool
    {
        if (!\array_key_exists(self::$rowsCount, $args)) {
            return false;
        }

        return (int) $args[self::$rowsCount] === 1;
    }

    public static function hasLimit(array $args): bool
    {
        if (!\array_key_exists(self::$noLimit, $args)) {
            return true;
        }

        return (int) $args[self::$noLimit] !== 1;
    }

    public static function getPageNumberForSql(array $args): int
    {
        $page = 0;
        if (!\array_key_exists(self::$page, $args)) {
            return $page;
        }

        $page = (int) $args[self::$page];
        if ($page > 0) {
            return --$page;
        }

        return 0;
    }

    public static function getPageNumberForHuman(array $args): int
    {
        $page = 1;
        if (!\array_key_exists(self::$page, $args)) {
            return $page;
        }

        $page = (int) $args[self::$page];
        if ($page > 1) {
            return $page;
        }

        return 1;
    }

    public static function getCountPerPage(array $args): int
    {
        if (!\array_key_exists(self::$count, $args)) {
            return self::$countPerPage;
        }

        $count = (int) $args[self::$count];
        if ($count <= 0) {
            return self::$countPerPage;
        }

        return $count;
    }

    public static function getLimitOffsetCount(array $args): array
    {
        $count = self::getCountPerPage($args);
        $page = self::getPageNumberForSql($args);

        return [$count, $count * $page];
    }

    public static function getOrderByOrderField(array $args, array $validFields = []): array
    {
        $fieldsAlreadyTreated = [];

        if (!\array_key_exists(self::$order, $args)) {
            return [[self::$defaultOrderBy => self::$defaultOrderByOrder]];
        }

        $order = (string) $args[self::$order];
        $parts = \explode(self::$orderDelimiter, $order);

        $results = [];
        foreach ($parts as $part) {
            $field = \trim($part);
            $order = self::$defaultOrderByOrder;

            $subparts = \explode(self::$pipeDelimiter, $part);
            if (\count($subparts) > 1) {
                $field = \trim($subparts[0]);

                if ($subparts[1] === 'asc' || $subparts[1] === 'desc') {
                    $field = \trim($subparts[0]);
                    $order = $subparts[1];
                }
            }

            if (self::isValidFieldForOrderBy($field, $validFields) && !\in_array($field, $fieldsAlreadyTreated, true)) {
                $fieldsAlreadyTreated[] = $field;
                $results[] = [$field => $order];
            }
        }

        if (\count($results) === 0) {
            return [[self::$defaultOrderBy => self::$defaultOrderByOrder]];
        }

        return $results;
    }

    public static function isValidFieldForOrderBy(string $field, array $validFields = []): bool
    {
        return \in_array($field, $validFields, true);
    }

    public static function hasInvalidPrimaryKey(int $value): bool
    {
        return $value < 1;
    }

    public static function implodeOrder(array $orders): string
    {
        $sql = [];

        foreach ($orders as $order) {
            $sql[] = \key($order) . ' ' . \current($order);
        }

        return \implode(',', $sql);
    }
}
