<?php

declare(strict_types=1);

namespace App\Repository;

use DateTime;
use Framework\Database\Database;
use PDO;
use ReflectionClass;

class ServiceRepositoryOldTwo extends Database
{
    private static $firstCall = true;
    private string $entityName;

    private function buildEntity(mixed $objet, mixed $row)
    {
        if (self::$firstCall) {
            $originalEntity = $this->entityName;
            var_dump($originalEntity);
            $reflectionClass = new ReflectionClass($objet);

            foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
                $propertyName = $property->getName();
                $type = $property->getType()->getName();

                if ('int' === $type) {
                    $objet->{'set' . ucfirst($propertyName)}($row[$propertyName]);
                } elseif ('string' === $type) {
                    if (isset($row[$propertyName])) {
                        $objet->{'set' . ucfirst($propertyName)}($row[$propertyName]);
                    }
                } elseif ('DateTime' === $type) {
                    if (null === $row[$propertyName]) {
                        $objet->{'set' . ucfirst($propertyName)}(null);
                    } else {
                        $dateTime = new \DateTime($row[$propertyName]);
                        $objet->{'set' . ucfirst($propertyName)}($dateTime);
                    }
                } elseif ('array' === $type) {
                    //var_dump($propertyName);
                    //var_dump('array type here');
                    $repository = substr($propertyName, 0, -1);
                    $otherObjects = $this->getRepository('App\\Model\\' . ucfirst($repository))->findBy([strtolower($reflectionClass->getShortName()) . '_id' => $objet->getId()]);
                    //var_dump($otherObjects);
                    //var_dump($objet); // Article
                    var_dump($originalEntity);
                    var_dump($this->entityName);
                    var_dump($objet);
                    if ($otherObjects) {
                        foreach ($otherObjects as $otherObject) {
                            $objet->{'add' . ucfirst($repository)}($otherObject);
                        }
                    }
                    //var_dump($objet);
                }
            }
            self::$firstCall = false;

            return $objet;
        } else {
            var_dump($this->entityName);
        }
        /*
        //var_dump($objet);
        $reflectionClass = new ReflectionClass($objet);

        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE) as $property) {
            $propertyName = $property->getName();
            $type = $property->getType()->getName();
            //var_dump($property->getType()->getName());

            //var_dump($propertyName);
            //var_dump($type);

            if ('int' === $type) {
                //var_dump('int type here');
                $objet->{'set'.ucfirst($propertyName)}($row[$propertyName]);
            } elseif ('string' === $type) {
                //var_dump($objet);
                //var_dump($type);
                //var_dump($propertyName);
                //var_dump($row[$propertyName]);
                if (isset($row[$propertyName])) {
                    $objet->{'set'.ucfirst($propertyName)}($row[$propertyName]);
                }
            } elseif ('DateTime' === $type) {
                //var_dump('DateTime type here');
                //var_dump($row[$propertyName]);
                //$value = $row[$propertyName] ?? null;
                //$dateTime = new \DateTime($row[$propertyName]);
                //$objet->{'set'.ucfirst($propertyName)}($dateTime);

                if (null === $row[$propertyName]) {
                    $objet->{'set'.ucfirst($propertyName)}(null);
                } else {
                    $dateTime = new \DateTime($row[$propertyName]);
                    $objet->{'set'.ucfirst($propertyName)}($dateTime);
                }
            } elseif ('array' === $type) {
                var_dump($propertyName);
                //var_dump('array type here');
                $repository = substr($propertyName, 0, -1);
                $otherObjects = $this->getRepository('App\\Model\\'.ucfirst($repository))->findBy([strtolower($reflectionClass->getShortName()).'_id' => $objet->getId()]);
                var_dump($otherObjects);
                var_dump($objet); // Article
                if ($otherObjects) {
                    foreach ($otherObjects as $otherObject) {
                        $objet->{'add'.ucfirst($repository)}($otherObject);
                    }
                }
                var_dump($objet);

                return $objet;
            }
        */

        return $objet;
    }

    public function findAll(): ?array
    {
        $connection = $this->getConnection();
        $tableName = (new ReflectionClass($this->entityName))->getShortName();
        $query = $connection->prepare('SELECT * FROM '.$tableName);
        $query->execute([]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $row){
            $entity = new $this->entityName;
            $entities[] = $this->buildEntity($entity, $row);
        }
        $query->closeCursor();

        var_dump($entities);
        die;

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
        //var_dump($result);
        $entities = [];
        foreach ($result as $row){
            $entity = new $this->entityName;
            $entities[] = $this->buildEntity($entity, $row);
        }
        $query->closeCursor();

        //var_dump($entities);
        //die;

        return $entities;
    }

    public function getRepository(string $entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }
}
