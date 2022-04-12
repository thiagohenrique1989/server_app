<?php

namespace App\Tests\Command;

use App\Command\ReadSpreadsheetCommand;
use App\Model\Server;
use PHPUnit\Framework\TestCase;

/**
 * Class ReadSpreadsheetCommandTest
 * @package App\Tests\Command
 */
class ReadSpreadsheetCommandTest extends TestCase
{
    public function testTransformData(): void
    {
        $subject = new ReadSpreadsheetCommand('');
        $this->assertEquals('SAS', $subject->transformData(
            ['HP DL180G62x Intel Xeon E5620', '32GBDDR3', '8x300GBSAS', 'DallasDAL-10', '$170.99']
        )[Server::STORAGE_TYPE]);
        $this->assertEquals('SATA2', $subject->transformData(
            ['Dell R210Intel Xeon X3440', '16GBDDR3', '2x2TBSATA2', 'AmsterdamAMS-01', '€49.99']
        )[Server::STORAGE_TYPE]);
        $this->assertEquals('SSD', $subject->transformData(
            ['RH2288v32x Intel Xeon E5-2650V4', '128GBDDR4', '4x480GBSSD', 'AmsterdamAMS-01', '€227.99']
        )[Server::STORAGE_TYPE]);
    }

    public function testCalcStorage(): void
    {
        $subject = new ReadSpreadsheetCommand('');
        $this->assertEquals('10', $subject->calcStorage(1, 10, 'GB'));
        $this->assertEquals('10', $subject->calcStorage(2, 5, 'GB'));
        $this->assertEquals('1000', $subject->calcStorage(2, 500, 'GB'));
    }
}
