<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\EmptyFilter;

class EmptyFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException
     */
    public function testWillSkipEmpty()
    {
        $filter = new EmptyFilter();
        $filter->setSkip(true);
        $row = array();
        $filter->filter($row);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testWillThrowExceptionEmpty()
    {
        $filter = new EmptyFilter();
        $filter->setSkip(false);
        $row = array();
        $filter->filter($row);
    }

    public function testWillNotSkipNotEmpty()
    {
        $filter = new EmptyFilter();
        $filter->setSkip(false);
        $row = array(1);
        $this->assertEquals($row, $filter->filter($row));
    }
}