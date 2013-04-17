<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Exception\FailedValidationException;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * Description of FormFilter
 *
 * @author jviveret
 */
class ValidationFilter extends AbstractFilter
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var array
     */
    protected $groups = array();

    /**
     * @param ValidatorInterface $validator
     * @return ValidationFilter
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     *
     * @param array $groups
     * @return ValidationFilter
     */
    public function setGroups($groups = array())
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        if (!isset($this->validator)) {
            throw new \RuntimeException('You have to set the validator');
        }

        return $this->validator;
    }

    /**
     *
     * @param mixed $row
     * @return mixed
     */
    public function filter($row)
    {
        $list = $this->getValidator()->validate($row, $this->groups);

        if ($list->count() === 0) {
            return $row;
        }

        $exception = new FailedValidationException();
        $exception->setConstraintViolations($list);

        throw $exception;
    }

    /**
     * @see AbstractFilter::getDescription
     * @return array
     */
    public function getDescription()
    {
        return 'Validates objects against validator';
    }
}
