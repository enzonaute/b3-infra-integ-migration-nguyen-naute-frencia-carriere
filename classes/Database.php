<?php

/**
 * Singleton permettant au serveur une connexion permanente à la base de données
 * On peut alors récupérer l'instance de la connexion courante.
 */

class Database
{
    private $environment = "test";

    private static $_instance;
    // Connexion à la base de données
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $connection;

    /**
     * @param $connection
     */
    private function __construct()
    {
        if($this->environment == "production"){
            $this->servername = "localhost";
            $this->username = "root";
            $this->password = "";
            $this->dbname = "app_web";
        }
        elseif($this->environment == "test"){
            $this->servername = "localhost";
            $this->username = "root";
            $this->password = "";
            $this->dbname = "test";
        }
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
