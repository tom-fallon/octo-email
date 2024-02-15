<?php

namespace Api\src\Database;

use Api\src\Messages\ErrorMessages;
use PDOException;
use PDO;

/**
 * Class MysqlDatabase
 */
class MysqlDatabase implements DbConnectionInterface
{
    private PDO $connection;

    public function __construct(array $connectionDetails)
    {
        try {
            $this->connection = $this->connect($connectionDetails);
        } catch (PDOException $e) {
            echo 'Fatal Error';
        }
    }

    /**
     * @param array $connectionDetails
     * @return PDO
     */
    public function connect(array $connectionDetails): PDO
    {
        return new PDO(
            "mysql:host=localhost;dbname=" . $connectionDetails['dbname'],
            $connectionDetails['username'],
            $connectionDetails['password']
        );
    }

    /**
     * @param string $table
     * @return array<mixed>
     */
    public function list(string $table): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM $table ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param array<mixed> $data
     * @return string|bool
     */
    public function insert(string $table, array $data): string|bool
    {
        try {
            $keys = array_keys($data);

            $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (:" . implode(',:', $keys) . ")";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($data);

            return 'Success';
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                return ErrorMessages::duplicateEntryMessage();
            }
            return false;
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return string
     */
    public function delete(string $table, int $id): string
    {
        try {
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            return 'Success';
        } catch (PDOException $e) {
            return ErrorMessages::fatalErrorMessage();
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return array<mixed>|string
     */
    public function load(string $table, int $id): array|string
    {
        try {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ErrorMessages::fatalErrorMessage();
        }
    }


    /**
     * @param string $table
     * @param string|int $key
     * @param string $value
     * @return array|bool
     */
    public function find(string $table, string|int $key, string $value): array|bool
    {
        $sql = "SELECT * FROM $table WHERE $key = :value";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['value' => $value]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
