<?php

namespace App\Service;

use App\DTO\DataObjectInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ImportProductsService
{
    /**
     * @var DataObjectInterface
     */
    protected $source;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(DataObjectInterface $dataObject, EntityManagerInterface $em)
    {
        $this->source = $dataObject;
        $this->em = $em;
    }

    public function import()
    {
        while ($row = $this->source->getRow()) {
            $product = new Product();
            $product->fromArray($row);
            $this->em->persist($product);
        }
        $this->em->flush();
    }
}