<?php
require_once('include/_init.php');

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce WHERE id_annonce=$_GET[id_annonce]");

if ($query->rowCount()) {
    // Récupération de l'annonce
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    // Récupération du nom du membre de l'annonce
    $queryFindUser = $bdd->query("SELECT prenom FROM user WHERE id_user=$annonce[id_user]");
    $membre = $queryFindUser->fetch(PDO::FETCH_ASSOC);

    // Récupération des autres annonces postées par ce même membre
    $queryOtherAnnonce = $bdd->query("SELECT * FROM annonce WHERE id_user=$annonce[id_user]");
    $otherAnnonce = $queryOtherAnnonce->fetchAll(PDO::FETCH_ASSOC);
}

require_once('include/_header.php');
?>

<!--<h1 class="text-center mx-auto mt-3">ACCUEIL</h1>-->
<div class="row">
    <div class="col-11 mx-auto">

        <div class="row d-flex justify-content-around mt-3">
            <h3 class="col-6 mx-0 px-0"><?= ucfirst($annonce['titre']) ?></h3>
            <div class="col-6 text-end mx-0 px-0">
                <a class="btn btn-success btn-sm">Contacter <?= $membre['prenom'] ?></a>
            </div>
            <hr class="mt-2 col-12 bg-warning">
        </div>

        <div class="row d-flex justify-content-around mt-2">
            <div class="col-6 mx-0 px-0"><img src="<?= UPLOAD_FOLDER . $annonce['photo'] ?>" alt="" width="500" height="330" style="object-fit: cover"></div>
            <div class="col-6">
                <h6>Description</h6>
                <p><?= $annonce['desc_longue'] ?></p>
            </div>
        </div>

        <div class="row d-flex justify-content-around mt-5">
            <div class="col-4 text-center"><i class="bi bi-calendar-check h5 text-warning"></i> Publiée le : <?= $annonce['created_at'] ?></div>
            <div class="col-3 text-center"><i class="bi bi-person-fill h5 text-warning"> </i><a href="" class="text-decoration-none"><?= ucfirst($membre['prenom']) ?></a> ⭐⭐⭐⭐⭐</div>
            <div class="col-1 text-center"><i class="bi bi-currency-euro h5 text-warning"> </i> <?= $annonce['prix'] ?> €</div>
            <div class="col-4 text-center"><i class="bi bi-geo-alt-fill h5 text-warning"> </i> <?= $annonce['adresse'] . ', ' . strtoupper($annonce['cp']) . ' ' . ucfirst($annonce['ville']) ?></div>
        </div>

        <div class="row mt-3 mx-0 px-0">
            <div class="col-12 mx-0 px-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d90512.33801933138!2d0.4882839500000001!3d44.85189989999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12aad07c6048f55f%3A0xd23474adfc552221!2s24100%20Bergerac!5e0!3m2!1sfr!2sfr!4v1674146539843!5m2!1sfr!2sfr"
                        height="250"
                        style="border:0; width: 100%;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="row mt-4">
            <h3 class="mx-0 px-0">Autres Annonces</h3>
            <hr class="col-12 bg-warning">
            <div class="col-12">

            </div>
        </div>

        <div class="row mt-3 d-flex justify-content-around">
            <div class="col-6 mx-0 px-0"><a href="" class="text-decoration-none">Déposer un commentaire ou une note</a></div>
            <div class="col-6 mx-0 px-0 text-end"><a href="index.php" class="text-decoration-none">Revenir vers les annonces</a></div>
        </div>

    </div>
</div>


<?php require_once('include/_footer.php') ?>