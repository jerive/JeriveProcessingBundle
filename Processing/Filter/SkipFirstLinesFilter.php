<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException;

/**
 * SkipFirstLinesFilter
 *
 * @author jviveret
 */
class SkipFirstLinesFilter extends AbstractFilter
{
    /**
     * How many lines will be skipped
     *
     * @var int
     */
    protected $linesToSkip = 0;

    /**
     * Skip that number of lines
     *
     * @param int $n
     */
    public function setSkipLines($n)
    {
        $this->linesToSkip = $n;
    }

    public function filter($row)
    {
        if ($this->linesToSkip != 0) {
            --$this->linesToSkip;
            throw new SkipException('Skip line');
        }

        return $row;
    }

    public function getDescription()
    {
        return sprintf(_('Will skip the first %s lines'), $this->linesToSkip);
    }
}
