<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Database\Model\ModelInterface;
use PDO;
use ReflectionClass;

class ServiceRepository extends Database implements ServiceRepositoryInterface
{
    private function buildObject(array $data): object
    {
        // TODO - do not hydrate field named 'password' for User
        $entityName = $this->getEntityName();
        $reflection = new ReflectionClass($this->getEntityName());
        $entity = new $entityName;

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            if (array_key_exists($propertyName, $data)) {
                $property->setAccessible(true);
                if ('DateTime' === $property->getType()->getName() && null !== $data[$propertyName]) {
                    $property->setValue($entity, new \DateTime($data[$propertyName]));
                }
                // TODO - add elseif to handle array like roles for User
                else {
                    $property->setValue($entity, $data[$propertyName]);
                }
            }
        }
        /* OLD method working with setters
        $entity = new ($this->getEntityName());
        foreach ($data as $key => $value) {
            $method = 'set'.ucwords($key);
            var_dump($key);
            var_dump($value);
            if ($value instanceof \DateTime) {
                var_dump('datetime');
            }
            if (method_exists($entity, $method)) {
                $entity->$method($value);
            }
        }
        */
        //var_dump($entity);

        return $entity;
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }

    public function find(int $id): ?ModelInterface
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
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
        /*
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName);
        var_dump($query);
        $query->execute([]);
        */
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        var_dump($query);
        $query->execute($criteria);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $entities = [];
        foreach ($result as $entry){
            $entities[] = $this->buildEntity($entry);
        }
        $query->closeCursor();

        return $entities;
    }

    // TODO - handle parameters
    public function findOneBy(array $criteria, ?array $orderBy = []): ?ModelInterface
    {
        $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
        $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
        $query->execute($criteria);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            return null;
        }
        $entity = $this->buildObject($result);
        $entity = $this->handleRelations($entity);

        $query->closeCursor();

        return $entity;
    }

    private function handleRelations(ModelInterface $model): ModelInterface
    {
        $reflection = new ReflectionClass($model);
        foreach ($reflection->getProperties() as $property) {
            if (class_exists($property->getType()->getName()) && new ($property->getType()->getName()) instanceof ModelInterface) {
                $this->setEntityName($property->getType()->getName());
                $criteria = ['id' => $model->getId()];
                $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
                $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
                $query->execute($criteria);
                $result = $query->fetch(PDO::FETCH_ASSOC);
                $associatedEntity = $this->buildObject($result);
                $model->{'set' . ucwords($property->getName())}($associatedEntity);
                // TODO - change 'Framework\Database\CollectionInterface' ?
            } elseif (interface_exists($property->getType()->getName()) && $property->getType()->getName() === 'Framework\Database\CollectionInterface') {
                // TODO - handle array of ModelInterface relation
                $modelInterfaceName = substr(ucfirst($property->getName()), 0, -1);
                $entityName = str_replace($reflection->getShortName(), $modelInterfaceName, $property->class);
                $this->setEntityName($entityName);
                $criteria = [lcfirst($reflection->getShortName()).'_id' => $model->getId()];
                $tableName = (new ReflectionClass($this->getEntityName()))->getShortName();
                $query = $this->getConnection()->prepare('SELECT * FROM '.$tableName.' WHERE '.array_keys($criteria)[0].' = :'.array_keys($criteria)[0]);
                $query->execute($criteria);
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $entry){
                    $model->{'add' . $modelInterfaceName}($this->buildObject($entry));
                }
            }
        }
        //var_dump($model);
        //die;

        return $model;
    }
}
