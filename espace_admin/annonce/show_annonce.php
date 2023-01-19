<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

if(isset($_GET['action']) && $_GET['action'] === 'show') {
    dd("faire la redirection vers la page show annonce");
}

// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $query = $bdd->prepare("DELETE FROM annonce WHERE id_annonce=:id_annonce");
    $query->bindValue(':id_annonce', $_GET['amp;id_annonce']);

    if ($query->execute()) {
        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>L\'annonce </strong>a bien été supprimé.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'update') {
    dd('il faut faire l\'update');
}

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce");

if ($query->rowCount()) {
    $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
}

require_once('../include/_header_admin.php');

?>

<link rel="stylesheet" href="../css/styles.css">

<div class="container-fluid px-4">

    <h1 class="mt-4">Annonces</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index_admin.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Annonces</li>
    </ol>

    <?php
        if( isset($_GET['action']) && $_GET['action'] === 'update') {
            include '_form_annonce.php';
        }
    ?>

    <div class="row">
        <?= $confirmSupp ?? '' ?>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Toutes les annonces en ligne
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Titre</th>
                    <th class="text-center">Description courte</th>
                    <th class="text-center">Description longue</th>
                    <th class="text-center">Prix</th>
                    <th class="text-center">Photo</th>
                    <th class="text-center">Pays</th>
                    <th class="text-center">Ville</th>
                    <th class="text-center">Adresse</th>
                    <th class="text-center">CP</th>
                    <th class="text-center">Membre</th>
                    <th class="text-center">Catégorie</th>
                    <th class="text-center">Créée le</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($annonces as $annonce) : ?>
                <?php #TODO select photo ?>
                    <tr>
                        <td class="text-center"><?= $annonce['id_annonce'] ?></td>
                        <td class="text-center"><?= $annonce['titre'] ?></td>
                        <td class="text-center"><?= $annonce['desc_courte'] ?></td>
                        <td class="text-center"><?= $annonce['desc_longue'] ?></td>
                        <td class="text-center"><?= $annonce['prix'] ?></td>
                        <td class="text-center"><img src="<?= UPLOAD_FOLDER . $annonce['photo'] ?>" style="object-fit: contain" width="220" height="120" alt="Une photo d'annonce"></td>
                        <td class="text-center"><?= $annonce['pays'] ?></td>
                        <td class="text-center"><?= $annonce['ville'] ?></td>
                        <td class="text-center"><?= $annonce['adresse'] ?></td>
                        <td class="text-center"><?= $annonce['cp'] ?></td>
                        <td class="text-center"><?php $queryFindUser = $bdd->query("SELECT prenom FROM user WHERE id_user=$annonce[id_user]");
                            $queryFindUser->execute(); $membre = $queryFindUser->fetch(PDO::FETCH_ASSOC); echo $membre['prenom'];  ?></td>
                        <td class="text-center"><?php $queryFindCategorie = $bdd->query("SELECT titre FROM categorie WHERE id_categorie=$annonce[id_categorie]");
                            $queryFindCategorie->execute(); $categorie = $queryFindCategorie->fetch(PDO::FETCH_ASSOC); echo $categorie['titre']; ?></td>
                        <td class="text-center"><?= $annonce['created_at'] ?></td>
                        <td class="text-center">
                            <a href="?action=show&id_annonce=<?= $annonce['id_annonce'] ?>"
                               class="text-success"
                               title="Modifier une annonce"><i class="bi bi-eye-fill"></i></a>
                            <!-- TODO: Corriger le problème "amp;" dans l'URL  -->
                            <a href="?action=update&id_annonce=<?= $annonce['id_annonce'] ?>"
                               class="text-primary"
                               title="Modifier une annonce"><i class="bi bi-pencil-fill"></i></a>
                            <a href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>"
                               class="ms-2 text-danger"
                               title="Supprimer une annonce"
                               onclick="return confirm('Cette action entraînera la suppression définitive. Veuillez confirmer la suppression')"><i class="bi bi-x-square"></i></a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require_once('../include/_footer_admin.php') ?>
<script src="../js/scripts.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

