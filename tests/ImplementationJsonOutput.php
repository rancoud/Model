<?php

declare(strict_types=1);

namespace tests;

use Rancoud\Model\JsonOutput;

/**
 * Class ImplementationJsonOutput.
 */
class ImplementationJsonOutput
{
    use JsonOutput;

    protected array $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    protected function getOneJson(int $id): array
    {
        foreach ($this->data as $value) {
            if ($value['id'] === $id) {
                return $value;
            }
        }

        return [];
    }

    protected function getAllJson(): array
    {
        return $this->data;
    }
}
