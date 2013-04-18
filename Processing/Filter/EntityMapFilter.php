<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

use Symfony\Component\PropertyAccess\PropertyAccess;

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

    public function setEntityClass($class)
    {
        $this->entityClass = $class;
        return $this;
    }

    public function filter($row)
    {
        if (!isset($this->accessor)) {
            $this->accessor = PropertyAccess::getPropertyAccessor();
        }

        $row      = parent::filter($row);
        $entity   = new $this->entityClass;
        //$reflection = new \ReflectionObject($entity);

        foreach($row as $key => $value) {
            $this->accessor->setValue($entity, $key, $value);

//            if ($reflection->hasProperty($key)) {
//                $property = $reflection->getProperty($key);
//                $property->setAccessible(true);
//                $property->setValue($entity, $value);
//            } else {
//                $entity->$key = $value;
//            }
        }

        return $entity;
    }
}
