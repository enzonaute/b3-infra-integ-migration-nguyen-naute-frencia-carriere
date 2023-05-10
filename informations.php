<?php
session_start();
// Inclure la connexion à la base de données
require 'db_connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}



// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];

    // Validation des données
    if (empty($name) || empty($email)) {
        $error = "Tous les champs sont requis";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse e-mail invalide";
    } else {
        // Mettre à jour les informations utilisateur dans la base de données
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE users SET name=:name, surname=:surname email=:email WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['name' => $name,'surname' => $surname, 'email' => $email, 'id' => $user_id]);
        $stmt->closeCursor();

        // Mettre à jour les informations de session de l'utilisateur
        $_SESSION['name'] = $name;
        $_SESSION['surname'] = $surname;
        $_SESSION['email'] = $email;

        // Rediriger vers la page de profil avec un message de succès
        header("Location: profile.php?success=update");
        exit();
    }

    // Récupérer les informations de l'utilisateur depuis la base de données
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT name, surname, email FROM users WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['id' => $user_id]);
    $stmt->fetchAll(PDO::FETCH_BOTH,$name,$surname,$email);
    $stmt->fetch();
    $stmt->close();

    // Afficher le formulaire de mise à jour des informations utilisateur
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Profile - My Web App</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
    <h1>Profile</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        <br>

        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname); ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="********" readonly>
        <button type="button" onclick="clearPassword()">Edit</button>
        <br>

        <button type="submit">Save</button>
        <span class="warning"><?php echo $warning; ?></span>
    </form>

    <script>
        function clearPassword() {
            document.getElementById('password').value = '';
        }
    </script>
    </body>
    </html>
<?php
}
else{
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Erreur</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="container">
        <h1>Oups, il y a eu une erreur</h1>
        <div class="error">
            <p>
                <?php
                $value = "";
                foreach ($_POST as $value){
                    echo $value;
                }?>
            </p>
        </div>
        <div class="error">
            <p>Veuillez vous reconnecter :
                <a href="index.php">Accueil
                </a>
            </p>
        </div>
    </div>
    </body>
    </html>
<?php
}


