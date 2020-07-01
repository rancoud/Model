<?php
/** @noinspection ForgottenDebugOutputInspection */
/** @noinspection SqlDialectInspection */

declare(strict_types=1);

namespace Rancoud\Model\Test;

use Exception;
use Rancoud\Model\Model;
use Rancoud\Model\Field;
use Rancoud\Model\ModelException;
use PHPUnit\Framework\TestCase;

/**
 * Class ModelTest
 */
class ModelTest extends TestCase
{
    protected array $data = [
        [
            'id' => 1,
            'title' => 'first',
            'date_start' => '2018-08-07 20:06:23',
            'year_start' => null,
            'hour_start' => '00:00:00',
            'hour_stop' => null,
            'is_visible' => 'yes',
            'email' => null,
            'nomaxlimit' => null,
            'external_id' => null
        ],
        [
            'id' => 2,
            'title' => 'secon',
            'date_start' => '2018-08-07 20:06:23',
            'year_start' => null,
            'hour_start' => '00:00:00',
            'hour_stop' => null,
            'is_visible' => 'yes',
            'email' => null,
            'nomaxlimit' => null,
            'external_id' => null
        ],
        [
            'id' => 3,
            'title' => 'third',
            'date_start' => '2018-08-07 20:06:23',
            'year_start' => null,
            'hour_start' => '00:00:00',
            'hour_stop' => null,
            'is_visible' => 'yes',
            'email' => null,
            'nomaxlimit' => null,
            'external_id' => null
        ]
    ];

    /** @var \Rancoud\Database\Database */
    protected ?\Rancoud\Database\Database $database = null;

    /**
     * @throws \Rancoud\Database\DatabaseException
     */
    protected function initDatabaseConnexion(): void
    {
        if ($this->database !== null) {
            return;
        }

        $config = new \Rancoud\Database\Configurator([
            'engine' => 'mysql',
            'host' => '127.0.0.1',
            'user' => 'root',
            'password' => '',
            'database' => 'test_database',
            'save_queries' => true]);
        $this->database = new \Rancoud\Database\Database($config);
    }

    /**
     * @throws \Rancoud\Database\DatabaseException
     */
    protected function setUp(): void
    {
        $this->initDatabaseConnexion();
        parent::setUp();
    }

    /**
     * @throws \Rancoud\Database\DatabaseException
     */
    public function testInitDatabase(): void
    {
        $this->data[0]['date_start'] = date('Y-m-d H:i:s');

        $this->database->dropTables('crud_table');

        $sql = <<<SQL
            CREATE TABLE `crud_table` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `title` varchar(5) DEFAULT NULL,
              `date_start` datetime NOT NULL,
              `year_start` year(4) DEFAULT NULL,
              `hour_start` time DEFAULT '00:00:00',
              `hour_stop` time DEFAULT NULL,
              `is_visible` enum('yes', 'no') DEFAULT 'yes',
              `email` varchar(255) DEFAULT NULL,
              `nomaxlimit` varchar(255) DEFAULT NULL,
              `external_id` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `external_id_UNIQUE` (`external_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
SQL;
        $this->database->exec($sql);

        static::assertTrue(true);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    public function testCreateUpdateDeleteCleanErrorFields(): void
    {
        $implem = new ImplementModel($this->database);
        $countAssert = 1;
        try {
            $implem->create(['date_start' => '']);
        } catch (Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        try {
            $implem->create(['date_start' => '']);
        } catch (Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        try {
            $implem->update(['date_start' => ''], 1);
        } catch (Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        try {
            $implem->update(['date_start' => ''], 1);
        } catch (Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        $implem->setWrongPk();
        try {
            $implem->delete(1);
        } catch (Exception $e) {
            static::assertSame(['wrong_id' => ['Null not authorized']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        try {
            $implem->delete(1);
        } catch (Exception $e) {
            static::assertSame(['wrong_id' => ['Null not authorized']], $implem->getErrorFields());
            $countAssert--;
        }
        static::assertSame(0, $countAssert);
    }

    public function testCreateModelExceptionEmptySql(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);

        try {
            $implem->create([]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Insert sql query is empty'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testCreateModelExceptionMissingRequiredFields(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->create(['title' => 'a']);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Missing required fields'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame(['date_start' => ['field is required']], $implem->getErrorFields());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testCreateModelExceptionInvalidValues(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->create(['title' => 'a', 'date_start' => 'ggggg']);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testCreateModelExceptionErrorSql(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->addBeforeCreate('a', static function ($sql, $params) {
                $sql = 'sql query invalid';
                return [$sql, $params];
            });
            $implem->create(['title' => 'a', 'date_start' => '2000-01-01 01:01:01']);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error creating'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame('HY093', $implem->getDatabaseErrors()[0]['query_error'][0]);
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testCreateModelExceptionHackInvalidField(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);

        try {
            $implem->hackCreateSqlFieldsFromParams();
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Insert sql query is empty'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws ModelException
     */
    public function testCreate(): void
    {
        $implem = new ImplementModel($this->database);
        $id = $implem->create(['title' => $this->data[0]['title'], 'date_start' => $this->data[0]['date_start']]);
        static::assertSame(1, $id);
        $id = $implem->create(['title' => $this->data[1]['title'], 'date_start' => $this->data[1]['date_start'], 'params_to_remove' => 'this will be remove in parametersToRemove function']);
        static::assertSame(2, $id);
        $id = $implem->create(['title' => $this->data[2]['title'], 'date_start' => $this->data[2]['date_start'], 'extra_field' => 'this will be remove in create sql']);
        static::assertSame(3, $id);
        static::assertSame(3, $implem->getLastInsertId());
    }

    public function testAllModelExceptionErrorSql(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);
        $implem->setHackWrongGetSqlAllWhereAndFillSqlParams(true);
        try {
            $implem->all([]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error select all'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame('42000', $implem->getDatabaseErrors()[0]['query_error'][0]);
            $countAssert--;
        }

        static::assertSame(0, $countAssert);

        $countAssert = 3;

        $implem = new ImplementModel($this->database);
        $implem->setHackWrongGetSqlAllWhereAndFillSqlParams(true);
        try {
            $implem->all(['rows_count' => 1]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error select count all'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame('42000', $implem->getDatabaseLastError()['query_error'][0]);
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws ModelException
     */
    public function testAll(): void
    {
        $firstRow = [];
        $firstRow[]= $this->data[0];

        $implem = new ImplementModel($this->database);
        $rows = $implem->all([]);
        static::assertSame($this->data, $rows);

        $rows = $implem->all(['count' => 1]);
        static::assertSame($firstRow, $rows);
        
        $count = $implem->all(['rows_count' => 1]);
        static::assertSame(3, $count);
        
        $implem->all(['no_limit' => 1, 'count' => 1]);
        static::assertSame($firstRow, $rows);
    }

    public function testOneModelExceptionNoPrimaryKey(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);
        try {
            $implem->removePkFields();
            $implem->one(1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testOneModelExceptionInvalidPrimaryKey(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);
        try {
            $implem->one("invalid");
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    public function testOneModelExceptionErrorSelect(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);
        try {
            $implem->setWrongPk();
            $implem->one(1, 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error select one'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws ModelException
     */
    public function testOne(): void
    {
        $implem = new ImplementModel($this->database);
        $row = $implem->one(1);
        static::assertSame($this->data[0], $row);

        $row = $implem->one(1, 999);
        static::assertSame($this->data[0], $row);

        $row = $implem->one(999);
        static::assertSame([], $row);

        $row = $implem->one(999, 1);
        static::assertSame([], $row);
    }

    public function testUpdateModelExceptionNoPrimaryKey(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);
        try {
            $implem->removePkFields();
            $implem->update([], 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testUpdateModelExceptionEmptySql(): void
    {
        $countAssert = 2;

        try {
            $implem = new ImplementModel($this->database);
            $implem->update([], 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Update sql query is empty'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testUpdateModelExceptionInvalidValues(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->update(['title' => 'a', 'date_start' => 'ggggg'], 1);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    public function testUpdateModelExceptionErrorSql(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->addBeforeUpdate('a', function ($sql, $params) {
                $sql = 'sql query invalid';
                return [$sql, $params];
            });
            $implem->setWrongPk();
            $implem->update(['title' => 'a'], 1, 99);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error updating'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame('42000', $implem->getDatabaseErrors()[0]['query_error'][0]);
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testUpdateModelExceptionHackInvalidField(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);

        try {
            $implem->hackUpdateSqlFieldsFromParams();
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Update sql query is empty'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws ModelException
     */
    public function testUpdate(): void
    {
        $implem = new ImplementModel($this->database);
        $implem->update(['title' => 'youpie'], 1);

        $row = $implem->one(1);
        static::assertSame('youpi', $row['title']);

        $implem->update(['title' => 'first'], 1);

        $row = $implem->one(1);
        static::assertSame('first', $row['title']);
    }

    public function testDeleteModelExceptionNoPrimaryKey(): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->database);

        try {
            $implem->removePkFields();
            $implem->delete(1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    public function testDeleteModelExceptionErrorSql(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->addBeforeDelete('a', function ($sql, $params) {
                $sql = 'sql query invalid';
                return [$sql, $params];
            });
            $implem->setWrongPk();
            $implem->delete(1, 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Error deleting'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame('42000', $implem->getDatabaseErrors()[0]['query_error'][0]);
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    public function testDeleteModelExceptionInvalidValues(): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->database);

        try {
            $implem->delete("invalid");
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame(['id' => ['Invalid int value']], $implem->getErrorFields());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }

    /**
     * @throws ModelException
     */
    public function testDelete(): void
    {
        $implem = new ImplementModel($this->database);

        $row = $implem->one(1);
        static::assertSame($this->data[0], $row);

        $implem = new ImplementModel($this->database);
        $implem->delete(1);

        $row = $implem->one(1);
        static::assertSame([], $row);
    }

    public function testCallbacks(): void
    {
        $implem = new ImplementModel($this->database);
        $implem->addBeforeCreate('a', static function ($sql, $params) {
            static::assertSame("INSERT INTO crud_table (`title`,`date_start`) VALUES (:title,:date_start)", $sql);
            $sql = 'toto';

            static::assertSame('a', $params['title']);
            $params['title'] = 'az';

            return [$sql, $params];
        });
        
        $implem->addBeforeCreate('b', static function ($sql, $params) {
            static::assertSame("toto", $sql);
            $sql = "INSERT INTO crud_table (`title`,`date_start`, `year_start`) VALUES (:title,:date_start, :year_start)";
            $params['year_start'] = 1956;

            static::assertSame('az', $params['title']);
            $params['title'] = 'aze';

            return [$sql, $params];
        });
        
        $implem->addAfterCreate('a', static function ($newId, $params) {
            static::assertIsInt($newId);
            static::assertSame('aze', $params['title']);
            static::assertSame(1956, $params['year_start']);

            $params['other'] = 'done';

            return $params;
        });
        
        $implem->addAfterCreate('b', static function ($newId, $params) {
            static::assertSame('done', $params['other']);
        });
        
        $implem->addBeforeUpdate('a', static function ($sql, $params) {
            static::assertSame("UPDATE crud_table SET `title` = :title WHERE id =:id", $sql);
            $sql = 'toto';

            static::assertSame('b', $params['title']);
            $params['title'] = 'bz';

            return [$sql, $params];
        });
        
        $implem->addBeforeUpdate('b', static function ($sql, $params) {
            static::assertSame("toto", $sql);
            $sql = "UPDATE crud_table SET `title` = :title, `year_start` = :year_start WHERE id =:id";
            $params['year_start'] = 2056;

            static::assertSame('bz', $params['title']);
            $params['title'] = 'bze';

            return [$sql, $params];
        });
        
        $implem->addAfterUpdate('a', static function ($params) {
            static::assertSame('bze', $params['title']);
            $params['other'] = 'done';

            return $params;
        });
        
        $implem->addAfterUpdate('b', static function ($params) {
            static::assertSame('bze', $params['title']);
            static::assertSame(2056, $params['year_start']);
            static::assertSame('done', $params['other']);
        });
        
        $implem->addBeforeDelete('a', static function ($sql, $params) {
            static::assertSame("DELETE FROM crud_table WHERE id =:id", $sql);
            $sql = 'toto';
            $params['other'] = 'done';

            return [$sql, $params];
        });
        
        $implem->addBeforeDelete('b', function ($sql, $params) {
            static::assertSame("toto", $sql);
            $sql = "DELETE FROM crud_table WHERE id =:id AND year_start=:year_start";
            $params['year_start'] = 2056;

            static::assertSame($params['other'], 'done');
            unset($params['other']);

            return [$sql, $params];
        });
        
        $implem->addAfterDelete('a', static function ($params) {
            static::assertIsInt($params['id']);
            $params['other_2'] = 'done';

            return $params;
        });
        
        $implem->addAfterDelete('b', static function ($params) {
            static::assertSame('done', $params['other_2']);
        });
        
        try {
            $newId = $implem->create(['title' => 'a', 'date_start' => date('Y-m-d H:i:s')]);
            $row = $implem->one($newId);
            static::assertSame('aze', $row['title']);
            static::assertSame(1956, $row['year_start']);
            
            $implem->update(['title' => 'b'], $newId);
            $row = $implem->one($newId);
            static::assertSame('bze', $row['title']);
            static::assertSame(2056, $row['year_start']);
            
            $implem->delete($newId);
            $row = $implem->one($newId);
            static::assertSame([], $row);

            $implem->removeBeforeCreate('a');
            $implem->removeBeforeUpdate('a');
            $implem->removeBeforeDelete('a');
            
            $implem->removeAfterCreate('a');
            $implem->removeAfterUpdate('a');
            $implem->removeAfterDelete('a');
            $implem->removeAfterDelete('invalid');
        } catch (ModelException $modelException) {
            var_dump($modelException->getMessage());
            var_dump($implem->getErrorFields());
            var_dump($implem->getErrorMessages());
            var_dump($implem->getDatabaseLastError());
        }
    }

    public function testCallbacksWithClass(): void
    {
        $myCallback = new MyCallback();

        $implem = new ImplementModel($this->database);
        $implem->addBeforeCreate('a', [$myCallback, 'before']);
        try {
            $newId = $implem->create(['title' => 'azert', 'date_start' => date('Y-m-d H:i:s')]);
            $row = $implem->one($newId);
            static::assertSame('azert', $row['title']);
            static::assertSame(2000, $row['year_start']);
        } catch (ModelException $modelException) {
            var_dump($modelException->getMessage());
            var_dump($implem->getErrorFields());
            var_dump($implem->getErrorMessages());
            var_dump($implem->getDatabaseLastError());
        }
    }

    // Only work with database accepting invalid values, like travis
    /*public function testBadDataInDatabaseRaiseException()
    {
        $sql = 'INSERT INTO crud_table (`title`,`date_start`) VALUES (:title,:date_start)';
        $params = ['title' => 'aze', 'date_start' => '0000-00-00 00:00:00'];
        $newId = $this->database->insert($sql, $params, true);

        $countAssert = 3;

        $implem = new ImplementModel($this->database);
        try {
            $implem->one($newId);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            $countAssert--;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            $countAssert--;
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            $countAssert--;
        }

        static::assertSame(0, $countAssert);
    }*/
}

/**
 * Class ImplementModel
 */
class ImplementModel extends Model
{
    protected array $parametersToRemove = ["param_to_remove"];
    protected bool $isHackWrong = false;

    public function __construct($database)
    {
        parent::__construct($database);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    protected function setFields() : void
    {
        $this->fields = [
            'id' => new Field('int', ['pk', 'unsigned', 'not_null']),
            'title' => new Field('varchar', ['max:5']),
            'date_start' => new Field('datetime', ['not_null']),
            'year_start' => new Field('year', []),
            'hour_start' => new Field('time', [], '00:00:00'),
            'hour_stop' => new Field('time', [], null),
            'is_visible' => new Field('enum:yes,no', ['not_null'], 'yes'),
            'email' => new Field('varchar', ['email']),
            'nomaxlimit' => new Field('varchar', ['max:']),
            'external_id' => new Field('int', ['fk', 'unsigned'])
        ];
    }

    protected function setTable() : void
    {
        $this->table = 'crud_table';
    }

    public function setHackWrongGetSqlAllWhereAndFillSqlParams(bool $bool): void
    {
        $this->isHackWrong = $bool;
    }
    
    protected function getSqlAllWhereAndFillSqlParams(array $params) : string
    {
        return ($this->isHackWrong) ? 'AND' : parent::getSqlAllWhereAndFillSqlParams($params);
    }

    public function removePkFields(): void
    {
        array_shift($this->fields);
    }

    /**
     * @throws \Rancoud\Model\FieldException
     */
    public function setWrongPk(): void
    {
        $this->fields['wrong_id'] = new Field('int', ['pk', 'unsigned', 'not_null']);
    }

    /**
     * @return string
     * @throws ModelException
     */
    public function hackCreateSqlFieldsFromParams() : string
    {
        $this->sqlParams = ['invalid', 'key' => 'value'];
        return $this->getCreateSqlFieldsFromParams();
    }

    /**
     * @return string
     * @throws ModelException
     */
    public function hackUpdateSqlFieldsFromParams() : string
    {
        $this->sqlParams = ['invalid', 'key' => 'value'];
        return $this->getUpdateSqlFieldsFromParams();
    }
}

/**
 * Class MyCallback
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
