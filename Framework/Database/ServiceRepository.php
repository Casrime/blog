<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;

class ServiceRepository extends Database implements ServiceRepositoryInterface
{
    private \PDOStatement|false $query;
    private string $getterRelation;

    private function buildObject(array $data): object
    {
        $entityName = $this->getEntityName();
        $reflection = new \ReflectionClass($this->getEntityName());
        $entity = new $entityName();

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            if ('password' === $propertyName) {
                continue;
            }
            if (array_key_exists($propertyName, $data)) {
                $property->setAccessible(true);
                if ('DateTime' === $property->getType()->getName() && null !== $data[$propertyName]) {
                    $property->setValue($entity, new \DateTime($data[$propertyName]));
                } elseif ('array' === $property->getType()->getName()) {
                    $property->setValue($entity, json_decode($data[$propertyName]));
                } else {
                    $property->setValue($entity, $data[$propertyName]);
                }
            }
        }
        $matches = preg_grep('/[a-z]*_/', array_keys($data));
        if (0 < count($matches)) {
            $foreignKey = reset($matches);
            $relationName = substr($foreignKey, 0, -3);
            $relatioNameWithFQCN = preg_replace('/\\\\([A-Za-z]+)$/', '\\'.ucfirst($relationName), $entityName);
            $relatedEntity = new $relatioNameWithFQCN();
            $relatedEntity->setId($data[$foreignKey]);
            $entity->{'set'.ucfirst($relationName)}($relatedEntity);
            $this->getterRelation = 'get'.ucfirst($relationName);
        }

        return $entity;
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function find(int $id): ?ModelInterface
    {
        $tableName = (new \ReflectionClass($this->getEntityName()))->getShortName();
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE id = :id');
        $query->execute(['id' => $id]);
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $entity = $this->buildEntity($result);
        $query->closeCursor();

        return $entity;
    }

    public function findBy(array $criteria): array
    {
        $tableName = (new \ReflectionClass($this->getEntityName()))->getShortName();
        if (empty($criteria)) {
            $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName);
            $query->execute([]);
        } else {
            $criteriaString = '';
            $keyNumber = 1;
            foreach ($criteria as $key => $value) {
                if (1 === $keyNumber) {
                    $criteriaString .= 'WHERE '.$key.' = :'.$key;
                    ++$keyNumber;
                } elseif ($keyNumber === count($criteria)) {
                    $criteriaString .= ' AND '.$key.' = :'.$key;
                } else {
                    $criteriaString .= ' AND '.$key.' = :'.$key;
                    ++$keyNumber;
                }
            }
            $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' '.$criteriaString);
            $query->execute($criteria);
        }
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $entry) {
            $entities[] = $this->buildObject($entry);
        }
        $query->closeCursor();

        return $entities;
    }

    public function findOneBy(array $criteria): ?ModelInterface
    {
        $result = $this->fetch($criteria);
        if (false === $result) {
            return null;
        }
        $entity = $this->buildObject($result);
        $entity = $this->handleRelations($entity);

        $this->query->closeCursor();

        return $entity;
    }

    private function handleRelations(ModelInterface $model): ModelInterface
    {
        $reflection = new \ReflectionClass($model);
        foreach ($reflection->getProperties() as $property) {
            if (class_exists($property->getType()->getName()) && new ($property->getType()->getName()) instanceof ModelInterface) {
                $this->setEntityName($property->getType()->getName());
                $criteria = ['id' => $model->{$this->getterRelation}()->getId()];
                $result = $this->fetch($criteria);
                if (false !== $result) {
                    $associatedEntity = $this->buildObject($result);
                    $model->{'set'.ucwords($property->getName())}($associatedEntity);
                }
            } elseif (interface_exists($property->getType()->getName()) && 'Framework\Database\CollectionInterface' === $property->getType()->getName()) {
                $modelInterfaceName = substr(ucfirst($property->getName()), 0, -1);
                $entityName = str_replace($reflection->getShortName(), $modelInterfaceName, $property->class);
                $this->setEntityName($entityName);
                $criteria = [lcfirst($reflection->getShortName()).'_id' => $model->getId()];
                $result = $this->fetchAll($criteria);
                foreach ($result as $entry) {
                    $model->{'add'.$modelInterfaceName}($this->buildObject($entry));
                }
            }
        }

        return $model;
    }

    private function fetch(array $criteria): mixed
    {
        $tableName = (new \ReflectionClass($this->getEntityName()))->getShortName();
        $this->query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        $this->query->execute($criteria);

        return $this->query->fetch(\PDO::FETCH_ASSOC);
    }

    private function fetchAll(array $criteria): array
    {
        $tableName = (new \ReflectionClass($this->getEntityName()))->getShortName();
        $this->query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        $this->query->execute($criteria);

        return $this->query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
