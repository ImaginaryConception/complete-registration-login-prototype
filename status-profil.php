<?php
session_start();

require __DIR__ . '/includes/db-connection.php';

if(isset($_SESSION['user'])){

    $successStatus = '<p class="success">Vous êtes bien connecté !</p>';

} else{

    $errorStatus = '<p class="error">Vous n\'êtes pas connecté ! Connectez-vous <a href="login.php">ici</a> !</p>';

}

if(isset($_POST['email'])){

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

        $errors[] = '<p class="error">Votre adresse e-mail est invalide !</p>';

    }

    if(!isset($errors)){

        $userInfo = $db->prepare("SELECT * FROM users WHERE email=?");

        $userInfo->execute([$_POST['email']]);

        $user = $userInfo->fetch();

        if($user){

            $errors[]  = '<p class="error">Cette adresse e-mail a déjà été utilisée !</p>';

        } else{

            $success = '<p class="success">Adresse e-mail sauvegardée !</p>';

            $email = $db->prepare("UPDATE users SET email = ?");

            $email->execute([$_POST['email']]);

            $_SESSION['user'] = [
                'email' => $_POST['email'],
            ];

        }

    }

}
if(isset($_POST['username'])){

    if(mb_strlen($_POST['username']) < 5 || mb_strlen($_POST['username']) > 50){

        $errors[] = '<p class="error">Votre nom d\'utilisateur doit contenir entre 5 et 50 caractères !</p>';

    }

    if(!isset($errors)){

        $userInfo = $db->prepare("SELECT * FROM users WHERE username=?");

        $userInfo->execute([$_POST['username']]);

        $user = $userInfo->fetch();

        if($user){

            $errors[]  = '<p class="error">Ce nom d\'utilisateur a déjà été utilisée !</p>';

        } else{

            $success = '<p class="success">Nom d\'utilisateur sauvegardé !</p>';

            $username = $db->prepare("UPDATE users SET username = ?");

            $username->execute([$_POST['username']]);

        }

    }

}

if(isset($_POST['password'])){

    if(!preg_match('/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[ !"#\$%&\'()*+,\-.\/:;<=>?@[\\\\\]\^_`{\|}~]).{8,4096}$/u', $_POST['password'])){

        $errors[] = '<p class="error">Votre mot de passe doit contenir au moins 8 caractères dont 1 chiffre, une lettre minuscule, une lettre majuscule et un caractère spécial !</p>';

    }

    if(!isset($errors)){

        $success = '<p class="success">Mot de passe sauvegardé !</p>';

        $password = $db->prepare("UPDATE users SET password = ?");

        $password->execute([password_hash($_POST['password'], PASSWORD_BCRYPT)]);

    }

}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status et profil</title>
    <?php include __DIR__ .'/includes/include-link.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/navbar.php'; ?>
    <h1>Status et profil</h1>

    <?php

    if(isset($successStatus)){

        echo $successStatus;

    }

    if(isset($errorStatus)){

        echo $errorStatus;

    }

    if(isset($_SESSION['user'])){

        ?>

        <hr>

        <p><strong>E-mail</strong> : <?php if(isset($_SESSION['user']['email'])){echo $_SESSION['user']['email'];} ?></p>
        <form action="status-profil.php" method="POST">
            <input class="new-input" type="text" placeholder="Nouvelle adresse e-mail" name="email">
            <input class="new-submit" type="submit" value="Confirmer">
        </form>

        <p><strong>Nom d'utilisateur</strong> : <?php if(isset($_SESSION['user']['username'])){echo $_SESSION['user']['username'];} ?></p>
        <form action="status-profil.php" method="POST">
            <input class="new-input" type="text" placeholder="Nouveau nom d'utilisateur" name="username">
            <input class="new-submit" type="submit" value="Confirmer">
        </form>

        <p><strong>Mot de passe</strong> : <?php if(isset($_SESSION['user']['password'])){echo $_SESSION['user']['password'];} ?></p>
        <form action="status-profil.php" method="POST">
            <input class="new-input" type="password" placeholder="Nouveau mot de passe" name="password">
            <input class="new-submit" type="submit" value="Confirmer">
        </form>


        <?php
    }

    if(isset($success)){

        echo $success;

    }

    if(isset($errors)){

        foreach($errors as $error){

            echo $error;

        }

    }

    include __DIR__ . '/includes/translate.php'; ?>
</body>
</html>