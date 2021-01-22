<?php

declare(strict_types=1);

namespace Rancoud\Model;

/**
 * Trait JsonOutput.
 */
trait JsonOutput
{
    /**
     * @param int|null $id
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getJson(int $id = null): string
    {
        if ($id !== null) {
            return \json_encode($this->getOneJson($id), \JSON_THROW_ON_ERROR);
        }

        return \json_encode($this->getAllJson(), \JSON_THROW_ON_ERROR);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    abstract protected function getOneJson(int $id): array;

    /**
     * @return array
     */
    abstract protected function getAllJson(): array;
}
