<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Event;

use Symfony\Component\EventDispatcher\Event;

class LineEvent extends Event
{
    protected $line;

    protected $row;

    protected $rowImported;

    public function __construct($line, &$row, $rowImported = null)
    {
        $this->line = $line;
        $this->row = $row;
        $this->rowImported = $rowImported;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function getRowImported()
    {
        return $this->rowImported;
    }
}
