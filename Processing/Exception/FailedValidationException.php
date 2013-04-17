<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class FailedValidationException extends \Exception
{
    /**
     * @var ConstraintViolationList
     */
    protected $constraintViolations;

    /**
     * @param ConstraintViolationList $list
     */
    public function setConstraintViolations(ConstraintViolationList $list)
    {
        $this->constraintViolations = $list;
        return $this;
    }

    public function getConstraintViolations()
    {
        return $this->constraintViolations;
    }

    public function getViolationsAsString()
    {
        $violations = array();
        foreach($this->constraintViolations as $violation) {
            $violations[] = $violation->getPropertyPath() . ':' . $violation->getInvalidValue() . ':' . $violation->getMessage();
        }

        return implode("\n", $violations);
    }
}
