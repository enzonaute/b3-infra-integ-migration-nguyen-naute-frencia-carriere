<?php


use PHPUnit\Framework\TestCase;

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

    }

}
