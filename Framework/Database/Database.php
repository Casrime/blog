<?php

namespace Framework\Database;

use Exception;
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
        //Tentative de connexion à la base de données
        try{
            $connection = new PDO(self::DB_HOST, self::DB_USER, self::DB_PASS);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //On renvoie la connexion
            return $connection;
        }
            //On lève une erreur si la connexion échoue
        catch(Exception $errorConnection)
        {
            die ('Erreur de connection :'.$errorConnection->getMessage());
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
            die('Erreur : ' . $exception->getMessage());
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
