<?php

namespace App;

use mysqli;
use Exception;

class Database
{
    private ?mysqli $connection = null;

    public function __construct(
        string $host = 'sql210.infinityfree.com',
        string $user = 'if0_37596667',
        string $password = 'JvuVZ8SXgBmP',
        string $dbname = 'if0_37596667_product_db'
    ) {
        $this->connection = new mysqli($host, $user, $password, $dbname);

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function prepare(string $query)
    {
        return $this->connection->prepare($query);
    }

    public function close(): void
    {
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
