<?php

namespace App\DTO;

class ProductDataObject implements DataObjectInterface
{
    /**
     * @var string
     */
    protected $dataSource;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $pointer = 0;

    /**
     * @var int
     */
    protected $max = 0;

    /**
     * ProductDataObject constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->dataSource = $filename;
        $this->init();
    }

    protected function init(): void
    {
        $json = file_get_contents($this->dataSource);
        $this->data = json_decode($json, true);
        $this->max = count($this->data);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array|null
     */
    public function getRow(): ?array
    {
        if ($this->pointer > $this->max - 1) {
            return null;
        }

        return $this->getData()[$this->pointer++];
    }

    public function reset(): void
    {
        $this->pointer = 0;
    }

    public function getCount(): int
    {
        return $this->max;
    }
}