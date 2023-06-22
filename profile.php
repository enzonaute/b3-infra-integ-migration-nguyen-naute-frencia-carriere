<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Profil - Mes infos></title>
    <script type = "text/javascript" src="functions.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div class="title">
    <h1>Profil</h1>
</div>
<div class="menu">
    <h2 class="disconnect"><a href="javascript:clearAndRedirect('index.php')">Deconnexion</a></h2>
    <h2><a href="forms.php">Formulaires</a></h2>
</div>

<?php

require_once('classes/Database.php');
require_once('classes/User.php');

session_start();

$conn = Database::getInstance();

// Sécurité, fait l'équivalent d'une route sur un framework
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

//On récupère l'objet User stocké dans le cookie
$user = unserialize($_SESSION['user']);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check for empty fields
    if (empty($name) || empty($surname) || empty($email)) {
        $warning = 'Please fill all required fields.';
    }
    // Check email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $warning = 'Please enter a valid email address.';
    }
    // Update fields
    else {
        $user->setAttributes($name,$surname,$email);
        if(!$user->update(password_hash($password, PASSWORD_DEFAULT))){
            echo "<h1 class='modifs'>Erreur lors de la modification</h1>";
        }

        echo "<h1 class='modifs'>Modifications enregistrées</h1>";
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])){
    if (!$user->deleteFromDB()) {
        echo "<h1 class='modifs'>Erreur lors de la suppression du compte.</h1>";
    }
    else {
        echo "<script type='type/JavaScript'>clearAndRedirect('index.php');</script>";
        header("Location: index.php");
        exit();
    }
}

$editable = isset($_GET['edit']) && $_GET['edit'] === 'true';


$_SESSION['user'] = serialize($user);
?>

<form method="post" action="">
    <label for="name">Nom:</label>
    <input type="text" id="name" name="name" value="<?php echo $user->name; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="surname">Prénom:</label>
    <input type="text" id="surname" name="surname" value="<?php echo $user->surname; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $user->email; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" value="<?php echo str_repeat('*', 10); ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <?php if (!$editable): ?>
        <a href="?edit=true"><button type="button">Modifier</button></a>
    <?php else: ?>
        <button type="submit" name="save">Enregistrer</button>
    <?php endif; ?>
    <br>
    <br>
    <button class="delete" type="submit" name="delete"><b>SUPPRIMER LE COMPTE</b></button>
</form>

<script>
    const form = document.querySelector('form');
    const editButton = document.querySelector('#edit');

    editButton.addEventListener('click', () => {
        // Enable all fields
        form.querySelectorAll('input').forEach((input) => {
            input.removeAttribute('disabled');
        });
    });
</script>
</body>
</html>