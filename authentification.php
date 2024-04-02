<?php

require_once "inc/functions.inc.php";




$info = '';

if (!empty($_POST)) {
    // debug($_POST);

    $verif = true;

    foreach ($_POST as $value) {


        if (empty($value)) {

            $verif = false;
        }
    }

    if (!$verif) {
        // debug($_POST);


        $info = alert("Veuillez renseigner tout les champs", "danger");
    } else {

        // debug($_POST);
        $pseudo = isset($_POST['pseudo']) ? $_POST['pseudo'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;

        $user = checkUser($email, $pseudo);
        debug($user);

        if ($user) {

            if (password_verify($mdp, $user['mdp'])) {

                $_SESSION['user'] = $user;

                header("location:" . RACINE_SITE . "profil.php");
            } else {
                $info = alert("Les identifiants sont incorrectes", "danger");
            }
        }

        // } else {
        //     $info = alert("Les identifiants sont incorrectes", "danger");


        // }





    }
}

$title = "Authentification";
require_once "inc/header.inc.php";
?>

<main style="background:url(assets/img/5818.png) no-repeat; background-size: cover; background-attachment: fixed;" class="pt-5 mt-5">

    <div class="w-75 m-auto p-5" style="background: rgba(20, 20, 20, 0.9);">
        <h2 class="text-center p-3 mb-5">Connexion</h2>

        <?php

        echo $info;

        ?>

        <form action="" method="post" class="p-5">
            <div class="row mb-3">
                <div class="col-12 mb-5">
                    <label for="pseudo" class="form-label mb-3">Pseudo</label>
                    <input type="text" class="form-control fs-5" id="pseudo" name="pseudo">
                </div>
                <div class="col-12 mb-5">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control fs-5" id="email" name="email" placeholder="exemple.email@exemple.com">
                </div>
                <div class="col-12 mb-5">
                    <label for="mdp" class="form-label mb-3">Mot de passe</label>
                    <input type="password" class="form-control fs-5 mb-3" id="mdp" name="mdp">
                    <input type="checkbox" onclick="showPass()"> <span class="text-danger">Afficher/masquer le mot de passe</span>
                </div>

                <button class="w-25 m-auto btn btn-danger btn-lg fs-5" type="submit">Se connecter</button>
                <p class="mt-5 text-center">Vous n'avez pas encore de compte ! <a href="register.php" class=" text-danger">créer un compte ici</a></p>
            </div>
        </form>


    </div>


</main>






<?php
require_once "inc/footer.inc.php";

?>