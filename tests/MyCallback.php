<?php

declare(strict_types=1);

namespace tests;

/**
 * Class MyCallback.
 */
class MyCallback
{
    public function before($sql, $params): array
    {
        $sql = 'INSERT INTO crud_table (`title`,`date_start`, `year_start`) VALUES (:title,:date_start, :year_start)';
        $params['year_start'] = 2000;

        return [$sql, $params];
    }
}
