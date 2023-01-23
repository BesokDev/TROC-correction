<?php
require_once('include/_init.php');

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce");

if ($query->rowCount()) {
    $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
}

$totalAnnonce = $query->rowCount();

require_once('include/_header.php');
?>

<!--<h1 class="text-center mx-auto mt-3">ACCUEIL</h1>-->

<div class="row d-flex justify-content-between mt-3">

    <!-- ///////////////////////////////////////// LEFT SIDE /////////////////////////////////////////////// -->
    <div class="col-2 ms-0 ps-0">
        <form id="filter" name="filter" action="?action=filter" method="get">
            <div class="row mb-2">
                <label for="categorie" class="form-label" id="categorie">Catégorie</label>
                <select name="categorie" id="categorie" class="form-select form-select-sm">
                    <option value="">Toutes les catégories</option>

                </select>
            </div>
            <div class="row mb-2">
                <label for="ville" class="form-label" id="ville">Ville</label>
                <select name="ville" id="ville" class="form-select form-select-sm">
                    <option value="">Toutes les villes</option>

                </select>
            </div>
            <div class="row mb-2">
                <label for="user" class="form-label" id="user">Membre</label>
                <select name="user" id="user" class="form-select form-select-sm">
                    <option value="">Tous les membres</option>

                </select>
            </div>
            <div class="row mb-2">
                <label for="price" class="form-label" id="price">Prix</label>
                <input type="range" id="price" name="price" class="form-range" min="0" max="10000" step="50" list="price">
                <small class="fst-italic">Maximum 10 000€</small>
            </div>
            <button type="submit" class="btn btn-sm btn-info col-6 d-block mx-auto">Appliquer</button>
        </form>
        <div class="row mt-3">
            <div class="col-12 fst-italic text-center text-warning"><?= $totalAnnonce ?> articles</div>
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
                            <option value="">Trier par prix croissant</option>
                            <option value="">Trier par prix décroissant</option>
                            <option value="">Trier par date croissante</option>
                            <option value="">Trier par date décroissante</option>
                            <option value="">Trier par vendeur</option>
                        </select>
                    </div>
                </form>
            </div>
            <hr class="col-12">
        </div>

        <?php if(isset($annonces) && !empty($annonces)):  ?>
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
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="text-center my-4">
            <a href="#" class="text-decoration-none">Voir plus</a>
        </div>
    </div>
</div>

<?php require_once('include/_footer.php') ?>