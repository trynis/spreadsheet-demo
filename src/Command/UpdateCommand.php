<?php

namespace App\Command;

use App\DTO\ProductDataObject;
use App\Entity\Product;
use App\Service\ExportProductService;
use App\Service\ImportProductsService;
use App\Service\MyGoogleSheetApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

class UpdateCommand extends Command
{
    protected static $defaultName = 'app:update';

    protected static $dataSource = __DIR__ . '/../../data/data.json';
    protected static $apiConfig = __DIR__ . '/../../data/pmweb-5aa3d9098ad8.json';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ParameterBagInterface
     */
    protected $params;

    public function __construct(string $name = null, EntityManagerInterface $em, ParameterBagInterface $params)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->params = $params;
    }

    protected function configure()
    {
        $this
            ->setDescription('Updates given google sheet with prepared data')
            ->addArgument('SheetID', InputArgument::REQUIRED, 'Google Sheet ID')
        ;

    }

    /**
     * Temporary method to clear database
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function truncateProductTable()
    {
        $cmd = $this->em->getClassMetadata(Product::class);
        $connection = $this->em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->truncateProductTable();

        $output->writeln("load data");
        $dataObject = new ProductDataObject(self::$dataSource);
        $output->writeln(sprintf("done. (%d records)", $dataObject->getCount()));

        $output->writeln("import to database");
        $importer = new ImportProductsService($dataObject, $this->em);
        $importer->import();
        $output->writeln("done.");

        $output->writeln("export to data sheet");
        $api = new MyGoogleSheetApi(self::$apiConfig);
        $exporter = new ExportProductService($this->em, $api);
        $exporter->export($input->getArgument('SheetID'));
        $output->writeln("done.");

        return 0;
    }
}