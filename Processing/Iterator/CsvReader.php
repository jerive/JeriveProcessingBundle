<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Iterator;

/**
 * Simple wrapper around SplFileObject
 *
 * @author Jérôme Viveret <jviveret@consoneo.com>
 */
class CsvReader implements \Iterator
{
    /**
     * @var handle
     */
    protected $handle;

    protected $count = 0;

    protected $csvDelimiter = ',';

    protected $csvEnclosure = '"';

    protected $csvEscape = '\\';

    /**
     * @var string
     */
    protected $path;

    /**
     * @var bool
     */
    protected $currentFetched = false;

    /**
     * @var array
     */
    protected $current;

    /**
     *
     * @param string $fileName
     */
    protected function __construct($filename)
    {
        if (file_exists($filename) && is_file($filename)) {
            $this->handle = fopen($filename, 'r');
            $this->path = $filename;
        } else {
            throw new \RuntimeException(sprintf('Could not open "%s"', $filename));
        }
    }

    /**
     * Easily build a CSV iterator
     *
     * @param string $fileName
     * @param string $csvDelimiter
     * @param string $csvEnclosure
     * @param string $csvEscape
     * @return CsvReader
     */
    public static function factory($filename, $csvDelimiter = ';', $csvEnclosure = '"', $csvEscape = '\\')
    {
        $file = new self($filename);
        $file->setCsvControl($csvDelimiter, $csvEnclosure, $csvEscape);

        return $file;
    }

    /**
     * @param string $csvDelimiter
     * @param string $csvEnclosure
     * @param string $csvEscape
     * @return CsvReader
     */
    public function setCsvControl($csvDelimiter = ';', $csvEnclosure = '"', $csvEscape = '\\')
    {
        $this->csvDelimiter = $csvDelimiter;
        $this->csvEnclosure = $csvEnclosure;
        $this->csvEscape    = $csvEscape;

        return $this;
    }

    /**
     * @return bool
     */
    public function rewind()
    {
        $this->count = 0;

        if (strpos($this->path, 'ftp://') !== false) {
            // Don't rewind on streams not supporting it
            fclose($this->handle);
            $this->__construct($this->path);
            return true;
        } else {
            return rewind($this->handle);
        }
    }

    public function current()
    {
        if (!$this->currentFetched) {
            $this->current = fgetcsv($this->handle, null, $this->csvDelimiter, $this->csvEnclosure);
            $this->currentFetched = true;
        }

        return $this->current;
    }

    public function key()
    {
        return $this->count;
    }

    public function next()
    {
        $this->count++;
        $this->currentFetched = false;
    }

    public function valid()
    {
        return !feof($this->handle);
    }
}
