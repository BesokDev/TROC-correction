<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}


// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $query = $bdd->prepare("DELETE FROM note WHERE id_note=:id_note");
    $query->bindValue(':id_note', $_GET['amp;id_note']);

    if ($query->execute()) {
        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>La note </strong>a bien été supprimée.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

$query = $bdd->query("SELECT * FROM note");

if ($query->rowCount()) {
    $notes = $query->fetchAll(PDO::FETCH_ASSOC);
}

require_once('../include/_header_admin.php');

?>

<link rel="stylesheet" href="../css/styles.css">

<div class="container-fluid px-4">

    <h1 class="mt-4">Notes</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index_admin.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Notes</li>
    </ol>

    <?php
    if( isset($_GET['action']) && $_GET['action'] === 'update') {
        dd('update note');
    }
    ?>

    <div class="row">
        <?= $confirmSupp ?? '' ?>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Toutes les notes attribuées
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                <tr class=" d-flex justify-content-center">
                    <th class="text-center">#</th>
                    <th class="text-center">Membre Notant</th>
                    <th class="text-center">Membre Noté</th>
                    <th class="text-center">Note</th>
                    <th class="text-center">Avis</th>
                    <th class="text-center">Enregistré le :</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($notes as $note) : ?>
                    <?php
                    $membreNotant = findUser($note['id_user_notant'], $bdd);
                    $membreNote = findUser($note['id_user_auteur'], $bdd);
                    ?>

                    <tr>
                        <td class="text-center d-flex justify-content-center"><?= $note['id_note'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $membreNotant['id_user'] . ' - ' . $membreNotant['email'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $membreNote['id_user'] . ' - ' . $membreNote['email'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $note['note'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $note['avis'] ?></td>
                        <td class="text-center d-flex justify-content-center"><?= $note['created_at'] ?></td>
                        <td class="text-center d-flex justify-content-center">
                            <div class="d-flex justify-content-center">
                                <!-- TODO: Corriger le problème "amp;" dans l'URL  -->
                                <a href="?action=update&id_note=<?= $note['id_note'] ?>"
                                   class="text-primary"
                                   title="Modifier un commentaire"><i class="bi bi-pencil-fill"></i></a>

                                <a href="?action=delete&id_note=<?= $note['id_note'] ?>"
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

