<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "appWeb";
$password = "";
$dbname = "app_web";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Traitement du formulaire de connexion
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérification des informations de connexion
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: profile.php");
        exit();
    } else {
        $login_error = "Nom d'utilisateur ou mot de passe invalide";
    }
}

// Traitement du formulaire d'inscription
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérification des champs obligatoires
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = "Tous les champs sont obligatoires";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Adresse e-mail invalide";
    } elseif ($password != $confirm_password) {
        $register_error = "Les mots de passe ne correspondent pas";
    } else {
        // Vérification de l'existence d'un utilisateur avec le même nom d'utilisateur ou la même adresse e-mail
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $register_error = "Nom d'utilisateur ou adresse e-mail déjà utilisé";
        } else {
            // Insertion des informations de l'utilisateur dans la base de données
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password]);

            $_SESSION['user_id'] = $conn->lastInsertId();
            header("Location: profile.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
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
    <label for="username">Nom d'utilisateur
        <input type="text" name="username" id="username" required>
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
    <label for="username">Nom d'utilisateur
        <input type="text" name="username" id="username" required>
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
