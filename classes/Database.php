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
            // Log the error and throw an exception
            error_log("Database connection failed: " . $this->connection->connect_error);
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    /**
     * Get the MySQLi connection instance
     *
     * @return mysqli
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    /**
     * Prepare an SQL statement for execution
     *
     * @param string $query SQL query string
     * @return \mysqli_stmt|false
     */
    public function prepare(string $query)
    {
        // Prepare and return the statement using the global mysqli_stmt class
        return $this->connection->prepare($query);
    }

    /**
     * Close the database connection
     */
    public function close(): void
    {
        // If the connection is not already closed, close it
        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }
    }
}
