<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

require_once('../include/_header_admin.php');

// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $query = $bdd->prepare("DELETE FROM commentaire WHERE id_commentaire=:id_commentaire");
    $query->bindValue(':id_commentaire', $_GET['amp;id_commentaire']);

    if ($query->execute()) {
        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>Le commentaire </strong>a bien été supprimé.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

$query = $bdd->query("SELECT * FROM commentaire");

if ($query->rowCount()) {
    $commentaires = $query->fetchAll(PDO::FETCH_ASSOC);
}

?>

<link rel="stylesheet" href="../css/styles.css">

<div class="container-fluid px-4">

    <h1 class="mt-4">Catégories</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index_admin.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Catégories</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <a href="?action=create" class="btn btn-primary col-3"><i class="bi bi-bookmark-plus"></i> Créer une catégorie</a>
            </div>
        </div>
    </div>

    <?php
    if( isset($_GET['action']) && $_GET['action'] === 'update') {
        dd('update commentaire');
    }
    ?>

    <div class="row">
        <?= $confirmSupp ?? '' ?>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tous les commentaires en ligne
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                <tr class=" d-flex justify-content-center">
                    <th class="text-center">#</th>
                    <th class="text-center">Membre</th>
                    <th class="text-center">Annonce</th>
                    <th class="text-center">Commentaire</th>
                    <th class="text-center">Enregistré le :</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($commentaires as $commentaire) : ?>
                <?php
                    $membre = findUser($commentaire['id_user'], $bdd);
                    $annonce = findAnnonce($commentaire['id_annonce'], $bdd);
                ?>

                    <tr>
                        <td class="text-center d-flex justify-content-center"><?= $commentaire['id_commentaire'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $membre['id_user'] . ' - ' . $membre['email'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $annonce['id_annonce'] . ' - ' . $annonce['titre'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $commentaire['commentaire'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $commentaire['created_at'] ?></td>
                        <td class="text-center d-flex justify-content-center">
                            <div class="d-flex justify-content-center">
                                <!-- TODO: Corriger le problème "amp;" dans l'URL  -->
                                <a href="?action=update&id_commentaire=<?= $commentaire['id_commentaire'] ?>"
                                   class="text-primary"
                                   title="Modifier un commentaire"><i class="bi bi-pencil-fill"></i></a>

                                <a href="?action=delete&id_commentaire=<?= $commentaire['id_commentaire'] ?>"
                                   class="ms-2 text-danger"
                                   title="Supprimer un commentaire"
                                   onclick="return confirm('Cette action entraînera la suppression définitive. Veuillez confirmer la suppression')"><i class="bi bi-x-square"></i></a>

                            </div>
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

