<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

class Manager extends Database implements ManagerInterface
{
    public function persist(object $entity): void
    {
        if (null === $entity->getId()) {
            $this->insert($entity);
        } else {
            $this->update($entity);
        }
    }

    public function remove(object $entity): void
    {
        $this->delete($entity);
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
            if ($property->getType()->getName() === 'Framework\Database\CollectionInterface') {
                continue;
            } elseif ($property->getValue($entity) instanceof ModelInterface) {
                $columns[] = $propertyName.'_id';
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity)->getId();
            } elseif ('DateTime' === $property->getType()->getName() && null !== $property->getValue($entity)) {
                $columns[] = $propertyName;
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity)->format('Y-m-d H:i:s');
            } elseif ('array' === $property->getType()->getName()) {
                $columns[] = $propertyName;
                $prepareParams[] = '?';
                $values[] = json_encode($property->getValue($entity));
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
            if ($property->getType()->getName() === 'Framework\Database\CollectionInterface') {
                continue;
            } elseif ($property->getValue($entity) instanceof ModelInterface) {
                $columns[] = $propertyName.'_id=:'.$propertyName.'_id';
                $values[$propertyName.'_id'] = $property->getValue($entity)->getId();
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

    private function delete(object $entity): void
    {
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ('Framework\Database\CollectionInterface' === $property->getType()->getName()) {
                $query = "DELETE FROM ".substr($propertyName, 0, -1)." WHERE ".strtolower($reflection->getShortName())."_id = {$entity->getId()}";
                $this->prepare($query);
                $this->flush();
            }
        }
        $query = "DELETE FROM ".strtolower($reflection->getShortName())." WHERE id = {$entity->getId()}";
        $this->prepare($query);
    }
}
