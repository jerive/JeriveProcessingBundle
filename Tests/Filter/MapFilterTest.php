<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\MapFilter;

class MapFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testFilterWillFailOnTooSmall()
    {
        $filter = new MapFilter;
        $filter->setMapping(array('a' => 'Column a', 'b' => 'Column b'));
        $filter->setInitializeMissingColumns(false);
        $row = array();
        $filter->filter($row);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testFilterWillFailOnTooLarge()
    {
        $filter = new MapFilter;
        $filter->setMapping(array('a' => 'Column a', 'b' => 'Column b'));
        $filter->setDiscardAdditionalColumns(false);
        $row = array(1, 2, 3, 4);
        $filter->filter($row);
    }

    public function testFilterWillNotFailOnTooSmall()
    {
        $filter = new MapFilter;
        $filter->setMapping(array('a' => 'Column a', 'b' => 'Column b'));
        $filter->setInitializeMissingColumns(true);
        $row = array();
        $this->assertEquals(array('a' => null, 'b' => null), $filter->filter($row));
    }

    public function testFilterWillNotFailOnTooLarge()
    {
        $filter = new MapFilter;
        $filter->setMapping(array('a' => 'Column a', 'b' => 'Column b'));
        $filter->setDiscardAdditionalColumns(true);
        $row = array(1, 2, 3, 4);
        $this->assertEquals(array('a' => 1, 'b' => 2), $filter->filter($row));
    }
}
