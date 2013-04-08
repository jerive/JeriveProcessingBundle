<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

/**
 * Description of EmptyIsNull
 *
 * @author jviveret
 */
class EmptyIsNullFilter extends AbstractFilter
{
    public function filter(&$row)
    {
        foreach ($row as &$value) {
            if ($value === '') {
                $value = null;
            }
        }

        return $row;
    }
}
