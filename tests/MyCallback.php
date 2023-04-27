<?php

/** @noinspection SqlDialectInspection */

declare(strict_types=1);

namespace tests;

/**
 * Class MyCallback.
 */
class MyCallback
{
    /** @noinspection PhpUnusedParameterInspection */
    public function before($sql, $params): array
    {
        $params['year_start'] = 2000;

        return [
            'INSERT INTO crud_table (`title`,`date_start`, `year_start`) VALUES (:title,:date_start, :year_start)',
            $params
        ];
    }
}
