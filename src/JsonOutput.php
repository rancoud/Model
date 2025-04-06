<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Trait JsonOutput.
 */
trait JsonOutput
{
    /**
     * @throws \Exception
     */
    public function getJson(?int $id = null): string
    {
        if ($id !== null) {
            return \json_encode($this->getOneJson($id), \JSON_THROW_ON_ERROR);
        }

        return \json_encode($this->getAllJson(), \JSON_THROW_ON_ERROR);
    }

    abstract protected function getOneJson(int $id): array;

    abstract protected function getAllJson(): array;
}
