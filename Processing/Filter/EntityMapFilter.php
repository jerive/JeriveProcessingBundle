<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

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

    public function setEntityClass($class)
    {
        $this->entityClass = $class;
        return $this;
    }

    public function filter($row)
    {
        $row = parent::filter($row);
        $entity     = new $this->entityClass;
        $reflection = new \ReflectionObject($entity);

        foreach($row as $key => $value) {
            if ($reflection->hasProperty($key)) {
                $property = $reflection->getProperty($key);
                $property->setAccessible(true);
                $property->setValue($entity, $value);
            } else {
                $entity->$key = $value;
            }
        }

        return $entity;
    }
}
