<?php


use PHPUnit\Framework\TestCase;
require_once('../Form.php');

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
        $form = new Form($this->sender_email, $this->sender_id, $this->receiver_email, $this->object, $this->message);
        $form->send();

        $conn = Database::getInstance();

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
