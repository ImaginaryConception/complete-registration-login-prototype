<?php
session_start();

require __DIR__ . '/includes/db-connection.php';

if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm-password'])){

    if(mb_strlen($_POST['username']) < 5 || mb_strlen($_POST['username']) > 50){

        $errors[] = '<p class="error">Votre nom d\'utilisateur doit contenir entre 5 et 50 caractères !</p>';

    }

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

        $errors[] = '<p class="error">Votre adresse e-mail est invalide !</p>';

    }

    if(!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u', $_POST['password'])){

        $errors[] = '<p class="error">Votre mot de passe doit contenir au moins 8 caractères dont 1 chiffre, une lettre minuscule, une lettre majuscule et un caractère spécial !</p>';

    }

    if($_POST['confirm-password'] != $_POST['password']){

        $errors[] = '<p class="error">Votre mot de passe ne correspond pas !</p>';

    }

    if(!isset($errors)){

        $createUser = $db->prepare('INSERT INTO users(username, email, password) VALUES(?, ?, ?)');

        $insertNewUser = $createUser->execute([

            $_POST['username'],
            $_POST['email'],
            password_hash($_POST['password'], PASSWORD_BCRYPT),

        ]);

        if($insertNewUser){

            $success = '<p class="success">Votre compte a bien été créé !</p>';

        } else{

            $errors[] = '<p class="error">Erreur interne, veuillez réessayer !</p>';

        }

        $createUser->closeCursor();

    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <?php include __DIR__ .'/css/include-css.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Inscription</h1>

    <!-- Formulaire d'inscription -->
    <form action="register.php" method="POST">

        <label for="username">Nom d'utilisateur :</label>
        <input type="text" placeholder="Nom d'utilisateur" name="username" id="username">

        <label for="email">Votre e-mail :</label>
        <input type="text" placeholder="E-mail" name="email" id="email">

        <label for="password">Votre mot de passe :</label>
        <input type="password" placeholder="Mot de passe" name="password" id="password">

        <label for="confirm-password">Confirmation du mot de passe :</label>
        <input type="password" placeholder="Confirmez le mot de passe" name="confirm-password" id="confirm-password">

        <input type="submit" class="submit" value="Envoyer">

    </form>

    <?php

        if(isset($success)){

            echo $success;

        }

        if(isset($errors)){

            foreach($errors as $error){

                echo $error;

            }

        }

    ?>

</body>
</html>