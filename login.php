<?php
session_start();

require __DIR__ . '/includes/db-connection.php';

if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['username'])){

    if(mb_strlen($_POST['username']) < 5 || mb_strlen($_POST['username']) > 50){

        $errors[] = '<p class="error">Votre nom d\'utilisateur doit contenir entre 5 et 50 caractères !</p>';

    }

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

        $errors[] = '<p class="error">Votre adresse e-mail est invalide !</p>';

    }

    if(!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u', $_POST['password'])){

        $errors[] = '<p class="error">Votre mot de passe doit contenir au moins 8 caractères dont 1 chiffre, une lettre minuscule, une lettre majuscule et un caractère spécial !</p>';

    }

    if(!isset($errors)){

        $username = $db->prepare("SELECT * FROM users WHERE username=?");

        $username->execute([$_POST['username']]);

        $userInfo = $db->prepare("SELECT * FROM users WHERE email=?");

        $userInfo->execute([$_POST['email']]);

        $emailUser = $username->fetch();

        $user = $userInfo->fetch();

        if($user){

            if(password_verify($_POST['password'], $user['password'])){

                if(isset($_SESSION['user'])){

                    $errors[] = '<p class="error">Vous êtes déjà connecté !</p>';

                } else{

                    if($emailUser){

                        $success = '<p class="success">Vous êtes bien connecté !</p>';

                        $_SESSION['user'] = [
                            'email' => $_POST['email'],
                            'password' => $_POST['password'],
                            'username' => $_POST['username'],
                        ];

                        $username->closeCursor();


                    } else{

                        $errors[] = '<p class="error">Votre nom d\'utilisateur est incorrecte !</p>';

                    }

                }

            } else{

                $errors[] = '<p class="error">Le mot de passe est incorrect !</p>';

            }

        } else{

            $errors[] = '<p class="error">Ce compte n\'existe pas !</p>';

        }

        $userInfo->closeCursor();

    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <?php include __DIR__ .'/includes/include-link.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Connexion</h1>

    <!-- Formulaire de connexion -->
    <form action="login.php" method="POST">

        <label for="username">Votre nom d'utilisateur :</label>
        <input type="text" placeholder="Nom d'utilisateur" name="username" id="username">

        <label for="email">Votre e-mail :</label>
        <input type="text" placeholder="E-mail" name="email" id="email">

        <label for="password">Votre mot de passe :</label>
        <input type="password" placeholder="Mot de passe" name="password" id="password">

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
    <?php include __DIR__ . '/includes/translate.php'; ?>
</body>
</html>