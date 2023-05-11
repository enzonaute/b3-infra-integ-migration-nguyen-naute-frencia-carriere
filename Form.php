<?php
require_once('Database.php');


function getReceiverID(string $receiver_email): int{
    $query = "SELECT id FROM users WHERE email=:email";
    $stmt = Database::getInstance()->prepare($query);
    $stmt->execute(['email'=>$receiver_email]);
    $row = $stmt->fetch();
    return $row["id"];
}

class Form {
    private $sender_email;
    private $sender_id;
    private $receiver_email;
    private $receiver_id;
    private $object;
    private $message;

    public function __construct(string $sender_email, int $sender_id, string $receiver_email, string $object, string $message) {
        $this->sender_email = $sender_email;
        $this->sender_id = $sender_id;
        $this->receiver_email = $receiver_email;
        $this->receiver_id = getReceiverID($this->receiver_email);
        $this->object = $object;
        $this->message = $message;
    }

    public function send(): bool {
        $query = "INSERT INTO forms (sender_email, sender_id, receiver_email, receiver_id, object, message) 
                    VALUES (:sender_email, :sender_id, :receiver_email, :receiver_id, :object, :message)";

        $stmt = Database::getInstance()->prepare($query);
        if (!$stmt->execute([
            "sender_email"=>$this->sender_email,
            "sender_id"=>$this->sender_id,
            "receiver_email"=> $this->receiver_email,
            "receiver_id"=>$this->receiver_id,
            "object"=>$this->object,
            "message"=>$this->message]))
        {
            return false;
        }
        return true;
    }

    public function read(): string {
        // implementation of the read() method
        // returns true if the message was successfully read, false otherwise
        // you can use the attributes of the class to identify the message to be read and retrieve its content from the appropriate source (e.g. email inbox, messaging service, etc.)
        return "";
    }


}