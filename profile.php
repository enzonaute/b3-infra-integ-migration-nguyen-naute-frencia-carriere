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
session_start();

require_once('classes/Database.php');

$conn = Database::getInstance();

// Sécurité, fait l'équivalent d'une route sur un framework
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}


//On récupère l'identifiant stocké dans le cookie
$user_id = $_SESSION['user_id'];


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
        $query = "UPDATE users SET name=:name, surname=:surname, email=:email, password=:password WHERE id=:user_id";
        $stmt = $conn->prepare($query);

        if (!$stmt->execute(["name"=>$name,"surname"=>$surname,"email"=>$email,"password"=>password_hash($password, PASSWORD_DEFAULT),"user_id"=>$user_id])) {
            $warning = 'Failed to update information. Please try again.';
        }
        echo "<h1 class='modifs'>Modifications enregistrées</h1>";
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])){
    $query = "DELETE FROM users WHERE id=:user_id";
    $stmt = $conn->prepare($query);

    if (!$stmt->execute(["user_id"=>$user_id])) {
        $warning = 'Failed to update information. Please try again.';
    }
    header('Location: index.php');
    exit();
}

$query = "SELECT name, surname, email FROM users WHERE id=:user_id";
$stmt = $conn->prepare($query);
$stmt->execute(["user_id"=>$user_id]);
$row = $stmt->fetch();

$name = $row['name'];
$surname = $row['surname'];
$email = $row['email'];

$editable = isset($_GET['edit']) && $_GET['edit'] === 'true';
?>

<form method="post" action="">
    <label for="name">Nom:</label>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="surname">Prénom:</label>
    <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" value="<?php echo str_repeat('*', 10); ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <?php if (!$editable): ?>
        <a href="?edit=true"><button type="button">Modifier</button></a>
    <?php else: ?>
        <button type="submit" name="save">Enregistrer</button>
    <?php endif; ?>
    <br>
    <br>
    <button class="delete" type="submit" onclick="clearAndRedirect('index.php')" name="delete"><b>SUPPRIMER LE COMPTE</b></button>
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