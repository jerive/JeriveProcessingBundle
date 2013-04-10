<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Event;

use Symfony\Component\EventDispatcher\Event;
use Jerive\Bundle\FileProcessingBundle\Processing\BaseIterator;

class StartEvent extends Event
{
    /**
     * @var BaseIterator
     */
    public $processing;

    public function __construct(BaseIterator $processing)
    {
        $this->processing = $processing;
    }

    public function getProcessing()
    {
        return $this->processing;
    }
}
