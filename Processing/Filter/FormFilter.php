<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Jerive\Bundle\FileProcessingBundle\Processing\Exception\FailedValidationException;
use Symfony\Component\Form\Form;

/**
 * Description of FormFilter
 *
 * @author jviveret
 */
class FormFilter extends AbstractFilter
{
    /**
     *
     * @var Form
     */
    protected $form;

    /**
     *
     * @param Form $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     * @param mixed $row
     * @return mixed
     */
    public function filter($row)
    {
        $this->form->bind($row);
        if ($this->form->isValid($row)) {
            return $this->form->getData();
        }

        $exception = new FailedValidationException();
        $exception->setValidationErrors($this->form->getErrors());

        throw $exception;
    }

    /**
     * @see AbstractFilter::getDescription
     * @return array
     */
    public function getDescription()
    {
        return '';
    }
}
