     <?php
     require_once "inc/functions.inc.php";

     $title = "Accueil";
     require_once "inc/header.inc.php";




     $info = "";

     if (isset($_GET) && !empty($_GET)) {
          if (isset($_GET['id_category'])) {
               $message = "films à vous proposer dans cette categorie";
               $films = filmByCategory($_GET['id_category']);

               if (count($films) == 0) {
                    $info = alert("Aucun film dans cette categorie", "danger");
               }
          } else if (isset($_GET['Voir plus'])) {
               $films = allFilms();
               $message = "films à vous proposer.";
          }
     }else{
          $films = filmByDate();
          $message = "films récents à vous proposer.";
     }


     ?>



     <!-- Main -->
     <main class="container">


          <div class="films">

               <h2 class="fw-bolder fs-1 my-5 mx-5"><span class="nbreFilms"><?= count($films) ?></span>        <?= ($message) ?? "films" ?></h2>

               <div class="row">

                    <?php
                    echo $info;

                    foreach ($films as $film) {
                    ?>

                         <div class="card bg-dark m-2 mx-auto" style="width: 35rem;">
                              <img src="<?= RACINE_SITE."assets/img/".$film['image'] ?>" class="card-img-top" alt="image du film">
                              <div class="card-body d-flex flex-column">
                                   <h3 class="card-title"><?= ucfirst($film['title']) ?></h3>
                                   <p class="card-text fs-4 "><?= substr(ucfirst($film['synopsis']), 0, 100) . "..." ?></p>
                                   <a href="<?= RACINE_SITE . "showFilm.php?id_film=" . $film['id_film'] ?>" class="btn btn-danger w-50 fs-3 mx-auto ">Plus de détails</a>
                              </div>
                         </div>


                    <?php
                    }

                    if (empty($_GET)) {


                    ?>

                         <div class="col-12 text-center">
                              <a href="<?= RACINE_SITE ?>index.php?voirplus" class="btn p-4 fs-3 bg-light">Voir plus</a>
                         </div>


                    <?php

                    }
                    ?>

               </div>

          </div>


          </div>



     </main>



     <?php
     require_once "inc/footer.inc.php";


     ?>