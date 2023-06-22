<?php


require_once('classes/Database.php');

// TODO : Les attributes phone et address ne sont pas présentement utilisés
class User
{
    public $id;
    public $name;
    public $surname;
    public $email;
    public $phone;
    public $address;

    /**
     * @param $id
     * @param $name
     * @param $surname
     * @param $email
     * @param string $phone optional
     * @param string $address optional
     */
    public function __construct($name, $surname, $email, int $id = -1, string $phone = "", string $address = "")
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->id = $id;
    }

    public static function getFromEmail($email): User|bool {
        $conn = Database::getInstance();

        $stmt = $conn->prepare("SELECT id, name, surname, email FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            return new User($userData['name'], $userData['surname'], $userData['email'], $userData['id']);
        }

        return false; // Return null if no user found
    }


    /**
     * Méthode utilisée lors de l'inscription d'un utilisateur
     * @param string $password
     * @return void
     */
    public function register(string $password){
        $conn = Database::getInstance();

        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
        $stmt->execute(['name' => $this->name,'surname'=>$this->surname, 'email' => $this->email, 'password' => $password]);

        // On ne stocke pas le mot de passe (même hashé) dans la session
        $this->id = $conn->lastInsertId();
        $_SESSION['user'] = serialize($this);
    }

    /**
     * Méthode utilisée lors de la mise à jour d'attributs sur la page profile.php
     * @param $name
     * @param $surname
     * @param $email
     * @param $phone
     * @param $address
     * @return void
     */
    public function setAttributes($name, $surname, $email, $phone="", $address=""){
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    /**
     * Méthode utilisée après la mise à jour d'attributs sur la page profile.php
     * @param string $password
     * @return void
     */
    public function update(string $password):bool{
        $conn = Database::getInstance();

        $stmt = $conn->prepare("UPDATE users SET name=:name, surname=:surname, email=:email, password=:password WHERE id=:id");

        if (!$stmt->execute(['name' => $this->name,'surname'=>$this->surname, 'email' => $this->email, 'password' => $password, 'id' => $this->id])) {
            return false;
        }
        // On ne stocke pas le mot de passe (même hashé) dans la session
        //$this->id = $conn->lastInsertId();
        //$_SESSION['user'] = serialize($this);
        return true;
    }

    public function deleteFromDB():bool{
        $conn = Database::getInstance();

        $query = "DELETE FROM users WHERE email=:email";
        $stmt = $conn->prepare($query);

        if (!$stmt->execute(["email"=>$this->email])) {
            return false;
        }
        return true;
    }
}