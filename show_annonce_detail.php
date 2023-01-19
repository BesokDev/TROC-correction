<?php
require_once('include/_init.php');

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce WHERE id_annonce=$_GET[id_annonce]");

if ($query->rowCount()) {
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    $queryFindUser = $bdd->query("SELECT prenom FROM user WHERE id_user=$annonce[id_user]");
    $membre = $queryFindUser->fetch(PDO::FETCH_ASSOC);
}

require_once('include/_header.php');
?>

<!--<h1 class="text-center mx-auto mt-3">ACCUEIL</h1>-->
<div class="row">
    <div class="col-11 mx-auto">

        <div class="row d-flex justify-content-around mt-3">
            <h3 class="col-6 mx-0 px-0"><?= $annonce['titre'] ?></h3>
            <div class="col-6 text-end mx-0 px-0">
                <a class="btn btn-success btn-sm">Contacter <?= $membre['prenom'] ?></a>
            </div>
            <hr class="mt-2 col-12">
        </div>

        <div class="row d-flex justify-content-around mt-2">
            <div class="col-6 mx-0 px-0"><img src="<?= UPLOAD_FOLDER . $annonce['photo'] ?>" alt="" width="500" height="330" style="object-fit: cover"></div>
            <div class="col-6">
                <h6>Description</h6>
                <p><?= $annonce['desc_longue'] ?></p>
            </div>
        </div>

        <div class="row d-flex justify-content-around mt-4">
            <div class="col-3">Publié le : <?= $annonce['created_at'] ?></div>
            <div class="col-2"><?= $membre['prenom'] ?></div>
            <div class="col-2"><?= $annonce['prix'] ?> €</div>
            <div class="col-5">Adresse : <?= $annonce['adresse'] . ', ' . strtoupper($annonce['cp']) . ' ' . ucfirst($annonce['ville']) ?></div>
        </div>

    </div>
</div>


<?php require_once('include/_footer.php') ?>