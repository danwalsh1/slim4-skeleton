<?php

namespace App\Application\Infrastructure\Persistence\Repositories;

use App\Application\Domain\Interfaces\UserRepositoryInterface;
use App\Application\Exceptions\RecordNotFoundException;
use PDO;

class UserDatabaseRepository implements UserRepositoryInterface
{
    private const SQL_FIND_ALL_USERS = 'SELECT * FROM users';
    private const SQL_FIND_USER_BY_ID = 'SELECT * FROM users WHERE id = :id';
    private const SQL_CREATE = "INSERT INTO users (username, first_name, last_name)" .
    " VALUES (:username, :first_name, :last_name)";

    /**
     * @var PDO The database connection.
     */
    private PDO $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection.
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function getAll(): array
    {
        $stmt = $this->connection->prepare(self::SQL_FIND_ALL_USERS);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * @inheritDoc
     * @throws RecordNotFoundException
     */
    #[\Override]
    public function findUserById(int $id): array
    {
        $stmt = $this->connection->prepare(self::SQL_FIND_USER_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        if (!$row) {
            throw new RecordNotFoundException(sprintf('User not found: %s', $id));
        }

        return $row;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function create(array $user): int
    {
        $stmt = $this->connection->prepare(self::SQL_CREATE);
        $stmt->execute($user);

        return (int)$this->connection->lastInsertId();
    }
}
