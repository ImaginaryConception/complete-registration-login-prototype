<?php
session_start();

if(isset($_SESSION['user'])){

    $success = '<p class="success">Vous avez bien été déconnecté !</p>';

    unset($_SESSION['user']);

} else{

    $error = '<p class="error">Vous devez être connecté <a href="login.php">(en cliquant ici)</a> pour venir sur cette page !</p>';

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déconnexion</title>
    <?php include __DIR__ .'/includes/include-link.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Déconnexion</h1>
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