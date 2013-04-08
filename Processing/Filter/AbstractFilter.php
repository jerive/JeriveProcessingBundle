<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Jerive\Bundle\FileProcessingBundle\Processing\Standard;

/**
 * Description of Abstract
 *
 * @author jviveret
 */
abstract class AbstractFilter implements FilterInterface, EventSubscriberInterface
{
    /**
     * The parent, to be able to interact
     * with other filters if needed
     *
     * @var Standard
     */
    protected $processing;

    /**
     * Initialize filter with options,
     * register itself with event dispatcher
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * Register parent
     *
     * @param Standard $processing
     * @return AbstractFilter
     */
    public function setProcessing(Standard $processing)
    {
        $this->processing = $processing;

        return $this;
    }

    /**
     * Register options via set****() methods
     *
     * @param array $options
     */
    public function setOptions($options = array())
    {
        foreach($options as $key => $value) {
            $methodName = 'set' . ucfirst($key);
            $this->$methodName($value);
        }
    }

    /**
     * Get a human readable description
     * of the filter action
     *
     * @return boolean
     */
    public function getDescription()
    {
        return false;
    }

    public static function getSubscribedEvents()
    {
        return array();
    }
}
