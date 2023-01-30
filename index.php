<?php
require_once('include/_init.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$queryString = "SELECT * FROM annonce";

if($_GET) {

    //////////////////////////////////////////////////////////////
    if(! isset($_GET['sort']) && ! isset($_GET['search_query'])) {

        // On filtre $_GET des paires qui ont une valeur vide : c.-à-d. qu'on ne garde que les champs renseignés
        $filteredGet = array_filter($_GET);

        // Si au moins un champ n'est pas vide
        if(count($filteredGet)) {

            // Concaténation de la requête SQL
            $queryString .= " WHERE ";

            // Pour chaque paire clé/valeur, on construit la requête SQL
            foreach($filteredGet as $key => $value) {
                if($key !== 'prix') {
                    $queryString .= "$key=:$key";
                } else {
                    $queryString .= "$key BETWEEN 0 AND :$key";
                }

                // S'il y a plusieurs champs et que ce n'est pas le dernier du tableau $filteredGet
                if (count($filteredGet) >=2 && $key != array_key_last($filteredGet)) {
                    $queryString .= " AND ";
                }
            }

        }

        // On fait une requête préparée pour sécuriser la requête SQL
        $filterQuery = $bdd->prepare($queryString);

        // Pour chaque champ renseigné, on lie la valeur
        foreach($filteredGet as $key => $value) {
            $filterQuery->bindValue(":$key", htmlspecialchars($value) ?? '');
        }

        // Exécution de la requête
        $filterQuery->execute();

        // S'il y a au moins 1 résultat dans la requête SQL
        if($filterQuery->rowCount()) {
            // On récupère toutes les annonces
            $annonces = $filterQuery->fetchAll(PDO::FETCH_ASSOC);

            // On variabilise le total d'annonces
            $totalAnnonce = $filterQuery->rowCount();

        } // end if(rowCount())
    }

    ////////////////////////////////////////////////////////////
    if(isset($_GET['sort']) && ! isset($_GET['search_query'])) {

        extract($_GET);

        switch ($sort) {
            case 'prix_asc': $queryString .= " ORDER BY prix ASC";
                break;
            case 'prix_desc': $queryString .= " ORDER BY prix DESC";
                break;
            case 'date_asc': $queryString .= " ORDER by created_at ASC";
                break;
            case 'date_desc': $queryString .= " ORDER by created_at DESC";
                break;
            case 'vendeur': $queryString = "SELECT * FROM annonce WHERE id_user IN (SELECT id_user FROM user WHERE id_user IN (SELECT id_user_auteur FROM note GROUP BY id_user_auteur HAVING ROUND(AVG(note), 1) >= 4) );";
                break;
            default: $queryString .= '';
                break;
        }
        $query = $bdd->query($queryString);

        if ($query->rowCount()) {
            $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
            $totalAnnonce = $query->rowCount();
        } // end if(rowCount())
    } // end if(isset($sort))

    //////////////////////////////////////////////////////////////
    if(isset($_GET['search_query']) && !isset($_GET['sort'])) {
        extract($_GET);

        $queryString .= " WHERE titre LIKE :titre OR desc_courte LIKE :desc_c OR desc_longue LIKE :desc_l";

        // On fait une requête préparée pour sécuriser la requête SQL
        $searchQuery = $bdd->prepare($queryString);

        // On lie les valeurs
        $searchQuery->bindValue(":titre", "%".htmlspecialchars($search_query)."%");
        $searchQuery->bindValue(":desc_c", "%".htmlspecialchars($search_query)."%");
        $searchQuery->bindValue(":desc_l", "%".htmlspecialchars($search_query)."%");

        // Exécution de la requête
        $searchQuery->execute();

        // S'il y a au moins 1 résultat dans la requête SQL
        if($searchQuery->rowCount()) {
            // On récupère toutes les annonces
            $annonces = $searchQuery->fetchAll(PDO::FETCH_ASSOC);

            // On variabilise le total d'annonces
            $totalAnnonce = $searchQuery->rowCount();
        }
    } // end if(isset($search_query))

} // end if($_GET)
else {
    try {
        $query = $bdd->query($queryString);
    } catch (PDOException $exception) {
        echo $exception->getMessage();
        die(". <b style='display: block;'>Veuillez ajouter les tables à la BDD 'troc' pour continuer (importer fichier sql/troc.sql dans phpMyAdmin).</b>");
    }

    if ($query->rowCount()) {
        $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
        $totalAnnonce = $query->rowCount();
    } // end if(rowCount())
} // end else

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$catQuery = $bdd->query("SELECT * FROM categorie ORDER BY titre ASC");

if ($catQuery->rowCount()) {
    $categories = $catQuery->fetchAll(PDO::FETCH_ASSOC);
}

$villeQuery = $bdd->query("SELECT ville FROM annonce GROUP BY ville ORDER BY ville ASC");

if ($villeQuery->rowCount()) {
    $villes = $villeQuery->fetchAll(PDO::FETCH_ASSOC);
}

// Requête de jointure pour retrouver les vendeurs uniquement
$membreQuery = $bdd->query("SELECT u.id_user, u.pseudo FROM user u, annonce a WHERE a.id_user = u.id_user GROUP BY pseudo ORDER BY pseudo ASC");

if ($membreQuery->rowCount()) {
    $membres = $membreQuery->fetchAll(PDO::FETCH_ASSOC);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

require_once('include/_header.php');
?>


<div class="row d-flex justify-content-between mt-3">

    <!-- ///////////////////////////////////////// LEFT SIDE /////////////////////////////////////////////// -->
    <div class="col-2 ms-0 ps-0">
        <form id="filter" name="filter" action="?action=filter" method="get">
            <div class="row mb-2">
                <label for="id_categorie" class="form-label" id="id_categorie">Catégorie</label>
                <select name="id_categorie" id="id_categorie" class="form-select form-select-sm">
                    <option value="">Toutes les catégories</option>
                    <?php if (isset($categories) && !empty($categories)) : ?>
                        <?php foreach($categories as $categorie) : ?>
                            <option value="<?= $categorie['id_categorie'] ?>"><?= $categorie['titre'] ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
            <div class="row mb-2">
                <label for="ville" class="form-label" id="ville">Ville</label>
                <select name="ville" id="ville" class="form-select form-select-sm">
                    <option value="">Toutes les villes</option>
                    <?php if (isset($villes) && !empty($villes)) : ?>
                        <?php foreach($villes as $ville) : ?>
                            <option value="<?= $ville['ville'] ?>"><?= $ville['ville'] ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
            <div class="row mb-2">
                <label for="id_user" class="form-label" id="id_user">Membre</label>
                <select name="id_user" id="id_user" class="form-select form-select-sm">
                    <option value="">Tous les membres</option>
                    <?php if (isset($membres) && !empty($membres)) : ?>
                        <?php foreach($membres as $membre) : ?>
                            <option value="<?= $membre['id_user'] ?>"><?= $membre['pseudo'] ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                </select>
            </div>
            <div class="row mb-2">
                <label for="prix" class="form-label" id="prix">Prix</label>
                <input type="range" id="prix" name="prix" class="form-range" min="0" max="10000" step="50" value="0">
                <small class="fst-italic">Maximum 10 000€</small>
            </div>
            <button type="submit" class="btn btn-sm btn-info col-6 d-block mx-auto">Appliquer</button>
        </form>
        <div class="row mt-3">
            <div class="col-12 fst-italic text-center text-warning"><?= $totalAnnonce ?? '0' ?> résultats</div>
        </div>
    </div>

<!-- ///////////////////////////////////////// RIGHT SIDE /////////////////////////////////////////////// -->
    <div class="col-9 ms-auto">
        <div class="row">
            <div class="col-8 ms-0 ps-0">
                <form id="sort" name="sort" action="?action=sort" method="get">
                    <div class="input-group input-group-sm my-3">
                        <label class="input-group-text" for="sort" id="sort">Options de tri</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="prix_asc">Trier par prix croissant</option>
                            <option value="prix_desc">Trier par prix décroissant</option>
                            <option value="date_asc">Trier par date croissante</option>
                            <option value="date_desc">Trier par date décroissante</option>
                            <option value="vendeur">Trier par vendeur</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-info">Valider</button>
                    </div>
                </form>
            </div>
            <hr class="col-12">
        </div>

        <?php if (isset($annonces) && !empty($annonces)) : ?>
            <?php foreach($annonces as $annonce) : ?>
                <div class="row">
                    <a href="show_annonce_detail.php?id_annonce=<?= $annonce['id_annonce'] ?>" class="text-decoration-none">
                        <div class="card mb-3" style="border: none !important;">
                            <div class="row">
                                <div class="col-4 ms-0 ps-0">
                                    <img src="<?= UPLOAD_URL . $annonce['photo'] ?>" class="rounded-start" alt="Photo d'annonce manquante" width="250" height="150" style="object-fit: contain;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body my-0 py-0">
                                        <h5 class="card-title text-primary"><?= $annonce['titre'] ?></h5>
                                        <p class="card-text text-dark"><?= $annonce['desc_courte'] ?></p>
                                        <div class="row text-dark pt-5">
                                            <div class="col-6"><?php $membre = findUser($annonce['id_user'], $bdd); echo $membre['pseudo'];  ?> ⭐⭐⭐⭐⭐</div>
                                            <div class="col-6 text-end"><?= $annonce['prix'] ?> €</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="col-12">
                </div>
            <?php endforeach ?>

            <div class="text-center my-4">
                <a href="#" class="text-decoration-none">Voir plus</a>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="card mb-3" style="border: none !important;">
                    <div class="row">

                        <div class="col-md-8 mx-auto">
                            <div class="card-body my-0 py-0">
                                <h5 class="card-title text-warning text-center">Aucune annonce</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="col-12">
            </div>
            <div class="text-center my-4">
                <?php if (isConnect()): ?>
                    <a href="form_annonce.php" class="text-decoration-none">Publiez votre annonce</a>
                <?php else : ?>
                    <a href="connexion.php" class="text-decoration-none">Connectez-vous</a>
                <?php endif; ?>
            </div>
        <?php endif ?>
    </div>
</div>

<?php require_once('include/_footer.php') ?>