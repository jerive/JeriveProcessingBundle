<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

/**
 * Description of MapFilter
 *
 * @author jviveret
 */
class MapFilter extends AbstractFilter
{
    /**
     * Fields to map rows to
     *
     * @var array
     */
    protected $fields = array();

    /**
     * Fields with title
     *
     * @var array
     */
    protected $mapping = array();

    /**
     * Number of fields
     *
     * @var int
     */
    protected $fieldNumber;

    /**
     * Whether or not to dicard additional columns
     *
     * @var bool
     */
    protected $discardAdditionalColumns = true;

    /**
     * Whether to initialize missing columns and not reject them
     *
     * @var bool
     */
    protected $initializeMissingColumns = false;

    /**
     * Sets the fields to map rows to
     *
     * @param array $fields
     */
    public function setMapping($fields = array())
    {
        $this->mapping = $fields;
        $this->fields = array_keys($fields);
        $this->fieldNumber = count($this->fields);
    }

    /**
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * Discard additional columns ?
     *
     * @param bool $flag
     */
    public function setDiscardAdditionalColumns($flag = true)
    {
        $this->discardAdditionalColumns = $flag;
        return $this;
    }

    /**
     * Initialize missing columns ?
     *
     * @param bool $flag
     */
    public function setInitializeMissingColumns($flag = true)
    {
        $this->initializeMissingColumns = $flag;
        return $this;
    }

    /**
     * Checks and maps csv columns with expected fields name
     *
     * @param array $row
     * @throws RuntimeException
     *     If the number of fields expected does not match the actual number of fields
     * @return array
     */
    public function filter($row)
    {
        $nbFields = count($row);
        $diff     = $nbFields - $this->fieldNumber;

        // Make too large rows pass anyways
        if ($this->discardAdditionalColumns && $diff > 0) {
            while($diff != 0) {
                array_pop($row);
                $diff -= 1;
            }
        } else if ($this->initializeMissingColumns && $diff < 0) {
            while($diff != 0) {
                $row[] = null;
                $diff += 1;
            }
        } elseif ($diff != 0) {
            throw new \RuntimeException(
                sprintf(_('Number of fields does not match : %s expected but %s found'),
                $this->fieldNumber,
                $nbFields
            ));
        }

        $combine = array_combine($this->fields, $row);

        return $combine;
    }

    public function getDescription()
    {

    }
}
