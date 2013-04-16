<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\CallbackFilter;

class CallbackFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException RuntimeException
     */
    public function testWillFailForUndefinedCallback()
    {
        $filter = new CallbackFilter;
        $row = array();
        $filter->filter($row);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWillFailForInvalidCallback()
    {
        $filter = new CallbackFilter;
        $filter->setCallback(1);
        $row = array();
        $filter->filter($row);
    }

    public function testWillDo()
    {
        $filter = new CallbackFilter;
        $filter->setCallback('array_merge');
        $row = array();
        $this->assertEquals($row, $filter->filter($row));
    }

    public function testCallArray()
    {
        $filter = new CallbackFilter;
        $filter->setCallback('array_merge');
        $filter->setCallArray(true);
        $row = array(array(), array());
        $this->assertEquals(array(), $filter->filter($row));
    }
}