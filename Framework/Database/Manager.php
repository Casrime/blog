<?php

declare(strict_types=1);

namespace Framework\Database;

class Manager extends Database
{
    public function persist(object $entity): void
    {
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties();
        $columns = [];
        $prepareParams = [];
        $values = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ('DateTime' === $property->getType()->getName() && null !== $property->getValue($entity)) {
                $columns[] = $propertyName;
                $prepareParams[] = '?';
                $values[] = $property->getValue($entity)->format('Y-m-d H:i:s');
            } elseif ('array' !== $property->getType()->getName() && null !== $property->getValue($entity)) {
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

    public function flush(): void
    {
        $this->execute();
    }
}
