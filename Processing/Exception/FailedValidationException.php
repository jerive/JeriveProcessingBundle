<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Exception;

use Symfony\Component\Form\FormError;

class FailedValidationException extends Exception
{
    /**
     * @var array<FormError>
     */
    protected $validationErrors;

    public function setValidationErrors($errors)
    {
        $this->validationErrors = $errors;
    }

    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}