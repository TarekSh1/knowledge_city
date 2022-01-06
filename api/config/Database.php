<?php

namespace Api\Config;

use PDO;
use PDOException;

class Database
{

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "knowledge_city_db";
    private $username = "root";
    private $password = "root";
    public $conn;


    /**
     * @return PDO|null
     */
    public function getConnection(): ?PDO
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            file_put_contents('FailedToConnect.txt', "Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}