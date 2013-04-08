<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Exception\SkipException;

/**
 * Description of EmptyFilter
 *
 * @author jviveret
 */
class EmptyFilter extends AbstractFilter
{
    /**
     * Skip empty lines or report them
     *
     * @var bool
     */
    protected $skip = true;

    /**
     * Report empty lines or skip them
     *
     * @param bool $flag
     */
    public function setSkip($flag = true)
    {
        $this->skip = $flag;
    }

    /**
     * Detects if a row is empty
     *
     * @param type $row
     * @throws RuntimeException if the row is empty
     * @return mixed
     */
    public function filter(&$row)
    {
        // Do not process empty rows
        if (empty($row) || implode('', array_map('trim', $row)) == '') {
            if ($this->skip) {
                throw new SkipException;
            }
            throw new \RuntimeException('The row is empty');
        }

        return $row;
    }

    public function getDescription()
    {
        if ($this->skip) {
            return 'Empty lines are skipped';
        }
    }
}
