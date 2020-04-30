<?php

namespace App\DTO;

interface DataObjectInterface
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array|null
     */
    public function getRow(): ?array;

    /**
     * @return int
     */
    public function getCount(): int;
}