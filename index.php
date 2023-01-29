<?php
require_once('include/_init.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_GET) {
    extract($_GET);

    $queryString = "SELECT * FROM annonce";

    $filteredGet = array_filter($_GET);

//    dd($filteredGet);
    if(count($filteredGet)) {
//        $queryString = $bdd->prepare("SELECT * FROM annonce WHERE id_categorie=:categorie OR ville=:ville OR prix BETWEEN 0 AND :prix OR id_user=:membre");

            $queryString .= " WHERE ";
//            $keynames = array_keys($filteredGet); // make array of key names from $filteredGet
            foreach($filteredGet as $key => $value)
            {
//    dd($filteredGet);

                $queryString .= "$key='$value'";  // $filteredGet keyname = $filteredGet['keyname'] value

                if (count($filteredGet) >=2 && $key != array_key_last($filteredGet)) { // more than one search filter, and not the last
                    $queryString .= " AND ";
                }
            }

    }

    if(isset($_GET['sort'])) {
        switch ($sort) {
            case 'prix_asc': $queryString .= " ORDER BY prix";
                break;
            case 'prix_desc': $queryString .= " ORDER BY prix DESC";
                break;
            case 'date_asc': $queryString .= " ORDER by created_at";
                break;
            case 'date_desc': $queryString .= " ORDER by created_at DESC";
                break;
            default: $queryString .= '';
                break;
        }
    }

    $filterQuery = $bdd->prepare($queryString);

    $filterQuery->bindValue(':categorie', $id_categorie ?? '');
    $filterQuery->bindValue(':ville', $ville ?? '');
    $filterQuery->bindValue(':membre', $id_user ?? '');
    $filterQuery->bindValue(':prix', $prix ?? '');

    $filterQuery->execute();
    if($filterQuery->rowCount()) {

        $annonces = $filterQuery->fetchAll(PDO::FETCH_ASSOC);
        $totalAnnonce = $filterQuery->rowCount();
    } // end if(rowCount())

} else {
    //$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL"); # à utiliser pour le soft delete
    $query = $bdd->query("SELECT * FROM annonce");

    if ($query->rowCount()) {
        $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
        $totalAnnonce = $query->rowCount();
    } // end if(rowCount())

} // end if/else($_GET)

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$catQuery = $bdd->query("SELECT * FROM categorie");

if ($catQuery->rowCount()) {
    $categories = $catQuery->fetchAll(PDO::FETCH_ASSOC);
}

$villeQuery = $bdd->query("SELECT ville FROM annonce GROUP BY ville");

if ($villeQuery->rowCount()) {
    $villes = $villeQuery->fetchAll(PDO::FETCH_ASSOC);
}

$membreQuery = $bdd->query("SELECT u.id_user, u.pseudo FROM user u, annonce a WHERE a.id_user = u.id_user GROUP BY pseudo");

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
                            <option value="membre">Trier par vendeur</option>
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
                                            <div class="col-6"><?php $membre = findUser($annonce['id_user'], $bdd); echo $membre['prenom'];  ?> ⭐⭐⭐⭐⭐</div>
                                            <div class="col-6 text-end"><?= $annonce['prix'] ?> €</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <hr class="col-12">
                </div>
                <div class="text-center my-4">
                    <a href="#" class="text-decoration-none">Voir plus</a>
                </div>
            <?php endforeach ?>
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
        <?php endif ?>
        <div class="text-center my-4">
            <?php if (isConnect()) : ?>
                <a href="form_annonce.php" class="text-decoration-none">Publiez votre annonce</a>
            <?php else : ?>
                <a href="connexion.php" class="text-decoration-none">Connectez-vous</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once('include/_footer.php') ?>