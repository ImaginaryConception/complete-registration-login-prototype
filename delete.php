<?php
session_start();

require __DIR__ . '/includes/db-connection.php';

if(isset($_SESSION['user'])){

    $success = '<p class="success">Votre compte a bien été supprimé !</p>';

    $delete = $db->prepare("DELETE FROM users WHERE email = ?");

    $delete->execute([$_SESSION['user']['email']]);

    unset($_SESSION['user']);

} else{

    $error = '<p class="error">Aucun compte à supprimé, vous n\'êtes pas connecté. Connectez-vous <a href="login.php">ici</a> !</p>';

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression</title>
    <?php include __DIR__ .'/includes/include-link.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Suppression</h1>

    <?php

    if(isset($success)){

        echo $success;

    }

    if(isset($error)){

        echo $error;

    }

    ?>
    <?php include __DIR__ . '/includes/translate.php'; ?>
</body>
</html>