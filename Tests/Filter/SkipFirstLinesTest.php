<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\SkipFirstLinesFilter;

/**
 * @author jviveret
 */
class SkipFirstLinesFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException
     */
    public function testWillHaveLineException()
    {
        $filter = new SkipFirstLinesFilter(array('skipLines' => 1));
        $row = array();
        $filter->filter($row);
    }

    public function testWillHaveOneLineExceptionAndWillGoOn()
    {
        $filter = new SkipFirstLinesFilter(array('skipLines' => 1));
        $row = array();
        $n = 0;
        try {
            $filter->filter($row);
        } catch (SkipException $e) {
            $n++;
        }

        $this->assertEquals($row, $filter->filter($row));
        $this->assertEquals(1, $n);
    }
}