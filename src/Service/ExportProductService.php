<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ExportProductService
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var GoogleSheetApiInterface
     */
    protected $api;

    /**
     * ExportProductService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, GoogleSheetApiInterface $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    /**
     * @param string $sheetId
     */
    public function export(string $sheetId)
    {
        $this->api->init();

        /* @var Product[] $products */
        $products = $this->em->getRepository(Product::class)->findAll();

        $this->api->insertHeaders($sheetId, Product::HEADERS);

//        $i = 2;
//        foreach ($products as $product) {
//            $this->api->insertRow($sheetId, $product->toArray(), $i++);
//        }

        $data = [];
        foreach ($products as $product) {
            $data[] = $product->toArray();
        }

        $this->api->insertRows($sheetId, $data, 2);
    }
}