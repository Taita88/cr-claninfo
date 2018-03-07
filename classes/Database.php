<?php

class Database
{

    //TODO: put values somewhere else maybe?
    private $host = "localhost";

    private $db_name = "clash_royale";

    private $username = "root";

    private $password = "";

    /*
     * private $host = "sql2.webzdarma.cz";
     * private $db_name = "crclaninfobo8496";
     * private $username = "crclaninfobo8496";
     * private $password = "Ghof06e";
     * public $conn;
     */
    
    // get the database connection
    public function getConnection()
    {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>