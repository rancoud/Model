<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;

/**
 * Class JsonOutputTest.
 */
class JsonOutputTest extends TestCase
{
    protected array $data = [
        [
            'id'          => 1,
            'title'       => 'my title',
            'external_id' => null
        ],
        [
            'id'          => 2,
            'title'       => 'other title',
            'external_id' => null
        ],
        [
            'id'          => 3,
            'title'       => 'reminder of my first post',
            'external_id' => 1
        ]
    ];

    /**
     * @throws \Exception
     */
    public function testGetOneJson(): void
    {
        $implem = new ImplementationJsonOutput($this->data);
        $data = $implem->getJson(1);
        $data = \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        static::assertSame($this->data[0], $data);
    }

    /**
     * @throws \Exception
     */
    public function testGetOneJsonEmpty(): void
    {
        $implem = new ImplementationJsonOutput([]);
        $data = $implem->getJson(1);
        $data = \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        static::assertSame([], $data);
    }

    /**
     * @throws \Exception
     */
    public function testGetAllJson(): void
    {
        $implem = new ImplementationJsonOutput($this->data);
        $data = $implem->getJson();
        $data = \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        static::assertSame($this->data, $data);
    }

    /**
     * @throws \Exception
     */
    public function testGetAllJsonEmpty(): void
    {
        $implem = new ImplementationJsonOutput([]);
        $data = $implem->getJson();
        $data = \json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
        static::assertSame([], $data);
    }
}
