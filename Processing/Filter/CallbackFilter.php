<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

/**
 * Description of CallbackFilter
 *
 * @author jviveret
 */
class CallbackFilter extends AbstractFilter
{
    /**
     * The callable used to process a row
     *
     * @var callable
     */
    protected $importMethod = array(
        'Jerive\\Bundle\\FileProcessingBundle\\Processing\Filter\\Callback',
        'notDefined',
    );

    /**
     * Use call_user_func_array or call_user func ?
     *
     * @var bool
     */
    protected $callArray = false;

    /**
     * The description
     *
     * @var string | false
     */
    protected $description = false;

    /**
     * Sets the import method
     *
     * @param callable $callable
     */
    public function setCallback($callable)
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException('This method is not callable');
        }
        $this->importMethod = $callable;
        return $this;
    }

    /**
     * Use call_user_func_array at callback time
     *
     * @param bool $flag
     */
    public function setCallArray($flag = true)
    {
        $this->callArray = $flag;
        return $this;
    }

    /**
     * @see FilterInterface::filter
     * @param mixed $row
     * @return mixed
     */
    public function filter($row)
    {
        if ($this->callArray) {
            $return = call_user_func_array($this->importMethod, $row);
        } else {
            $return = call_user_func($this->importMethod, $row);
        }
        return $return;
    }

    /**
     * Will be called if no callback method was defined
     *
     * @param mixed $row
     * @throws RuntimeException
     */
    public function notDefined($row)
    {
        throw new \RuntimeException('The callback method is not defined - use self::setCallback');
    }

    /**
     * Set the description of this filter
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
