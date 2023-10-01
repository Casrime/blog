<?php

declare(strict_types=1);

namespace App\Repository;

use Framework\Database\Database;
use PDO;
use ReflectionClass;

class ServiceRepositoryOld extends Database
{
    private string $entityName;
    private array $entityNamesAlreadyUsed = ['App\Model\Article'];
    private string $dataToHydrate;
    private array $entities = [];

    private function buildEntity(array $row)
    {
        $entity = new $this->entityName;
        $reflect = new \ReflectionClass($entity);
        $attributes = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            $type = $attribute->getType()->getName();

            if (isset($row[$attributeName])) {
                $value = $row[$attributeName];
                if ($type === 'DateTime' && null !== $value) {
                    $dateTime = new \DateTime($value);
                    $entity->{'set'.ucfirst($attributeName)}($dateTime);
                } else {
                    $entity->{'set'.ucfirst($attributeName)}($value);
                }
            } else {
                if ($type === 'array') {
                    // TODO - get comments based on article id
                    //var_dump($entity);
                    //var_dump($entity->getId());
                    // TODO - add entity name
                    // TODO - get these values only if the entity name is not the original one
                    //var_dump($attribute);
                    //var_dump($attributeName);
                    //var_dump($reflect->getShortName());
                    $entityName = ucfirst(substr($attributeName, 0, -1));
                    // TODO - Can we change this here to make this more dynamic with syntax like $entityName::class ?
                    $comments = $this->getRepository('\\App\\Model\\'.$entityName)->findBy([strtolower($reflect->getShortName()).'_id' => $entity->getId()]);
                    //var_dump($comments);
                    //die;
                    //var_dump($comments);
                    // loop on each comment and hydrate the article object
                    var_dump($entity);
                    var_dump($comments);
                    foreach ($comments as $comment) {
                        if ($comment !== false) {
                            var_dump($comment);
                            $entity->addComment($comment);
                        }
                    }

                    //var_dump($entity);
                    //var_dump($entity->getComments());
                    //die;
                }
            }
        }

        if ($entity instanceof $this->dataToHydrate) {
            $this->entities[] = $entity;

            return $entity;
        }

        $lastEntity = end($this->entities);
        //var_dump($lastEntity);
        var_dump($this->entities);

        if (false !== $lastEntity) {
            //var_dump($lastEntity);
            $lastEntity->addComment($entity);
            array_pop($this->entities);
            var_dump($this->entities);
            // TODO - replace the last entity by the new one in the array
            $this->entities[] = $lastEntity;
            var_dump($this->entities);
        }

        // TODO - get the entity to hydrate and push data to it
        return $lastEntity;
    }

    public function findAll(): ?array
    {
        $connection = $this->getConnection();
        $this->dataToHydrate = $this->entityName;
        $tableName = (new ReflectionClass($this->entityName))->getShortName();
        $query = $connection->prepare('SELECT * FROM '.$tableName);
        $query->execute([]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $row){
            $entities[] = $this->buildEntity($row);
        }
        $query->closeCursor();

        return $entities;
    }

    public function find($id)
    {
        $connection = $this->getConnection();
        $tableName = (new ReflectionClass($this->entity))->getShortName();
        $query = $connection->prepare('SELECT * FROM '.$tableName.' WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $entity = $this->buildEntity($result);
        $query->closeCursor();

        return $entity;
    }

    public function findBy(array $criteria): ?array
    {
        $connection = $this->getConnection();
        $tableName = (new ReflectionClass($this->entityName))->getShortName();
        $query = $connection->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        $query->execute($criteria);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $row){
            $entities[] = $this->buildEntity($row);
        }
        $query->closeCursor();

        //var_dump($entities);
        //die;

        return $entities;
    }

    public function getRepository($entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }
}
