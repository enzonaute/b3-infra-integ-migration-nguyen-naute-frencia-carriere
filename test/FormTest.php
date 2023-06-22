<?php


use PHPUnit\Framework\TestCase;
require_once('../classes/Form.php');

class FormTest extends TestCase
{
    private $sender_email;
    private $sender_id;
    private $receiver_email;
    private $receiver_id;
    private $object;
    private $message;

    protected function setUp():void
    {
        parent::setUp();
        $this->object = "Test formulaire";
        $this->message = "Hello world !";
        $this->receiver_id = 50;
        $this->receiver_email = "receiver.test@epsi.fr";
        $this->sender_id = 49;
        $this->sender_email = "sender.test@epsi.fr";
    }

    public function testSend()
    {
        $conn = Database::getInstance();

        // On compte le nombre de lignes avant l'envoi d'un nouveau formulaire
        $stmt = $conn->prepare("SELECT * FROM forms WHERE receiver_id = :user_id");
        $stmt->execute(['user_id'=>$this->receiver_id]);
        $row_count_before = $stmt->rowCount();

        $form = new Form($this->sender_email, $this->sender_id, $this->receiver_email, $this->object, $this->message);
        $form->send();

        // On compte le nombre de lignes avant l'envoi d'un nouveau formulaire
        $stmt = $conn->prepare("SELECT * FROM forms WHERE receiver_id = :user_id");
        $stmt->execute(['user_id'=>$this->receiver_id]);
        $row_count_after = $stmt->rowCount();

        // Comparaison des 2 valeurs de nombre de lignes
        self::assertEquals($row_count_before+1,$row_count_after);

    }

    public function testRead()
    {
        $conn = Database::getInstance();

        $formToSend = new Form($this->sender_email, $this->sender_id, $this->receiver_email, $this->object, $this->message);
        $formToSend->send();

        // Retrieve all forms for the current user
        $stmt = $conn->prepare("SELECT * FROM forms WHERE receiver_id = :user_id");
        $stmt->execute(['user_id'=>$this->receiver_id]);
        $received_forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $form = $received_forms[0];

        // Test des valeurs du formulaire dans la base de données
        self::assertEquals($this->object,$form['object']);
        self::assertEquals($this->message,$form['message']);
        self::assertEquals($this->receiver_email,$form['receiver_email']);
        self::assertEquals($this->receiver_id,$form['receiver_id']);
        self::assertEquals($this->sender_email,$form['sender_email']);
        self::assertEquals($this->sender_id,$form['sender_id']);
    }

}
