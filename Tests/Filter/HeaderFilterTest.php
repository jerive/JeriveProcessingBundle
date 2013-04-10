<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\HeaderFilter;

class HeaderFilterTest extends PHPUnit_Framework_Testcase
{
    protected static $instance;

    /**
     * @expectedException \Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException
     */
    public function testThrowExceptionFilter()
    {
        self::$instance = new HeaderFilter();
        self::$instance->setHasHeader(true);
        $row = array();
        self::$instance->filter($row);
    }

    /**
     * @depends testThrowExceptionFilter
     */
    public function testFilterDoesNotThrowExceptionAnymore()
    {
        $row = array();
        $this->assertEquals(array(), self::$instance->filter($row));
    }

    public function testFilterNeverThrowsException()
    {
        $row = array();
        $filter = new HeaderFilter;
        $filter->setHasHeader(false);
        $this->assertEquals($row, $filter->filter($row));
        $this->assertEquals($row, $filter->filter($row));
    }
}