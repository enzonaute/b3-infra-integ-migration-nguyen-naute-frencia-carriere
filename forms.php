<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include the Form class
require_once 'classes/Form.php';
require_once('classes/Database.php');

$conn = Database::getInstance();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $sender_email = $_POST['sender_email'];
    $sender_id = $_POST['sender_id'];
    $receiver_email = $_POST['receiver_email'];
    $object = $_POST['object'];
    $message = $_POST['message'];

    // Create a new form object
    $form = new Form($sender_email, $sender_id, $receiver_email, $object, $message);

    // On l'envoie
    $form->send();

    // Redirect to the same page to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
$user_id = $_SESSION['user_id'];

// Retrieve all forms for the current user
$stmt = $conn->prepare("SELECT * FROM forms WHERE receiver_id = :user_id");
$stmt->execute(['user_id'=>$user_id]);
$received_forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve all forms for the current user
$stmt = $conn->prepare("SELECT * FROM forms WHERE sender_id = :user_id");
$stmt->execute(['user_id'=>$user_id]);
$sent_forms = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Formulaires></title>
    <script type = "text/javascript" src="functions.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="title">
    <h1>Formulaires</h1>
</div>
<div class="menu">
    <h2 class="disconnect"><a href="javascript:clearAndRedirect('index.php')">Deconnexion</a></h2>
    <h2><a href="profile.php">Profil</a></h2>
</div>

<h1>Send a form</h1>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="sender_email">Sender email:</label>
    <input type="email" name="sender_email" id="sender_email" maxlength="50" required><br>

    <input type="hidden" name="sender_id" value="<?php echo $_SESSION['user_id']; ?>">

    <label for="receiver_email">Receiver email:</label>
    <input type="email" name="receiver_email" id="receiver_email" maxlength="50" required><br>

    <label for="object">Object:</label>
    <input type="text" name="object" id="object" maxlength="50" required><br>
    <label for="message">Message:</label>
    <textarea name="message" id="message" maxlength="500" required></textarea><br>

    <input type="submit" value="Send">
</form>

<h1>List of received forms</h1>
<ul>
    <?php foreach ($received_forms as $form): ?>
        <li>
            <a href="#" onclick="toggleMessage(event, '<?php echo $form['id']; ?>'); return false;"><?php echo $form['object']; ?></a>
            <div id="<?php echo $form['id']; ?>" style="display: none;"><?php echo $form['message']; ?></div>
        </li>
    <?php endforeach; ?>
</ul>

<h1>List of sent forms</h1>
<ul>
    <?php foreach ($sent_forms as $form): ?>
        <li>
            <a href="#" onclick="toggleMessage(event, '<?php echo $form['id']; ?>'); return false;"><?php echo $form['object']; ?></a>
            <div id="<?php echo $form['id']; ?>" style="display: none;"><?php echo $form['message']; ?></div>
        </li>
    <?php endforeach; ?>
</ul>

<script>
    function toggleMessage(event, messageId) {
        event.preventDefault();
        var messageDiv = document.getElementById(messageId);
        if (messageDiv.style.display === 'none') {
            messageDiv.style.display = 'block';
        } else {
            messageDiv.style.display = 'none';
        }
    }
</script>
</body>
</html>
