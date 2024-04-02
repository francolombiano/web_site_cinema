<?php

require_once "../inc/functions.inc.php";

if (!isset($_SESSION['user'])) {
    header("location:" . RACINE_SITE . "authentification.php");
} else {
    if ($_SESSION['user']['role'] == 'ROLE_USER') {
        header("location:" . RACINE_SITE . "index.php");
    }
}

// ********************************************************

if (isset($_GET['action']) && isset($_GET['id_category'])) {

    if (!empty($_GET['action']) && $_GET['action'] == 'update' && !empty($_GET['id_category'])) {

        $idCategory = $_GET['id_category'];
        $category = showCategory($idCategory);
    }
}

if (isset($_GET['action']) && isset($_GET['id_category'])) {
    if (!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id_category'])) {

        $idCategory = $_GET['id_category'];
        $category = deleteCategory($idCategory);
    }
}
// ********************************************************



$info = '';
if (!empty($_POST)) {
    $verif = true;

    foreach ($_POST as $value) {

        if (empty($value)) {

            $verif = false;
        }
    }

    if (!$verif) {

        $info = alert("Veuillez renseigner tout les champs", "danger");
    } else {

        $categoryName = isset($_POST['name']) ? $_POST['name'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;

        if (strlen($categoryName) < 3 || preg_match('/[0-9]+/', $categoryName)) {
            $info = alert("Le nom de la catégorie n'est pas valide", "danger");
        }

        if (strlen($description) < 50) {
            $info += alert("La description doit faire au moins 50 caractères", "danger");
        }

        if (empty($info)) {
            $categoryName = strip_tags($categoryName);
            $description = strip_tags($description);

            addCategory($categoryName, $description);

            $info = alert('Catégorie ajoutée avec succès!', 'success');
        }
    }
}


$title = "Catégories";
// require_once "../inc/header.inc.php"


?>


<div class="row mt-5 justify-content-center">
    <div class="col-sm-12 col-md-5 m-5">
        <h2 class="text-center fw-bolder mb-5"><?= isset($category) ? 'Modifier une catégorie' : 'Ajouter une catégorie' ?></h2>

        <?php
        echo $info;
        ?>

        <form action="" method="post" class="">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <label for="name">Nom de la catégorie</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $category['name'] ?? '' ?>">
                </div>
                <div class="col-md-12 mb-5">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" cols="30" rows="10" class="form-control"><?= $category['description'] ?? '' ?></textarea>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-danger p-3 fs-3"><?= isset($category) ? 'Modifier' : 'Ajouter' ?></button>
                </div>
            </div>
        </form>
    </div>


    <div class="col-sm-12 col-md-5 m-5">
        <h2 class="text-center fw-bolder mb-5">Liste des catégories</h2>
        <table class="table table-dark table-bordered mt-5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Supprimer</th>
                    <th>Modifier</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $categories = allCategories();

                foreach ($categories as $category) {


                ?>
                    <tr>
                        <td><?= $category['id_category'] ?></td>
                        <td><?= ucfirst($category['name']) ?></td>
                        <td><?= substr(ucfirst($category['description']), 0, 40) . "..." ?></td>
                        <td class="text-center"><a href="?categories_php&action=delete&id_category=<?= $category['id_category'] ?>"><i class="bi bi-trash3-fill text-danger"></i></td>
                        <td class="text-center"><a href="?categories_php&action=update&id_category=<?= $category['id_category'] ?>"><i class="bi bi-pen-fill"></i></a></td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>



<?php
require_once "../inc/footer.inc.php"
?>