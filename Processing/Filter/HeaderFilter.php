<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException;
use Jerive\Bundle\FileProcessingBundle\Processing\Event\LineEvent;

/**
 * Description of HeaderFilter
 *
 * @author jviveret
 */
class HeaderFilter extends AbstractFilter
{
    const EVENT_HEADER = 'event.header';

    /**
     * Has the CSV a header
     *
     * @var bool
     */
    protected $hasHeader = true;

    /**
     * Header has already been skipped ?
     *
     * @var type
     */
    protected $headerSkipped = false;

    /**
     * Whether or not to skip the first line
     *
     * @param bool $hasHeader
     */
    public function setHasHeader($hasHeader)
    {
        $this->hasHeader = (bool) $hasHeader;
    }

    /**
     * Will skip the header if told to
     *
     * @see FilterInterface::filter
     * @param mixed $row
     * @return mixed
     */
    public function filter(&$row)
    {
        // Do not process header if there is a header
        if ($this->_hasHeader && ! $this->_headerSkipped) {
            $this->_headerSkipped = true;
            $this->processing->getDispatcher()->dispatch(
                self::EVENT_HEADER,
                new LineEvent(1, $row)
            );
            throw new SkipException('This is the header');
        }

        return $row;
    }

    /**
     * @see FilterInterface::getDescription
     */
    public function getDescription()
    {
        if ($this->_hasHeader) {
            return 'The first line will be skipped as it is the header';
        }
    }
}
