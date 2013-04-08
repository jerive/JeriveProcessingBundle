<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Event;

use Symfony\Component\EventDispatcher\Event;
use Jerive\Bundle\FileProcessingBundle\Processing\Standard;

class StartEvent extends Event
{
    /**
     * @var Standard
     */
    public $processing;

    public function __construct(Standard $processing)
    {
        $this->processing = $processing;
    }

    public function getProcessing()
    {
        return $this->processing;
    }
}
