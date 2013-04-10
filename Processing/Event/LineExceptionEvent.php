<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Event;

class LineExceptionEvent extends LineEvent
{
    /**
     * @var Exception
     */
    protected $exception;

    public function __construct(\Exception $e, $line, $row, $rowImported = null)
    {
        parent::__construct($line, $row, $rowImported);
        $this->setException($e);
    }

    /**
     * The exception
     *
     * @param Exception $e
     */
    public function setException(\Exception $e)
    {
        $this->exception = $e;
        return $this;
    }

    /**
     * Get the exception bound to this event
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
