<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Aims to manage batch processing with the following features:
 *      - Mainly, but not limited to, CSV file processing
 *        (any iterator will do)
 *      - File location from any source (local, FTP, SSH...)
 *      - Import progression
 *      - Customizable reports
 *      - Exception management
 *      - Included row filters:
 *          - Empty row skip
 *          - Header skip
 *          - Column mapping
 *          - Encoding conversion
 *          - Callback function
 *
 * @author Jérôme Viveret <jviveret@consoneo.com>
 */
class Standard implements \IteratorAggregate
{
    /**
     * Events triggered during process
     */
    const EVENT_LINE_ERROR        = 'processing.line.error';

    const EVENT_LINE_PROCESSING   = 'processing.line.processing';

    const EVENT_LINE_FILTERED     = 'processing.line.filtered';

    const EVENT_LINE_PROCESSED    = 'processing.line.processed';

    const EVENT_LOG               = 'processing.log';

    const EVENT_PROCESS_STARTED   = 'processing.process.started';

    const EVENT_PROCESS_FINISHED  = 'processing.process.finished';

    /**
     * Row filters
     *
     * @var array<Filter\FilterInterface>
     */
    protected $filters = array();

    /**
     * The iterator
     *
     * @var Iterator
     */
    protected $iterator;

    /**
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Public constructor
     *
     * @param string    $filename
     * @return void
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Adds a row filter
     *
     * @param Filter\FilterInterface | string $filter | array name => filter
     * @return Standard
     */
    public function addFilter($filter, $options = array())
    {
        if (!$filter instanceof Filter\FilterInterface) {
            if (is_array($filter)) {
                foreach($filter as $key => $filter) break;
            }
            $filter = new $class($options);
        }

        if (isset($key)) {
            $this->filters[$key] = $filter;
        } elseif (!isset($this->filters[get_class($filter)])) {
            $this->filters[get_class($filter)] = $filter;
        } else {
            $this->filters[] = $filter;
        }

        $filter->setProcessing($this);
        return $this;
    }

    /**
     * Adds several row filters
     *
     * @param array $filters
     * @return Standard
     */
    public function setFilters($filters = array())
    {
        foreach($filters as $name => $filter) {
            if (is_array($filter)) {
                $this->addFilter($filter[0], isset($filter[1]) ? $filter[1] : array());
            } else {
                $this->addFilter($filter);
            }
        }

        return $this;
    }

    /**
     * Get a filter by name
     *
     * @param string $name
     * @return Filter\FilterInterface
     * @throws RuntimeException
     */
    public function getFilter($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \RuntimeException(sprintf('Filter "%s" is not attached yet', $name));
        }

        return $this->filters[$name];
    }

    /**
     * The description of the processing
     *
     * @return array
     */
    public function getDescription()
    {
        $description = array();

        foreach($this->filters as $key => $filter) {
            $description[$key] = $filter->getDescription();
        }

        return $description;
    }

    /**
     * Set an iterator to batch process
     *
     * @param Iterator $iterator
     */
    public function setIterator($iterator)
    {
        $this->iterator = $iterator;
        return $this;
    }

    /**
     * @see IteratorAggregate::getIterator
     *
     * @return Iterator
     */
    public function getIterator()
    {
        if (!isset($this->iterator)) {
            throw new \RuntimeException('You have to set an iterator');
        }
        return $this->iterator;
    }

    /**
     * Process each element of the iterator
     *
     * @throws RuntimeException
     * @return Standard
     */
    public function process()
    {
        $this->dispatcher->dispatch(
            self::EVENT_PROCESS_STARTED,
            new Event\StartEvent($this)
        );

        foreach ($this as $index => $rowRaw) {
            $this->dispatcher->dispatch(
                self::EVENT_LINE_PROCESSING,
                new Event\LineEvent($index + 1, $rowRaw)
            );
            try {
                $this->dispatcher->dispatch(
                    self::EVENT_LINE_PROCESSED,
                    new Event\LineEvent($index + 1, $rowRaw, $this->filter($rowRaw, $index + 1))
                );
            } catch (Exception $e) { }
        }

        $this->dispatcher->dispatch(self::EVENT_PROCESS_FINISHED);

        return $this;
    }

    /**
     * Executes the filter chain on the raw input
     *
     * @param mixed $row
     * @return mixed
     */
    protected function filter(&$row, $index)
    {
        $rowFiltered = $row;

        foreach($this->filters as $filter) {
            $rowTemp = $rowFiltered;
            try {
                $rowFiltered = $filter->filter($rowFiltered);
                $this->dispatcher->dispatch(
                    self::EVENT_LINE_FILTERED,
                    new Event\LineEvent(null, $index, $rowTemp, $rowFiltered)
                );
            } catch (Exception\SkipException $e) {
                throw $e;
            } catch (\Exception $e) {
                $this->dispatcher->dispatch(
                    self::EVENT_LINE_ERROR,
                    new Event\LineExceptionEvent($e, $index, $row, $rowFiltered)
                );
                throw $e;
            }
        }

        return $rowFiltered;
    }

    public function __sleep()
    {
        return array('filters');
    }
}
