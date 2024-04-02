<!-- Fichier qui contient les fonctions php à utiliser dans notre site -->
<?php

session_start();

define("RACINE_SITE","/02_site_cinema/"); // constante qui définit les dossiers dans lesquels se situe le site pour pouvoir déterminer des chemin absolus à partir de localhost (on ne prend pas locahost). Ainsi nous écrivons tous les chemins (exp : src, href) en absolus avec cette constante.


///////////////////////////// Fonction de débugage //////////////////////////

function debug($var)
{

    echo '<pre class="border border-dark bg-light text-primary w-50 p-3">';

    var_dump($var);

    echo '</pre>';
}


////////////////////// Fonction d'alert ////////////////////////////////////////

function alert(string $contenu, string $class)
{

    return "<div class='alert alert-$class alert-dismissible fade show text-center w-50 m-auto mb-5' role='alert'>
        $contenu

            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>

        </div>";
}


///////////////////////////// Fonction de déconnexion/////////////////////////

function logOut()
{

    if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'deconnexion') {


        unset($_SESSION['user']);
        // On supprime l'indice "user " de la session pour se déconnecter // cette fonction détruit les variables  stocké  comme 'firstName' et 'email'.

        //session_destroy(); // Détruit toutes les données de la session déjà  établie . cette fonction détruit la session sur le serveur 

        header("location:" . RACINE_SITE . "index.php");
    }
}
// logOut();


///////////////////////////  Fonction de connexion à la BDD //////////////////////////

/**
 * On va utiliser l'extension PHP Data Object (PDO), elle définit une excellente interface pour accèder à une base de données depuis PHP et d'éxécuter des requêtes SQL.
 * pour se connecter à la BDD avec PDO, il faut créer une instance de cette Class/Objet (PDO) qui représente une connexion à la BDD.
 */

// On déclare des constantes d'environnement qui vont contenir les informations à la connexion à la BDD

// Constante du serveur => localhost
define("DBHOST", "localhost");

// Constante de l'utilisateur de la BDD du serveur en local  => root
define("DBUSER", "root");

// Constante pour le mot de passe de serveur en local => pas de mot de passe
define("DBPASS", "");

// Constante pour le nom de la BDD
define("DBNAME", "cinema");


function connexionBdd()
{

    // Sans la variable $dsn et sans le constantes, on se connecte à la BDD :

    // $pdo = new PDO('mysql:host=localhost;dbname=cinema;charset=utf8', 'root', '');

    // avec la variable DSN (Data Source Name) et les constantes

    // $dsn = "mysql:host=localhost;dbname=cinema;charset=utf8";

    $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";

    try {

        $pdo = new PDO($dsn, DBUSER, DBPASS);

        // On définit le mode d'erreur de PDO sur Exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {

        die($e->getMessage());
    }

    return $pdo;
}
// connexionBdd();



///////////////////Fonction qui convertie les string en tableau////////////

//Convertir les string en tableau ///
function stringToArray(string $string): array
{

    $array = explode('/', trim($string)); // je transforme ma chaine de caractère en tableau et supprime les "/"
    //autour de la chaine de caractère
    return $array; // ma fonction retroune un tableau 
}




///////////////// Une fonction pour créer la table users ////////////////////
function createTableUsers()
{

    $pdo = connexionBdd();

    $sql = "CREATE TABLE IF NOT EXISTS users (
            id_user INT PRIMARY KEY AUTO_INCREMENT,
            firstName VARCHAR(50) NOT NULL,
            lastName VARCHAR(50) NOT NULL,
            pseudo VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL,
            mdp VARCHAR(255) NOT NULL,
            phone VARCHAR(30) NOT NULL,
            civility ENUM('f', 'h') NOT NULL,
            birthday DATE NOT NULL,
            address VARCHAR(50) NOT NULL,
            zipCode VARCHAR(50) NOT NULL,
            city VARCHAR(50) NOT NULL,
            country VARCHAR(50) NOT NULL,
            role ENUM('ROLE_USER', 'ROLE_ADMIN') DEFAULT 'ROLE_USER'
        )";

    $request = $pdo->exec($sql);
}

// createTableUsers();

//////////////////// Fonctions du CRUD pour les utilisateurs Users /////////////////////

function inscriptionUsers(string $firstName, string $lastName, string $pseudo, string $email, string $mdp, string $phone, string $civility, string $birthday, string $address, string $zipCode, string $city, string $country): void
{

    $pdo = connexionBdd(); // je stock ma connexion  à la BDD dans une variable

    $sql = "INSERT INTO users 
        (firstName, lastName, pseudo, email, mdp, phone, civility, birthday, address, zipCode, city, country)
        VALUES
        (:firstName, :lastName, :pseudo, :email, :mdp, :phone, :civility, :birthday, :address, :zipCode, :city, :country)"; // Requête d'insertion que je stock dans une variable
    $request = $pdo->prepare($sql); // Je prépare ma requête et je l'exécute
    $request->execute(
        array(
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':pseudo' => $pseudo,
            ':email' => $email,
            ':mdp' => $mdp,
            ':phone' => $phone,
            ':civility' => $civility,
            ':birthday' => $birthday,
            ':address' => $address,
            ':zipCode' => $zipCode,
            ':city' => $city,
            ':country' => $country

        )
    );
}


////////////////// Fonction pour vérifier si un email existe dans la BDD ///////////////////////////////

function checkEmailUser(string $email): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM users WHERE email = :email";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':email' => $email

    ));

    $resultat = $request->fetch();
    return $resultat;
}

////////////////// Fonction pour vérifier si un pseudo existe dans la BDD ///////////////////////////////

function checkPseudoUser(string $pseudo)
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM users WHERE pseudo = :pseudo";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':pseudo' => $pseudo

    ));

    $resultat = $request->fetch();
    return $resultat;
}

/////////// Fonction pour vérifier un utilisateur ////////////////////

function checkUser(string $email, string $pseudo): mixed
{

    $pdo = connexionBdd();

    $sql = "SELECT * FROM users WHERE pseudo = :pseudo AND email = :email";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':pseudo' => $pseudo,
        ':email' => $email


    ));
    $resultat = $request->fetch();
    return $resultat;
}

//  /////////////////Fonction pour récupérer tous les utilisateurs///////////////////


function allUsers(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM users";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}

// /////////////////  Fonction pour recupereer un seul utilisateur  //////////////////////

function showUser(int $id): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM users WHERE id_user = :id_user";
    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':id_user' => $id

    ));
    $result = $request->fetch();
    return $result;
}

// /////////////////  Fonction pour supprimer un utilisateur  ///////////////////////


function deleteUser(int $id): void
{
    $pdo = connexionBdd();
    $sql = "DELETE FROM users WHERE id_user = :id_user";
    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':id_user' => $id

    ));
}

// ////////////////////  Fonction pour modifier le role d'un utilisateur//////////////

function updateRole(string $role, int $id): void
{
    $pdo = connexionBdd();
    $sql = "UPDATE users SET role = :role WHERE id_user = :id_user";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':role' => $role,
        ':id_user' => $id

    ));
}


//////////////une fonction pour recupérer toutes les catégories//////////

function allCategories(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT * FROM categories";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}


////////////////// fonction pour récupérer tous les films/////////////////////

function allFilms(): array
{

    $pdo = connexionBdd();
    $sql = "SELECT films.* , categories.name AS genre
    FROM films
    LEFT JOIN categories ON films.category_id = categories.id_category";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;
}

// //////////////  Fonction pour récuperer un film qui a la même catégorie  /////////////////

function filmByCategory(int $id): array
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM films WHERE category_id = :id";
    $request = $pdo->prepare($sql);
    $request->execute([':id' => $id]);

    $result = $request->fetchAll();
    return $result;
}



//////////////  fonction pour afficher un film///////////////

function showFilm(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT films.* , categories.name AS genre
    FROM films 
    LEFT JOIN categories
    ON films.category_id = categories.id_category
    WHERE id_film = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetch();
    return $result;
}


///////////////////////////Fonction pour modifier le  film///////////
function updateFilm(int $idFilm, int $category, string $title, string $director, string $actors, string $ageLimit, string $duration, string $synopsis, string $dateSortie, string $image, float $price, int $stock) : void 

{
    $pdo = connexionBdd();
    $sql = "UPDATE films SET 
                    id_film = :id,
                    category_id= :category_id,
                    title = :title,
                    director = :director,
                    actors = :actors,
                    synopsis = :synospsis,
                    ageLimit = :ageLimit,
                    dateSortie = :date,
                    image = :image,
                    price = :price,
                    stock = :stock 
                    WHERE id_film = :id";

    $request = $pdo->prepare($sql);
    $request->execute(array (

        ':id' => $idFilm,
        ':category_id' => $category,
        ':title' => $title,
        ':director' => $director,
        ':actors' => $actors,
        ':ageLimit' => $ageLimit,
        ':duration' => $duration,
        ':synopsis' => $synopsis,
        ':date' => $dateSortie,
        ':image' => $image,
        ':price' => $price,
        ':stock' => $stock

    ));
}


///////////////////////////////////////////////// PANIER /////////////////////////////////

// calculerMontantTotal() pour calculer le montant total du panier en additionnant les prix de chaque film.
function calculerMontantTotal(array $tab): int
{
    $montant_total = 0;

    foreach ($tab as $key) {
        $montant_total += $key['price'] * $key['quantity'];
    }

    return $montant_total;
}









// //////////   fonction pour afficher une categorie  ////////////

function showCategory(int $id): mixed
{
    $pdo = connexionBdd();
    $sql = "SELECT * FROM categories WHERE id_category = :id ";
    $request = $pdo->prepare($sql);
    $request->execute(array(
        ':id' => $id
    ));

    $result = $request->fetch();
    return $result;
}


/////////////  Une fonction pour créer la table films /////////////

function createTableFilms()
{

    $pdo = connexionBdd();

    $sql = "CREATE TABLE IF NOT EXISTS films (
            id_film INT PRIMARY KEY AUTO_INCREMENT,
            category_id INT NOT NULL,
            title VARCHAR(100) NOT NULL,
            director VARCHAR(100) NOT NULL,
            actors VARCHAR(100) NOT NULL,
            ageLimit VARCHAR(5) NULL,
            duration TIME NOT NULL,
            synopsis TEXT NOT NULL,
            date DATE NOT NULL,
            image VARCHAR(255) NOT NULL,
            price FLOAT NOT NULL,
            stock INT NOT NULL

        )";

    $request = $pdo->exec($sql);
}

// createTableFilms();

//////// Une fonction pour créer la table categories //////////////

function createTableCategories()
{

    $pdo = connexionBdd();

    $sql = "CREATE TABLE IF NOT EXISTS categories (
            id_category INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            description TEXT NULL
        )";

    $request = $pdo->exec($sql);
}

// createTableCategories();



// ///////   Fonction pour ajouter une catégorie   /////////////

function addCategory(string $categoryName, string $description): void
{

    $pdo = connexionBdd();

    $sql = "INSERT INTO categories (name, description) VALUES (:name, :description)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':name' => $categoryName,
        ':description' => $description
    ));
}

////////  Fonction pour supprimer une categorie //////////


function deleteCategory(int $id): void
{
    $pdo = connexionBdd();

    // // Supprimer les films associés à la catégorie
    // $sql = "DELETE FROM films WHERE category_id = :id";
    // $request = $pdo->prepare($sql);
    // $request->execute([':id' => $id]);

    // Supprimer la catégorie
    $sql = "DELETE FROM categories WHERE id_category = :id";
    $request = $pdo->prepare($sql);
    $request->execute(array(':id' => $id));
}


// ///////////  Fonction pour ajouter un film  ////////////

function addFilm( int $category, string $title, string $director, string $actors, string $ageLimit, string $duration, string $synopsis, string $dateSortie, string $image, float $price, int $stock): void
{

    $pdo = connexionBdd();

    $sql = "INSERT INTO films (category_id, title, director, actors, ageLimit, duration, synopsis, date, image, price, stock) VALUES (:category_id, :title, :director, :actors, :ageLimit, :duration, :synopsis, :date, :image, :price, :stock)";

    $request = $pdo->prepare($sql);
    $request->execute(array(

        ':category_id' => $category,
        ':title' => $title,
        ':director' => $director,
        ':actors' => $actors,
        ':ageLimit' => $ageLimit,
        ':duration' => $duration,
        ':synopsis' => $synopsis,
        ':date' => $dateSortie,
        ':image' => $image,
        ':price' => $price,
        ':stock' => $stock
    ));
}

// //////////  Fonction pour supprimer un film/////////////

function deleteFilm(int $id): void
{
    $pdo = connexionBdd();

    $sql = "DELETE FROM films WHERE id_film = :id";
    $request = $pdo->prepare($sql);
    $request->execute([':id' => $id]);
}

// //////////////// fonction pour trier les films les plus recents  ////////////////////////

function filmByDate(){
    $pdo = connexionBdd();
    $sql = "SELECT * FROM films ORDER BY date DESC LIMIT 6";
    $request = $pdo->query($sql);
    $result = $request->fetchAll();
    return $result;

}

///////  Une fonction pour la création des clés étrangères /////

// $tableF : table où on va créer la clé étrangère
// $tableP : table à partir de laquelle on récupère la clé primaire
// $foreign : le nom de la clé étrangère
// $primary : le nom de la clé primaire

function foreignKey(string $tableF, string $foreign, string $tableP, string $primary)
{

    $pdo = connexionBdd();

    $sql = "ALTER TABLE $tableF ADD CONSTRAINT FOREIGN KEY ($foreign) REFERENCES $tableP ($primary)";

    $request = $pdo->exec($sql);
}

// Création de la clé étrangère dans la table films
// foreignKey('films', 'category_id', 'categories', 'id_category');




?>