<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\FormFilter;

/**
 * @author jviveret
 */
class FormFilterTest extends PHPUnit_Framework_Testcase
{
    /**
     * @expectedException \Jerive\Bundle\FileProcessingBundle\Processing\Exception\FailedValidationException
     * @covers \Jerive\Bundle\FileProcessingBundle\Processing\Filter\AbstractFilter
     * @covers FormFilter
     */
    public function testWillFail()
    {
//        $filter = new FormFilter(array('form' => $form));
//        $row = array('element' => '10');
//        $filter->filter($row);
    }

    /**
     * @covers \Jerive\Bundle\FileProcessingBundle\Processing\Filter\AbstractFilter
     * @covers FormFilter
     */
    public function testWillNotFail()
    {
//        $filter = new FormFilter(array('form' => $form));
//        $row = array('element' => 'abbaa');
//
//        $this->assertEquals($row, $filter->filter($row));
//        $filter->getForm();
    }
}