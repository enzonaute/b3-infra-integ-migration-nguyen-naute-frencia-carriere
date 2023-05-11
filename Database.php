<?php


class Database
{
    private static $_instance;
    // Connexion à la base de données
    private $servername = "localhost";
    private $username = "appWeb";
    private $password = "pwd_app123";
    private $dbname = "app_web";

    /**
     * @param $connection
     */
    private function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getInstance(): PDO{
        if(is_null(self::$_instance)) {
            self::$_instance = new Database();
        }

        return self::$_instance->connection;
    }


}