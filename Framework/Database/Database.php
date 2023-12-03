<?php

namespace Framework\Database;

use Exception;
use Framework\Exception\GenericException;
use PDO;

abstract class Database
{
    const DB_HOST = 'mysql:host=localhost;dbname=blog;charset=utf8';
    const DB_USER = 'root';
    const DB_PASS = '';
    private \PDOStatement|false $statement;
    protected array $params = [];
    private ?string $entityName = null;

    public function getConnection()
    {
        try {
            $connection = new PDO(self::DB_HOST, self::DB_USER, self::DB_PASS);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch(Exception $errorConnection) {
            throw new GenericException('Connection error :'.$errorConnection->getMessage());
        }
    }

    public function prepare($query)
    {
        $this->statement = $this->getConnection()->prepare($query);

        return $this->statement;
    }

    public function execute()
    {
        try {
            $this->statement->execute($this->params);
        } catch (Exception $exception) {
            throw new GenericException('Error : ' . $exception->getMessage());
        }
        // TODO - close cursor
        //$this->statement->closeCursor();
    }

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    public function setEntityName(?string $entityName): void
    {
        $this->entityName = $entityName;
    }
}
