<?php

namespace App\Command;

use App\Model\Server;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ReadSpreadsheetCommand
 * @package App\Command
 */
class ReadSpreadsheetCommand extends Command
{
    protected static $defaultName = 'app:read-spreadsheet';

    protected $filesystem;
    protected $filepath;
    protected $data = [];

    /**
     * ReadSpreadsheetCommand constructor.
     * @param string $path
     * @param null $name
     */
    public function __construct(string $path, $name = null)
    {
        parent::__construct($name);
        $this->filesystem = new Filesystem();
        $this->filepath = $path . '/src/LeaseWeb_servers_filters_assignment.xlsx';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->exists($this->filepath)) {
            $output->writeln(sprintf("File %s not found on server.", $this->filepath));
            return Command::FAILURE;
        }

        $reader = new Xlsx();
        $spreadsheet = $reader->load($this->filepath);

        foreach ($spreadsheet->getSheet(0)->getRowIterator(2) as $row) {
            $this->data[] = $this->extractRow($row);
        }

        $cachePool = new FilesystemAdapter('app');
        $cacheItem = $cachePool->getItem(Server::CACHE_KEY)->set($this->data);
        $cachePool->save($cacheItem);

        $output->writeln(sprintf("%d registers saved on cache", count($this->data)));
        return Command::SUCCESS;
    }

    /**
     * @param Row $row
     * @return array
     */
    private function extractRow(Row $row): array
    {
        $data = [];
        foreach ($row->getCellIterator() as $idx => $cell) {
            if (in_array($idx, ['A', 'B', 'C', 'D', 'E'])) {
                $data[] = $cell->getValue();
            }
        }

        return $this->transformData($data);
    }

    /**
     * @param array $data
     * @return array
     */
    private function transformData(array $data): array
    {
        $matches = [];
        preg_match("/(\d+)x(\d+)(GB|TB)(.+)/", $data[Server::STORAGE_RAW], $matches);

        $data[Server::RAM] = preg_replace("/DDR\d/", '', $data[Server::RAM_RAW]);
        $data[Server::STORAGE] = $this->calcStorage($matches[1], $matches[2], $matches[3]);
        $data[Server::STORAGE_TYPE] = $matches[4];

        return $data;
    }

    /**
     * @param $number
     * @param $capacity
     * @param $measure
     * @return string
     */
    private function calcStorage($number, $capacity, $measure): string
    {
        if ($measure === 'TB') {
            $capacity *= 1000;
        }

        return $number * $capacity;
    }
}
