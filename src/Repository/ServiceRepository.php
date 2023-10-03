<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Comment;
use Framework\Database\Database;
use Framework\Database\Model\ModelInterface;
use PDO;
use PDOStatement;
use ReflectionClass;

class ServiceRepository extends Database
{
    protected function executeQuery(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetchEntities(string $query, string $className, array $params = []): array
    {
        $stmt = $this->executeQuery($query, $params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $entities = [];
        foreach ($data as $entityData) {
            $entities[] = $this->buildEntity($entityData, $className);
        }
        var_dump($entities);

        return $entities;
    }

    protected function buildEntity(array $data, string $className): object
    {
        $reflection = new ReflectionClass($className);
        //$entity = $reflection->newInstanceWithoutConstructor();
        $entity = new $className;

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $data)) {
                $property->setAccessible(true);
                if ('DateTime' === $property->getType()->getName() && null !== $data[$propertyName]) {
                    $property->setValue($entity, new \DateTime($data[$propertyName]));
                } else {
                    $property->setValue($entity, $data[$propertyName]);
                }
            } elseif ($propertyName === 'comments' && property_exists($entity, 'comments')) {
                // Si la propriété est 'comments' et existe dans l'entité, hydrate-la avec les commentaires
                $comments = $this->findBy('comment', Comment::class, ['article_id' => $entity->getId()]);
                foreach ($comments as $comment) {
                    $entity->addComment($comment);
                }
                $property->setAccessible(true);
                $property->setValue($entity, $comments);
            }
            //var_dump($entity->getId());
        }

        return $entity;
    }

    public function findBy(string $tableName, string $className, array $criteria): array
    {
        $whereConditions = [];
        $params = [];
        foreach ($criteria as $column => $value) {
            $whereConditions[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $query = "SELECT * FROM $tableName WHERE " . implode(' AND ', $whereConditions);
        $stmt = $this->executeQuery($query, $params);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $entities = [];
        foreach ($data as $entityData) {
            $entities[] = $this->buildEntity($entityData, $className);
        }

        return $entities;
    }

    public function findOneBy(string $tableName, string $className, array $criteria): ?ModelInterface
    {
        $whereConditions = [];
        $params = [];
        foreach ($criteria as $column => $value) {
            $whereConditions[] = "$column = :$column";
            $params[":$column"] = $value;
        }

        $query = "SELECT * FROM $tableName WHERE " . implode(' AND ', $whereConditions);
        $stmt = $this->executeQuery($query, $params);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return $this->buildEntity($data, $className);
        }

        return null;
    }
}
