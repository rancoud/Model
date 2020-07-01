<?php

declare(strict_types=1);

use Rancoud\Model\JsonOutput;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonOutputTest
 */
class JsonOutputTest extends TestCase
{
    protected array $data = [
        [
            'id' => 1,
            'title' => 'my title',
            'external_id' => null
        ],
        [
            'id' => 2,
            'title' => 'other title',
            'external_id' => null
        ],
        [
            'id' => 3,
            'title' => 'reminder of my first post',
            'external_id' => 1
        ]
    ];

    /**
     * @throws JsonException
     */
    public function testGetOneJson(): void
    {
        try {
            $implem = new ImplementationJsonOutput($this->data);
            $data = $implem->getJson(1);
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            static::assertSame($this->data[0], $data);
        } catch(JsonException $e) {
            throw $e;
        }
    }

    /**
     * @throws JsonException
     */
    public function testGetOneJsonEmpty(): void
    {
        try {
            $implem = new ImplementationJsonOutput([]);
            $data = $implem->getJson(1);
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            static::assertSame([], $data);
        } catch(JsonException $e) {
            throw $e;
        }
    }

    /**
     * @throws JsonException
     */
    public function testGetAllJson(): void
    {
        try {
            $implem = new ImplementationJsonOutput($this->data);
            $data = $implem->getJson();
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            static::assertSame($this->data, $data);
        } catch(JsonException $e) {
            throw $e;
        }
    }

    /**
     * @throws JsonException
     */
    public function testGetAllJsonEmpty(): void
    {
        try {
            $implem = new ImplementationJsonOutput([]);
            $data = $implem->getJson();
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            static::assertSame([], $data);
        } catch(JsonException $e) {
            throw $e;
        }
    }
}

/**
 * Class ImplementationJsonOutput
 */
class ImplementationJsonOutput
{
    use JsonOutput;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    protected function getOneJson(int $id) : array
    {
        foreach ($this->data as $value) {
            if ($value['id'] === $id) {
                return $value;
            }
        }
        
        return [];
    }

    protected function getAllJson() : array
    {
        return $this->data;
    }
}
