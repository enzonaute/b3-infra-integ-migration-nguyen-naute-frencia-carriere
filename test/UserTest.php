<?php


use PHPUnit\Framework\TestCase;

require_once('classes/Database.php');

class UserTest extends TestCase
{
    private $id;
    private $name;
    private $surname;
    private $email;
    private $password;

    protected function setUp():void
    {
        parent::setUp();
        $this->name = "Iluvatar";
        $this->surname = "Eru";
        $this->email = "eru.iluvatar@tolkien.org";
        $this->password = "azertyuiop";
    }

    public function testCreateUser(){
        $conn = Database::getInstance();

        // On compte le nombre de lignes avant la création d'un nouvel utilisateur
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email'=>$this->email]);
        $row_count_before = $stmt->rowCount();

        $user = new User($this->name,$this->surname,$this->email);
        $user->register(password_hash($this->password,PASSWORD_DEFAULT));

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email'=>$this->email]);
        $row_count_after = $stmt->rowCount();

        // Comparaison des 2 valeurs de nombre de lignes
        self::assertEquals($row_count_before+1,$row_count_after);
    }

    public function testUpdateUser(){
        $nameReplacement = "Second";
        $surnameReplacement = "Arathorn";
        // On décide arbitrairement de ne pas changer l'email
        $emailReplacement = $this->email;

        $conn = Database::getInstance();

        $user = new User($this->name,$this->surname,$this->email);
        $user->register(password_hash($this->password,PASSWORD_DEFAULT));

        $user->setAttributes($nameReplacement,$surnameReplacement,$emailReplacement);

        // Retrieve all forms for the current user
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id LIMIT 1");
        $stmt->execute(['user_id'=>$user->getId()]);
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        self::assertEquals($nameReplacement,$user['name']);
        self::assertEquals($surnameReplacement,$user['surname']);
        self::assertEquals($this->email,$user['email']);
    }
}
