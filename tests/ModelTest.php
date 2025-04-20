<?php

/**
 * @noinspection SqlDialectInspection
 */

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rancoud\Database\Configurator;
use Rancoud\Database\Database;
use Rancoud\Model\FieldException;
use Rancoud\Model\ModelException;

/** @internal */
class ModelTest extends TestCase
{
    protected array $sgbds = [
        'mysql' => [
            // @var ?Database $db;
            'db'         => null,
            'parameters' => [
                'driver'       => 'mysql',
                'host'         => '127.0.0.1',
                'user'         => 'root',
                'password'     => '',
                'database'     => 'test_database'
            ],
        ],
        'pgsql' => [
            // @var ?Database $db;
            'db'         => null,
            'parameters' => [
                'driver'        => 'pgsql',
                'host'          => '127.0.0.1',
                'user'          => 'postgres',
                'password'      => '',
                'database'      => 'test_database'
            ],
        ],
        'sqlite' => [
            // @var ?Database $db;
            'db'         => null,
            'parameters' => [
                'driver'       => 'sqlite',
                'host'         => '127.0.0.1',
                'user'         => '',
                'password'     => '',
                'database'     => __DIR__ . '/test_database.db'
            ],
        ]
    ];

    protected array $data = [
        'mysql' => [
            [
                'id'          => 1,
                'title'       => 'first',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 2,
                'title'       => 'secon',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 3,
                'title'       => 'third',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ]
        ],
        'pgsql' => [
            [
                'id'          => 1,
                'title'       => 'first',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => null,
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 2,
                'title'       => 'secon',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => null,
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 3,
                'title'       => 'third',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => null,
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ]
        ],
        'sqlite' => [
            [
                'id'          => 1,
                'title'       => 'first',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 2,
                'title'       => 'secon',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ],
            [
                'id'          => 3,
                'title'       => 'third',
                'date_start'  => '2018-08-07 20:06:23',
                'year_start'  => null,
                'hour_start'  => '00:00:00',
                'hour_stop'   => null,
                'is_visible'  => 'yes',
                'email'       => null,
                'nomaxlimit'  => null,
                'external_id' => null
            ]
        ],
    ];

    protected array $sqlQueries = [
        'mysql' => [
            'create' => [
                <<<'EOD'
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
                EOD
            ],
            'all' => [
                <<<'EOD'
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
                EOD,
                "INSERT INTO crud_table VALUES (1, 'first', '{{DATE_START}}', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (2, 'secon', '2018-08-07 20:06:23', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (3, 'third', '2018-08-07 20:06:23', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
            ],
        ],
        'pgsql' => [
            'create' => [
                'CREATE TYPE mood AS ENUM (\'yes\', \'no\');',
                <<<'EOD'
                create table crud_table
                                (
                                    id SERIAL PRIMARY KEY,
                                    title varchar(5),
                                    date_start timestamp not null,
                                    year_start timestamp,
                                    hour_start time,
                                    hour_stop time,
                                    is_visible mood,
                                    email varchar(255),
                                    nomaxlimit varchar(255),
                                    external_id int
                                );
                EOD
            ],
            'all' => [
                'CREATE TYPE mood AS ENUM (\'yes\', \'no\');',
                <<<'EOD'
                create table crud_table
                                (
                                    id SERIAL PRIMARY KEY,
                                    title varchar(5),
                                    date_start timestamp not null,
                                    year_start timestamp,
                                    hour_start time,
                                    hour_stop time,
                                    is_visible mood,
                                    email varchar(255),
                                    nomaxlimit varchar(255),
                                    external_id int
                                );
                EOD,
                "INSERT INTO crud_table VALUES (1, 'first', '{{DATE_START}}', null, null, null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (2, 'secon', '2018-08-07 20:06:23', null, null, null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (3, 'third', '2018-08-07 20:06:23', null, null, null, 'yes', null, null, null)", // phpcs:ignore
            ],
        ],
        'sqlite' => [
            'create' => [
                <<<'EOD'
                create table crud_table
                                (
                                    id   INTEGER PRIMARY KEY AUTOINCREMENT,
                                    title varchar(5),
                                    date_start datetime not null,
                                    year_start timestamp,
                                    hour_start time default '00:00:00' not null,
                                    hour_stop time default NULL,
                                    is_visible text default 'yes',
                                    email varchar(255),
                                    nomaxlimit varchar(255),
                                    external_id int
                                );
                EOD,
                'create unique index crud_table_external_id_uindex on crud_table (external_id);'
            ],
            'all' => [
                <<<'EOD'
                create table crud_table
                                (
                                    id   INTEGER PRIMARY KEY AUTOINCREMENT,
                                    title varchar(5),
                                    date_start datetime not null,
                                    year_start timestamp,
                                    hour_start time default '00:00:00' not null,
                                    hour_stop time default NULL,
                                    is_visible text default 'yes',
                                    email varchar(255),
                                    nomaxlimit varchar(255),
                                    external_id int
                                );
                EOD,
                'create unique index crud_table_external_id_uindex on crud_table (external_id);',
                "INSERT INTO crud_table VALUES (1, 'first', '{{DATE_START}}', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (2, 'secon', '2018-08-07 20:06:23', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
                "INSERT INTO crud_table VALUES (3, 'third', '2018-08-07 20:06:23', null, '00:00:00', null, 'yes', null, null, null)", // phpcs:ignore
            ],
        ],
    ];

    /** @throws \Rancoud\Database\DatabaseException */
    protected function setUp(): void
    {
        $this->data['mysql'][0]['date_start'] = \date('Y-m-d H:i:s');
        $this->data['pgsql'][0]['date_start'] = \date('Y-m-d H:i:s');
        $this->data['sqlite'][0]['date_start'] = \date('Y-m-d H:i:s');

        foreach ($this->sgbds as $k => $sgbd) {
            $configurator = new Configurator($this->sgbds[$k]['parameters']);

            if ($configurator->getDriver() === 'mysql') {
                $mysqlHost = \getenv('MYSQL_HOST', true);
                $configurator->setHost(($mysqlHost !== false) ? $mysqlHost : $this->sgbds[$k]['parameters']['host']);
            }

            if ($configurator->getDriver() === 'pgsql') {
                $postgresHost = \getenv('POSTGRES_HOST', true);
                $configurator->setHost(($postgresHost !== false) ? $postgresHost : $this->sgbds[$k]['parameters']['host']); // phpcs:ignore
            }

            $this->sgbds[$k]['db'] = new Database($configurator);
            $pdo = $configurator->createPDOConnection();

            $pdo->exec('DROP TABLE IF EXISTS crud_table');
            if ($this->sgbds[$k]['parameters']['driver'] === 'pgsql') {
                $pdo->exec('DROP TYPE IF EXISTS mood');
            }
        }

        parent::setUp();
    }

    protected function tearDown(): void
    {
        foreach ($this->sgbds as $k => $sgbd) {
            $this->sgbds[$k]['db']->disconnect();
            $this->sgbds[$k]['db'] = null;
        }
    }

    public static function sgbds(): iterable
    {
        yield 'mysql'      => ['mysql'];

        yield 'postgresql' => ['pgsql'];

        yield 'sqlite'     => ['sqlite'];
    }

    /** @throws FieldException */
    #[DataProvider('sgbds')]
    public function testCreateUpdateDeleteCleanErrorFields(string $sgbd): void
    {
        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        $countAssert = 1;

        try {
            $implem->create(['date_start' => '']);
        } catch (\Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;

        try {
            $implem->create(['date_start' => '']);
        } catch (\Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;

        try {
            $implem->update(['date_start' => ''], 1);
        } catch (\Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;

        try {
            $implem->update(['date_start' => ''], 1);
        } catch (\Exception $e) {
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;
        $implem->setWrongPk();

        try {
            $implem->delete(1);
        } catch (\Exception $e) {
            static::assertSame(['wrong_id' => ['Null not authorized']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);

        $countAssert = 1;

        try {
            $implem->delete(1);
        } catch (\Exception $e) {
            static::assertSame(['wrong_id' => ['Null not authorized']], $implem->getErrorFields());
            --$countAssert;
        }
        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testCreateModelExceptionEmptySql(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->create([]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Insert sql query is empty'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testCreateModelExceptionMissingRequiredFields(string $sgbd): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->create(['title' => 'a']);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Missing required fields'], $implem->getErrorMessages());
            --$countAssert;
            static::assertSame(['date_start' => ['field is required']], $implem->getErrorFields());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testCreateModelExceptionInvalidValues(string $sgbd): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->create(['title' => 'a', 'date_start' => 'ggggg']);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            --$countAssert;
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testCreateModelExceptionErrorSql(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->addBeforeCreate('a', static function ($sql, $params) {
                $sql = 'sql query invalid';

                return [$sql, $params];
            });
            $implem->create(['title' => 'a', 'date_start' => '2000-01-01 01:01:01']);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error creating'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testCreateModelExceptionHackInvalidField(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->hackCreateSqlFieldsFromParams();
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Insert sql query is empty'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws ModelException */
    #[DataProvider('sgbds')]
    public function testCreate(string $sgbd): void
    {
        $sqls = $this->sqlQueries[$sgbd]['create'];
        foreach ($sqls as $sql) {
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        $id = $implem->create([
            'title'      => $this->data[$sgbd][0]['title'],
            'date_start' => $this->data[$sgbd][0]['date_start']
        ]);
        static::assertSame(1, $id);
        $id = $implem->create([
            'title'           => $this->data[$sgbd][1]['title'],
            'date_start'      => $this->data[$sgbd][1]['date_start'],
            'param_to_remove' => 'this will be remove in parametersToRemove function'
        ]);
        static::assertSame(2, $id);
        $id = $implem->create([
            'title'       => $this->data[$sgbd][2]['title'],
            'date_start'  => $this->data[$sgbd][2]['date_start'],
            'extra_field' => 'this will be remove in create sql'
        ]);
        static::assertSame(3, $id);
        static::assertSame(3, $implem->getLastInsertId());

        static::assertSame($this->data[$sgbd][0], $implem->one(1));
        static::assertSame($this->data[$sgbd][1], $implem->one(2));
        static::assertSame($this->data[$sgbd][2], $implem->one(3));
    }

    #[DataProvider('sgbds')]
    public function testAllModelExceptionErrorSql(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->setHackWrongGetSqlAllWhereAndFillSqlParams(true);

        try {
            $implem->all([]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error select all'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);

        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->setHackWrongGetSqlAllWhereAndFillSqlParams(true);

        try {
            $implem->all(['rows_count' => 1]);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error select count all'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws ModelException */
    #[DataProvider('sgbds')]
    public function testAll(string $sgbd): void
    {
        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        $rows = $implem->all([]);
        static::assertSame($this->data[$sgbd], $rows);

        $rows = $implem->all(['count' => 1]);
        static::assertSame([$this->data[$sgbd][0]], $rows);

        $count = $implem->all(['rows_count' => 1]);
        static::assertSame(3, $count);

        $implem->all(['no_limit' => 1, 'count' => 1]);
        static::assertSame([$this->data[$sgbd][0]], $rows);

        $rows = $implem->all(['page' => 1, 'count' => 2]);
        static::assertSame([$this->data[$sgbd][0], $this->data[$sgbd][1]], $rows);

        $rows = $implem->all(['page' => 2, 'count' => 2]);
        static::assertSame([$this->data[$sgbd][2]], $rows);
    }

    #[DataProvider('sgbds')]
    public function testOneModelExceptionNoPrimaryKey(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->removePkFields();
            $implem->one(1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testOneModelExceptionInvalidPrimaryKey(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->one('invalid');
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws FieldException */
    #[DataProvider('sgbds')]
    public function testOneModelExceptionErrorSelect(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->setWrongPk();
            $implem->one(1, 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error select one'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws ModelException */
    #[DataProvider('sgbds')]
    public function testOne(string $sgbd): void
    {
        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $row = $implem->one(1);
        static::assertSame($this->data[$sgbd][0], $row);

        $row = $implem->one(1, 999);
        static::assertSame($this->data[$sgbd][0], $row);

        $row = $implem->one(999);
        static::assertSame([], $row);

        $row = $implem->one(999, 1);
        static::assertSame([], $row);
    }

    #[DataProvider('sgbds')]
    public function testUpdateModelExceptionNoPrimaryKey(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->removePkFields();
            $implem->update([], 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testUpdateModelExceptionEmptySql(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->update([], 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Update sql query is empty'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testUpdateModelExceptionInvalidValues(string $sgbd): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->update(['title' => 'a', 'date_start' => 'ggggg'], 1);
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            --$countAssert;
            static::assertSame(['date_start' => ['Invalid datetime value']], $implem->getErrorFields());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws FieldException */
    #[DataProvider('sgbds')]
    public function testUpdateModelExceptionErrorSql(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->addBeforeUpdate('a', static function ($sql, $params) {
                $sql = 'sql query invalid';

                return [$sql, $params];
            });
            $implem->setWrongPk();
            $implem->update(['title' => 'a'], 1, 99);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error updating'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testUpdateModelExceptionHackInvalidField(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->hackUpdateSqlFieldsFromParams();
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Update sql query is empty'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws ModelException */
    #[DataProvider('sgbds')]
    public function testUpdate(string $sgbd): void
    {
        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->update(['title' => 'youpie', 'param_to_remove' => 'this will be remove in parametersToRemove function'], 1); // phpcs:ignore

        $row = $implem->one(1);
        static::assertSame('youpi', $row['title']);

        $implem->update(['title' => 'first'], 1);

        $row = $implem->one(1);
        static::assertSame('first', $row['title']);
    }

    #[DataProvider('sgbds')]
    public function testDeleteModelExceptionNoPrimaryKey(string $sgbd): void
    {
        $countAssert = 2;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->removePkFields();
            $implem->delete(1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error no primary key'], $implem->getErrorMessages());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws FieldException */
    #[DataProvider('sgbds')]
    public function testDeleteModelExceptionErrorSql(string $sgbd): void
    {
        $countAssert = 4;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->addBeforeDelete('a', static function ($sql, $params) {
                $sql = 'sql query invalid';

                return [$sql, $params];
            });
            $implem->setWrongPk();
            $implem->delete(1, 1);
        } catch (ModelException $modelException) {
            static::assertSame('ERROR', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Error deleting'], $implem->getErrorMessages());
            --$countAssert;
            static::assertNotEmpty($implem->getDatabaseErrors());
            static::assertSame('sql query invalid', $implem->getDatabaseErrors()[0]['query']);
            --$countAssert;
            static::assertNotEmpty($implem->getDatabaseLastError());
            static::assertSame('sql query invalid', $implem->getDatabaseLastError()['query']);
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    #[DataProvider('sgbds')]
    public function testDeleteModelExceptionInvalidValues(string $sgbd): void
    {
        $countAssert = 3;

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        try {
            $implem->delete('invalid');
        } catch (ModelException $modelException) {
            static::assertSame('FIELDS', $modelException->getMessage());
            --$countAssert;
            static::assertSame(['Formating values invalid'], $implem->getErrorMessages());
            --$countAssert;
            static::assertSame(['id' => ['Invalid int value']], $implem->getErrorFields());
            --$countAssert;
        }

        static::assertSame(0, $countAssert);
    }

    /** @throws ModelException */
    #[DataProvider('sgbds')]
    public function testDelete(string $sgbd): void
    {
        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);

        $row = $implem->one(1);
        static::assertSame($this->data[$sgbd][0], $row);

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->delete(1);

        $row = $implem->one(1);
        static::assertSame([], $row);
    }

    /**
     * @throws FieldException
     * @throws ModelException
     */
    #[DataProvider('sgbds')]
    public function testCallbacks(string $sgbd): void
    {
        if ($sgbd === 'pgsql') {
            $this->expectNotToPerformAssertions();

            return;
        }

        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->addBeforeCreate('a', static function ($sql, $params) {
            static::assertSame('INSERT INTO crud_table (title,date_start) VALUES (:title,:date_start)', $sql);
            $sql = 'toto';

            static::assertSame('a', $params['title']);
            $params['title'] = 'az';

            return [$sql, $params];
        });

        $implem->addBeforeCreate('b', static function ($sql, $params) {
            static::assertSame('toto', $sql);
            $sql = 'INSERT INTO crud_table (`title`,`date_start`, `year_start`) VALUES (:title,:date_start, :year_start)'; // phpcs:ignore
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

        $implem->addAfterCreate('b', static function ($newId, $params): void {
            static::assertSame('done', $params['other']);
        });

        $implem->addBeforeUpdate('a', static function ($sql, $params) {
            static::assertSame('UPDATE crud_table SET title = :title WHERE id =:id', $sql);
            $sql = 'toto';

            static::assertSame('b', $params['title']);
            $params['title'] = 'bz';

            return [$sql, $params];
        });

        $implem->addBeforeUpdate('b', static function ($sql, $params) {
            static::assertSame('toto', $sql);
            $sql = 'UPDATE crud_table SET `title` = :title, `year_start` = :year_start WHERE id =:id';
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

        $implem->addAfterUpdate('b', static function ($params): void {
            static::assertSame('bze', $params['title']);
            static::assertSame(2056, $params['year_start']);
            static::assertSame('done', $params['other']);
        });

        $implem->addBeforeDelete('a', static function ($sql, $params) {
            static::assertSame('DELETE FROM crud_table WHERE id =:id', $sql);
            $sql = 'toto';
            $params['other'] = 'done';

            return [$sql, $params];
        });

        $implem->addBeforeDelete('b', static function ($sql, $params) {
            static::assertSame('toto', $sql);
            $sql = 'DELETE FROM crud_table WHERE id =:id AND year_start=:year_start';
            $params['year_start'] = 2056;

            static::assertSame('done', $params['other']);
            unset($params['other']);

            return [$sql, $params];
        });

        $implem->addAfterDelete('a', static function ($params) {
            static::assertIsInt($params['id']);
            $params['other_2'] = 'done';

            return $params;
        });

        $implem->addAfterDelete('b', static function ($params): void {
            static::assertSame('done', $params['other_2']);
        });

        $params = ['title' => 'a', 'date_start' => \date('Y-m-d H:i:s')];

        $newId = $implem->create($params);
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
    }

    /**
     * @throws FieldException
     * @throws ModelException
     */
    #[DataProvider('sgbds')]
    public function testCallbacksWithClass(string $sgbd): void
    {
        if ($sgbd === 'pgsql') {
            $this->expectNotToPerformAssertions();

            return;
        }

        $sqls = $this->sqlQueries[$sgbd]['all'];
        foreach ($sqls as $sql) {
            $sql = \str_replace('{{DATE_START}}', $this->data[$sgbd][0]['date_start'], $sql);
            $this->sgbds[$sgbd]['db']->exec($sql);
        }

        $myCallback = new MyCallback();

        $implem = new ImplementModel($this->sgbds[$sgbd]['db']);
        $implem->addBeforeCreate('a', [$myCallback, 'before']);

        $params = ['title' => 'azert', 'date_start' => \date('Y-m-d H:i:s')];

        $newId = $implem->create($params);

        $row = $implem->one($newId);
        static::assertSame('azert', $row['title']);
        static::assertSame(2000, $row['year_start']);
    }
}
