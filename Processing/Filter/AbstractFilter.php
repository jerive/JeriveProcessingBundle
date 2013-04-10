<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Description of Abstract
 *
 * @author jviveret
 */
abstract class AbstractFilter implements FilterInterface, EventSubscriberInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

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
     * @param BaseIterator $processing
     * @return AbstractFilter
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

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
