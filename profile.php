<!DOCTYPE html>
<html>

<head>
    <title>Profile - My Web App</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<h1>Profile</h1>

<?php
session_start();

require_once('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

//if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//    $user_id = $_SESSION['user_id'];
//
//    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
//    $stmt->execute(["user_id" => $user_id]);
//    $row = $stmt->fetch();
//    // Pour debugger
////    foreach ($row as $item){
////        //echo $item ."</br>";
////    }
//
//    $name = $row['name'];
//    $surname = $row['surname'];
//    $email = $row['email'];
//    //$password = $row['password'];
//
//    $user_id = $_SESSION['user_id'];
//    $warning = '';
//}


//On récupère l'identifiant stocké dans le cookie
$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        //$stmt->bind_param('ssssi', $name, $surname, $email, password_hash($password, PASSWORD_DEFAULT), $user_id);
        if (!$stmt->execute(["name"=>$name,"surname"=>$surname,"email"=>$email,"password"=>password_hash($password, PASSWORD_DEFAULT),"user_id"=>$user_id])) {
            $warning = 'Failed to update information. Please try again.';
        }
        echo "<h1 class='modifs'>Modifications enregistrées</h1>";
    }
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
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $name; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="surname">Surname:</label>
    <input type="text" id="surname" name="surname" value="<?php echo $surname; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $email; ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" value="<?php echo str_repeat('*', 10); ?>" <?php if (!$editable) echo 'disabled'; ?> required>

    <?php if (!$editable): ?>
        <a href="?edit=true"><button type="button">Edit</button></a>
    <?php else: ?>
        <button type="submit" name="save">Save</button>
    <?php endif; ?>
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
<!---->
<?php
//}
//else{
//    ?>
<!---->
<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <title>Erreur</title>-->
<!--    <link rel="stylesheet" href="style.css">-->
<!--</head>-->
<!--<body>-->
<!--<div class="container">-->
<!--    <h1>Oups, il y a eu une erreur</h1>-->
<!--    <div class="error">-->
<!--        <p>-->
<!--            --><?php
//            $value = "";
//            foreach ($_POST as $value){
//                echo $value;
//            }?>
<!--        </p>-->
<!--    </div>-->
<!--    <div class="error">-->
<!--        <p>Veuillez vous reconnecter :-->
<!--            <a href="index.php">Accueil-->
<!--            </a>-->
<!--        </p>-->
<!--    </div>-->
<!--</div>-->
<!--</body>-->
<!--</html>-->
<?php
//}


