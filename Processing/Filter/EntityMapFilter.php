<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

/**
 * Description of MapFilter
 *
 * @author jviveret
 */
class EntityMapFilter extends MapFilter
{
    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $accessor;

    protected $transforms = array();

    public function setEntityClass($class)
    {
        $this->entityClass = $class;
        return $this;
    }

    public function setTransforms($transforms = array())
    {
        $this->transforms = $transforms;
        return $this;
    }

    public function filter($row)
    {
        if (!isset($this->accessor)) {
            $this->accessor = PropertyAccess::getPropertyAccessor();
        }

        $row      = parent::filter($row);
        $entity   = new $this->entityClass;

        foreach($row as $key => $value) {
            try {
                $this->accessor->setValue($entity, $key, $value);
            } catch (NoSuchPropertyException $e) {
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
