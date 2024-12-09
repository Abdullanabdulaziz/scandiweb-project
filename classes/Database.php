<?php

namespace App;

use mysqli;
use Exception;

class Database
{
    private ?mysqli $connection = null;

    public function __construct(
        string $host = 'sql210.infinityfree.com',  // Database host
        string $user = 'if0_37596667',            // Database username
        string $password = 'JvuVZ8SXgBmP',        // Database password
        string $dbname = 'if0_37596667_product_db' // Database name
    ) {
        // Establish the MySQL connection using the provided credentials
        $this->connection = new mysqli($host, $user, $password, $dbname);

        // Check if the connection was successful
        if ($this->connection->connect_error) {
            error_log("Database connection failed: " . $this->connection->connect_error);
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
