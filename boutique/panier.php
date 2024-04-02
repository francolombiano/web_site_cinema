<?php
require_once "../inc/functions.inc.php";


// if (!isset($_SESSION['user'])) {

//     header("location:" . RACINE_SITE . "authentification.php");
// }

//     debug($_POST);
if (isset($_POST["ajout_panier"])) {

    $id_film = htmlentities($_POST['id_film']);
    $quantity = htmlentities($_POST['quantity']);
    // debug($id_film);


    if (!isset($quantity) || empty($quantity)) {

        header("location:" . RACINE_SITE . "showFilm.php");
    } else {


        if (!isset($_SESSION['panier'])) {

            $_SESSION["panier"] = array();
        }


        $film_existe = false;

        foreach ($_SESSION['panier'] as $key => $film) {
            if ($film['id_film'] === $id_film) {

                $_SESSION['panier'][$key]['quantity'] += $quantity;
                $film_existe = true;
                break;
            }
        }
        if (!$film_existe) {

            $new_film = array(
                'id_film' => $id_film,
                'quantity' => $quantity,
                'title' => $_POST['title'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'image' => $_POST['image']

            );
            $_SESSION['panier'][] = $new_film;
        }
    }
}

if (isset($_GET['id_film']) && isset($_SESSION['panier'])) {

    $idFilmForDelete = $_GET['id_film'];

    foreach ($_SESSION['panier'] as $key => $filmPanier) {
        if ($filmPanier['id_film'] === $idFilmForDelete) {

            unset($_SESSION['panier'][$key]);
            break;
        }
    }
} else if (isset($_GET['vider'])) {

    unset($_SESSION['panier']);
}








$title = "Panier";

require_once "../inc/header.inc.php";

?>




<div class="panier d-flex justify-content-center" style="padding-top:8rem;">


    <div class="d-flex flex-column  mt-5 p-5">
        <h2 class="text-center fw-bolder mb-5 text-danger">Mon panier</h2>


        <?php
        $info = '';


        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {

            $info = alert("Votre panier est vide", "danger");
            echo $info;
        } else {


        ?>
            <a href="?vider" class="btn align-self-end mb-5">Vider le panier</a>

            <table class="fs-4">
                <tr>
                    <th class="text-center text-danger fw-bolder">Affiche</th>
                    <th class="text-center text-danger fw-bolder">Nom</th>
                    <th class="text-center text-danger fw-bolder">Prix</th>
                    <th class="text-center text-danger fw-bolder">Quantité</th>
                    <th class="text-center text-danger fw-bolder">Sous-total</th>
                    <th class="text-center text-danger fw-bolder">Supprimer</th>
                </tr>

                <?php

                foreach ($_SESSION['panier'] as  $film$) {
                ?>
                    <tr>
                        <td class="text-center border-top border-dark-subtle"><a href="<?= RACINE_SITE ?>showFilm.php?id_film=<?= $film['id_film'] ?>"><img src="<?= RACINE_SITE . "assets/img/" . $film['image'] ?>" style="width: 100px;"></a></td>
                        <td class="text-center border-top border-dark-subtle"><?= $film['title'] ?></td>
                        <td class="text-center border-top border-dark-subtle"><?= $film['price'] ?>€</td>
                        <td class="text-center border-top border-dark-subtle d-flex align-items-center justify-content-center" style="padding: 7rem;">

                            <?= $film['quantity'] ?>

                        </td>
                        <td class="text-center border-top border-dark-subtle"><?= $sousTotal = $film['price'] * $film['quantity'] ?>€</td>
                        <td class="text-center border-top border-dark-subtle"><a href="?id_film=<?= $film['id_film'] ?>"><i class="bi bi-trash3"></i></a></td>
                    </tr>
                <?php
                }

                ?>
                <tr class="border-top border-dark-subtle">
                    <th class="text-danger p-4 fs-3">Total : <?= $total = calculerMontantTotal($_SESSION['panier']) ?>€</th>
                </tr>



            </table>
            <form action="checkout.php" method="post">
                <input type="hidden" name="total" value="<?= $total ?>">
                <button type="submit" class="btn btn-danger mt-5 p-3" id="checkout-button">Payer</button>


            </form>

        <?php

        }
        ?>
    </div>
</div>