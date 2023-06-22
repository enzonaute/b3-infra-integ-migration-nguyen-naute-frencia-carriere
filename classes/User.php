<?php


require_once('classes/Database.php');


class User
{
    private $id;
    private $name;
    private $surname;
    private $email;
    private $phone;
    private $address;

    /**
     * @param $id
     * @param $name
     * @param $surname
     * @param $email
     * @param string $phone optional
     * @param string $address optional
     */
    public function __construct($name, $surname, $email, string $phone = "", string $address = "")
    {

        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function register(string $password){
        $conn = Database::getInstance();

        $hashed_password =
        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
        $stmt->execute(['name' => $this->name,'surname'=>$this->surname, 'email' => $this->email, 'password' => $password]);

        // On ne stocke pas le mot de passe (même hashé) dans la session
        $this->id = $conn->lastInsertId();
        $_SESSION['user'] = serialize($this);
    }
}