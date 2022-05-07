<?php
session_start();

if(isset($_SESSION['user'])){

    $success = '<p class="success">Vous êtes bien connecté !</p>';

} else{

    $error = '<p class="error">Vous n\'êtes pas connecté ! Connectez-vous <a href="login.php">ici</a> !</p>';

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status</title>
    <?php include __DIR__ .'/css/include-css.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Status</h1>

    <?php

    if(isset($success)){

        echo $success;

    }

    if(isset($error)){

        echo $error;

    }

    ?>

</body>
</html>