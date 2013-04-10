<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Tests\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Filter\EncodingFilter;

class EncodingFilterTest extends PHPUnit_Framework_Testcase
{
    /**
     * @dataProvider providerEncoding
     */
    public function testWillNotFail($expected, $value)
    {
        $filter = new EncodingFilter;
        $this->assertEquals($expected, $filter->filter($value));
    }

    public function providerEncoding()
    {
        return array(
            array(array(1, 'é', '@'), array(1, 'é', '@')),
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWillFailOnError()
    {
        $filter = new EncodingFilter;
        $filter->setExceptionOnEncodingError(true);
        $filter->setInputEncoding('utf-7');
        $filter->setOutputEncoding('iso-8859-7');

        $row = array('°$ə');
        $filter->filter($row);
    }

    public function testWillNotFailOnError()
    {
        $filter = new EncodingFilter;
        $filter->setExceptionOnEncodingError(false);
        $filter->setInputEncoding('utf-7');
        $filter->setOutputEncoding('iso-8859-7');

        $row = array('°$ə');
        $filter->filter($row);
    }

    public function testWillNotFailOnError2()
    {
        $filter = new EncodingFilter;
        $filter->setExceptionOnEncodingError(false);
        $filter->setInputEncoding('utf-7');
        $filter->setOutputEncoding('iso-8859-7');
        $filter->setAutodetectInputEncoding(true);

        $row = array('°$ə');
        $filter->filter($row);
    }
}