<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Iterator;

use Jerive\Bundle\FileProcessingBundle\Processing\Iterator\CsvReader;

class CsvReaderTest extends PHPUnit_Framework_Testcase
{
    public function testWillLoadFile()
    {
        $csv = <<<csv
toto|toto
lolo|lolo
csv;

        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('imports'));
        file_put_contents($file = vfsStream::url('imports/test.csv'), $csv);

        $iterator = CsvReader::factory($file, '|');
        $return = array();
        foreach($iterator as $row) {
            $return[] = $row;
        }

        $this->assertEquals(array(array('toto', 'toto'), array('lolo', 'lolo')), $return);
    }
}
