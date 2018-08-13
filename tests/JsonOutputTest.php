<?php

declare(strict_types=1);

use Rancoud\Model\JsonOutput;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonOutputTest
 */
class JsonOutputTest extends TestCase
{
    protected $data = [
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
    
    public function testGetOneJson()
    {
        $implem = new ImplementationJsonOutput($this->data);
        $data = $implem->getJson(1);
        $data = json_decode($data, true);
        static::assertSame($this->data[0], $data);
    }

    public function testGetOneJsonEmpty()
    {
        $implem = new ImplementationJsonOutput([]);
        $data = $implem->getJson(1);
        $data = json_decode($data, true);
        static::assertSame([], $data);
    }

    public function testGetAllJson()
    {
        $implem = new ImplementationJsonOutput($this->data);
        $data = $implem->getJson();
        $data = json_decode($data, true);
        static::assertSame($this->data, $data);
    }

    public function testGetAllJsonEmpty()
    {
        $implem = new ImplementationJsonOutput([]);
        $data = $implem->getJson();
        $data = json_decode($data, true);
        static::assertSame([], $data);
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
        foreach ($this->data as $value){
            if($value['id'] === $id){
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