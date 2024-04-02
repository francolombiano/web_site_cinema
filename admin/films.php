<?php

require_once "../inc/functions.inc.php";

if( !isset($_SESSION['user'])){
    header("location:".RACINE_SITE."authentification.php");
}else{
    if($_SESSION['user']['role'] == 'ROLE_USER'){
        header("location:".RACINE_SITE."index.php");
    }
}



// ************************************************




$title = "Films";

?>

<main>

    <div class="d-flex flex-column m-auto mt-5">

        <h2 class="text-center fw-bolder mb-5 text-danger">Liste des films</h2>
        <a href="gestionFilms.php" class="btn btn-primary p-3 fs-3 align-self-end "> Ajouter un film</a>
        <table class="table table-dark table-bordered mt-5 ">
            <thead>
                <tr>
                    <!-- th*7 -->
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Affiche</th>
                    <th>Réalisateur</th>
                    <th>Acteurs</th>
                    <th>Âge limite</th>
                    <th>Genre</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Synopsis</th>
                    <th>Date de sortie</th>
                    <th>Supprimer</th>
                    <th> Modifier</th>
                </tr>
            </thead>
            <tbody>


 

<?php

        $films = allFilms();

                foreach ($films as $film) {

                    $actors = stringToArray($film['actors']);

                ?>

                    <tr>

                        <!-- Je récupére les valeus de mon tabelau $film dans des td -->
                        <td><?= $film['id_film'] ?></td>
                        <td><?= $film['title'] ?></td>
                        <td> <img src="<?= RACINE_SITE . "assets/img/" . $film['image'] ?>" alt="affiche du film" class="img-fluid upload-img"></td>
                        <td><?= $film['director'] ?> </td>
                        <td>
                            <ul>
                                <?php
                                foreach ($actors as $actor) {
                                ?>
                                    <li><?= $actor; ?></li>



                                <?php

                                }
                                ?>

                            </ul>
                        </td>


                        <td><?= $film['ageLimit'] ?></td>
                        <td><?= $film['title'] ?></td>
                        <td><?= $film['duration'] ?></td>
                        <td><?= $film['price'] ?>€</td>
                        <td><?= $film['stock'] ?></td>
                        <td><?= substr($film['synopsis'], 0, 50) ?>...</td>
                        <td><?= $film['date'] ?></td>
                        <td class="text-center">
                            <a href="gestionFilms.php?action=delete&id_film=<?= $film['id_film'] ?>">
                                <i class="bi bi-trash3-fill text-danger"></i>
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="gestionFilms.php?action=update&id_film=<?= $film['id_film'] ?>">
                                <i class="bi bi-pen-fill"></i>
                            </a>
                        </td>

                    </tr>
                <?php
                }

                ?>


            </tbody>


        </table>


    </div>

</main>

<?php
require_once "../inc/footer.inc.php";
?>


