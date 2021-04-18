<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use Rancoud\Model\Helper;

/**
 * Class HelperTest.
 */
class HelperTest extends TestCase
{
    public function testIsTotalCount(): void
    {
        $bool = Helper::isRowsCount(['rows_count' => 1]);
        static::assertTrue($bool);

        $bool = Helper::isRowsCount(['rows_count' => '1']);
        static::assertTrue($bool);

        $bool = Helper::isRowsCount(['rows_count' => '']);
        static::assertFalse($bool);

        $bool = Helper::isRowsCount([]);
        static::assertFalse($bool);
    }

    public function testGetPageNumberForSql(): void
    {
        $pageIndex = Helper::getPageNumberForSql(['page' => '']);
        static::assertSame(0, $pageIndex);

        $pageIndex = Helper::getPageNumberForSql(['page' => '1']);
        static::assertSame(0, $pageIndex);

        $pageIndex = Helper::getPageNumberForSql([]);
        static::assertSame(0, $pageIndex);

        $pageIndex = Helper::getPageNumberForSql(['page' => '10']);
        static::assertSame(9, $pageIndex);

        $pageIndex = Helper::getPageNumberForSql(['page' => '0']);
        static::assertSame(0, $pageIndex);

        $pageIndex = Helper::getPageNumberForSql(['page' => '-1']);
        static::assertSame(0, $pageIndex);
    }

    public function testGetPageNumberForHuman(): void
    {
        $pageIndex = Helper::getPageNumberForHuman(['page' => '']);
        static::assertSame(1, $pageIndex);

        $pageIndex = Helper::getPageNumberForHuman(['page' => '1']);
        static::assertSame(1, $pageIndex);

        $pageIndex = Helper::getPageNumberForHuman([]);
        static::assertSame(1, $pageIndex);

        $pageIndex = Helper::getPageNumberForHuman(['page' => '10']);
        static::assertSame(10, $pageIndex);

        $pageIndex = Helper::getPageNumberForHuman(['page' => '0']);
        static::assertSame(1, $pageIndex);

        $pageIndex = Helper::getPageNumberForHuman(['page' => '-1']);
        static::assertSame(1, $pageIndex);
    }

    public function testGetCountPerPage(): void
    {
        $count = Helper::getCountPerPage(['count' => '']);
        static::assertSame(50, $count);

        $count = Helper::getCountPerPage(['count' => '100']);
        static::assertSame(100, $count);

        $count = Helper::getCountPerPage([]);
        static::assertSame(50, $count);

        $count = Helper::getCountPerPage(['count' => '-100']);
        static::assertSame(50, $count);

        $count = Helper::getCountPerPage(['count' => '0']);
        static::assertSame(50, $count);

        $count = Helper::getCountPerPage(['count' => '1']);
        static::assertSame(1, $count);
    }

    public function testGetLimitOffsetCount(): void
    {
        $limitOffset = Helper::getLimitOffsetCount(['page' => '']);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '1']);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount([]);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '10']);
        static::assertSame([50, 450], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '0']);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '-1']);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 100]);
        static::assertSame([100, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount([]);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => -100]);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 0]);
        static::assertSame([50, 0], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 1]);
        static::assertSame([1, 0], $limitOffset);
    }

    public function testGetOrderField(): void
    {
        $args = Helper::getOrderByOrderField([]);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '']);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|desc'], ['title', 'id']);
        static::assertSame([['title' => 'desc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|desc,id|desc'], ['title', 'id']);
        static::assertSame([['title' => 'desc'], ['id' => 'desc']], $args);

        $args = Helper::getOrderByOrderField(['order' => ' title '], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|title|title'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|,'], ['title', 'id']);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '|desc']);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '|desc,|asc']);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'toto']);
        static::assertSame([['id' => 'asc']], $args);
    }

    public function testHasInvalidPrimaryKey(): void
    {
        $dataInput = -10;
        $dataOutput = Helper::hasInvalidPrimaryKey($dataInput);
        static::assertTrue($dataOutput);

        $dataInput = -1;
        $dataOutput = Helper::hasInvalidPrimaryKey($dataInput);
        static::assertTrue($dataOutput);

        $dataInput = 0;
        $dataOutput = Helper::hasInvalidPrimaryKey($dataInput);
        static::assertTrue($dataOutput);

        $dataInput = 1;
        $dataOutput = Helper::hasInvalidPrimaryKey($dataInput);
        static::assertFalse($dataOutput);

        $dataInput = 10;
        $dataOutput = Helper::hasInvalidPrimaryKey($dataInput);
        static::assertFalse($dataOutput);
    }

    public function testHasLimit(): void
    {
        $bool = Helper::hasLimit(['no_limit' => 1]);
        static::assertFalse($bool);

        $bool = Helper::hasLimit(['no_limit' => '1']);
        static::assertFalse($bool);

        $bool = Helper::hasLimit(['no_limit' => '']);
        static::assertTrue($bool);

        $bool = Helper::hasLimit([]);
        static::assertTrue($bool);
    }

    public function testImplodeOrder(): void
    {
        $args = Helper::getOrderByOrderField([]);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|desc'], ['title', 'id']);
        static::assertSame('title desc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|desc,id|desc'], ['title', 'id']);
        static::assertSame('title desc,id desc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => ' title '], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|title|title'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|,'], ['title', 'id']);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '|desc']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '|desc,|asc']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'toto']);
        static::assertSame('id asc', Helper::implodeOrder($args));
    }
}
