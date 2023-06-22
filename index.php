<?php
session_start();

require_once('classes/Database.php');

$conn = Database::getInstance();

// Traitement du formulaire de connexion
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des informations de connexion
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->execute(['email' => $email]);
    $row = $stmt->fetch();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        header("Location: forms.php");
        exit();
    } else {
        $login_error = "Nom d'utilisateur ou mot de passe invalide";
    }
}

// Traitement du formulaire d'inscription
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des champs obligatoires
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = "Tous les champs sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Adresse e-mail invalide";
    } elseif ($password != $confirm_password) {
        $register_error = "Les mots de passe ne correspondent pas";
    } else {
        // Vérification de l'existence d'un utilisateur avec le même nom d'utilisateur ou la même adresse e-mail
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row) {
            $register_error = "Nom d'utilisateur ou adresse e-mail déjà utilisé";
        } else {
            // Insertion des informations de l'utilisateur dans la base de données
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
            $stmt->execute(['name' => $name,'surname'=>$surname, 'email' => $email, 'password' => $hashed_password]);

            $_SESSION['user_id'] = $conn->lastInsertId();
            header("Location: forms.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Application d'inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php if (isset($login_error)) { ?>
    <div class="error"><?php echo $login_error; ?></div>
<?php } ?>

<h1>Connexion</h1>
<form method="post">
    <label for="email">Adresse e-mail
        <input type="email" name="email" id="email" required>
    </label><br>
    <label for="password">Mot de passe
        <input type="password" name="password" id="password" required>
    </label><br>
    <input type="submit" name="login" value="Se connecter">
</form>
<?php if (isset($register_error)) { ?>
    <div class="error"><?php echo $register_error; ?></div>
<?php } ?>
<h1>Inscription</h1>
<form method="post">
    <label for="name">Nom
        <input type="text" name="name" id="name" required>
    </label><br>
    <label for="surname">Prénom
        <input type="text" name="surname" id="surname" required>
    </label><br>
    <label for="email">Adresse e-mail
        <input type="email" name="email" id="email" required>
    </label><br>
    <label for="password">Mot de passe
        <input type="password" name="password" id="password" required>
    </label><br>
    <label for="confirm_password">Confirmation du mot de passe
        <input type="password" name="confirm_password" id="confirm_password" required>
    </label><br>
    <input type="submit" name="register" value="S'inscrire">
</form>
</body>
</html>
