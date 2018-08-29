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
     * @return string
     */
    public function getJson(int $id = null)
    {
        if ($id !== null) {
            return \json_encode($this->getOneJson($id));
        }

        return \json_encode($this->getAllJson());
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
