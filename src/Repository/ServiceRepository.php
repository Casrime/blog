<?php

declare(strict_types=1);

namespace App\Repository;

use Framework\Database\Database;
use Framework\Database\Model\ModelInterface;
use PDO;
use PDOStatement;
use ReflectionClass;

class ServiceRepository extends Database
{
    protected function buildEntity(array $data): object
    {
        $entityName = $this->getEntityName();
        $reflection = new ReflectionClass($this->getEntityName());
        $entity = new $entityName;

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $data)) {
                $property->setAccessible(true);
                if ('DateTime' === $property->getType()->getName() && null !== $data[$propertyName]) {
                    $property->setValue($entity, new \DateTime($data[$propertyName]));
                } else {
                    $property->setValue($entity, $data[$propertyName]);
                }
            }
        }

        return $entity;
    }

    public function findAll()
    {
        return $this->findBy([]);
    }

    public function find(int $id)
    {
        $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $entity = $this->buildEntity($result);
        $query->closeCursor();

        return $entity;
    }

    // TODO - handle parameters
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName);
        $query->execute([]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $entry){
            $entities[] = $this->buildEntity($entry);
        }
        $query->closeCursor();

        return $entities;
    }

    // TODO - handle parameters
    public function findOneBy(array $criteria, array $orderBy = []): ?ModelInterface
    {
        $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        $query->execute($criteria);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            return null;
        }
        $entity = $this->buildEntity($result);
        $query->closeCursor();

        return $entity;
    }

    public function count(array $criteria)
    {

    }
}
