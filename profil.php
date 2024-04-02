<?php

require_once "inc/functions.inc.php";

if (empty($_SESSION['user'])) {

    header("location:".RACINE_SITE."authentification.php");

} else if ( $_SESSION['user']['role'] == 'ROLE_ADMIN') {

    header("location:".RACINE_SITE."admin/dashboard.php?dashboard_php");


    } 





$title = "Profil";
require_once "inc/header.inc.php";
?>

<main>
    <h2 class="text-center">Bonjour <?=$_SESSION['user']['firstName']?></h2>

</main>




<?php
require_once "inc/footer.inc.php";

?>