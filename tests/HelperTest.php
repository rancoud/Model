<?php

declare(strict_types=1);

use Rancoud\Model\Helper;
use PHPUnit\Framework\TestCase;

/**
 * Class HelperTest
 */
class HelperTest extends TestCase
{
    public function testIsTotalCount()
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

    public function testGetPageNumberForSql()
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

    public function testGetPageNumberForHuman()
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

     public function testGetCountPerPage()
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

    public function testGetLimitOffsetCount()
    {
        $limitOffset = Helper::getLimitOffsetCount(['page' => '']);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '1']);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount([]);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '10']);
        static::assertSame([450, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '0']);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['page' => '-1']);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 100]);
        static::assertSame([0, 100], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount([]);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => -100]);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 0]);
        static::assertSame([0, 50], $limitOffset);

        $limitOffset = Helper::getLimitOffsetCount(['count' => 1]);
        static::assertSame([0, 1], $limitOffset);
    }
    
    public function testGetOrderField()
    {
        $args = Helper::getOrderByOrderField([]);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '']);
        static::assertSame([['id' => 'asc']], $args);
        
        $args = Helper::getOrderByOrderField(['order' => 'title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|desc'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'desc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|desc,id|desc'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'desc'], ['id' => 'desc']], $args);

        $args = Helper::getOrderByOrderField(['order' => ' title '], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|title|title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title,'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => 'title|,'], ['title' => 'aze', 'id' => 1]);
        static::assertSame([['title' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '|desc']);
        static::assertSame([['id' => 'asc']], $args);

        $args = Helper::getOrderByOrderField(['order' => '|desc,|asc']);
        static::assertSame([['id' => 'asc']], $args);
        
        $args = Helper::getOrderByOrderField(['order' => 'toto']);
        static::assertSame([['id' => 'asc']], $args);
    }

    public function testHasInvalidPrimaryKey()
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

    public function testHasLimit()
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

    public function testImplodeOrder()
    {
        $args = Helper::getOrderByOrderField([]);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|desc'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title desc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|desc,id|desc'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title desc,id desc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => ' title '], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|title|title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,title,title'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title,'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'title|,'], ['title' => 'aze', 'id' => 1]);
        static::assertSame('title asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '|desc']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => '|desc,|asc']);
        static::assertSame('id asc', Helper::implodeOrder($args));

        $args = Helper::getOrderByOrderField(['order' => 'toto']);
        static::assertSame('id asc', Helper::implodeOrder($args));
    }
}