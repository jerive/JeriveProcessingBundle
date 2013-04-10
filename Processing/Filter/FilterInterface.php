<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

/**
 * @author jviveret
 */
interface FilterInterface
{
    /**
     * Filter function. Called in BaseIterator::process
     *
     * @param mixed $row The item to filter
     * @throws SkipException
     *      to abort the filter chain without error
     * @return mixed The filtered row.
     */
    public function filter($row);

    /**
     * Get a human readable description of what the filter does
     *
     * @return string
     */
    public function getDescription();

    /**
     * Register filter options
     */
    public function setOptions($options = array());
}
