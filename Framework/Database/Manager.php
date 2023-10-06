<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

class Manager extends Database
{
    public function persist(object $entity): void
    {
        // TODO - if entity->getId() is null, then insert, else update
        if (null === $entity->getId()) {
            $this->insert($entity);
        } else {
            $this->update($entity);
        }
    }

    public function flush(): void
    {
        $this->execute();
    }

    private function insert(object $entity): void
    {
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();
        $columns = [];
        $prepareParams = [];
        $values = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($property->getValue($entity) instanceof ModelInterface) {
                $columns[] = $propertyName.'_id';
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity)->getId();
            } elseif ('DateTime' === $property->getType()->getName() && null !== $property->getValue($entity)) {
                $columns[] = $propertyName;
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity)->format('Y-m-d H:i:s');
            } elseif ('array' !== $property->getType()->getName() && null !== $property->getValue($entity)){
                $columns[] = $propertyName;
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity);
            }
        }
        $columns = implode(', ', $columns);
        $prepareParams = implode(', ', $prepareParams);
        $query = "INSERT INTO ".strtolower($reflection->getShortName())." ({$columns}) VALUES ({$prepareParams})";
        $this->prepare($query);
        $this->params = $values;
    }

    private function update(object $entity): void
    {
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();
        $columns = [];
        $values = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($property->getValue($entity) instanceof ModelInterface) {
                // TODO - do weed need to change this here ?
                $columns[] = $propertyName.'_id';
                $values[] = $property->getValue($entity)->getId();
            } elseif ('DateTime' === $property->getType()->getName() && null !== $property->getValue($entity)) {
                $columns[] = $propertyName.'=:'.$propertyName;
                $values[$propertyName] = $property->getValue($entity)->format('Y-m-d H:i:s');
            } elseif ('array' !== $property->getType()->getName() && null !== $property->getValue($entity)){
                $columns[] = $propertyName.'=:'.$propertyName;
                $values[$propertyName] = $property->getValue($entity);
            }
        }
        $columns = implode(', ', $columns);
        $query = "UPDATE ".strtolower($reflection->getShortName())." SET {$columns} WHERE id = {$entity->getId()}";
        $this->prepare($query);
        $this->params = $values;
    }
}
