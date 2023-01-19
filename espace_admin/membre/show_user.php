<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $query = $bdd->prepare("DELETE FROM user WHERE id_user=:id_user");
    $query->bindValue(':id_user', $_GET['amp;id_user']);

    if ($query->execute()) {
        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>Le membre </strong>a bien été supprimé.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

//$query = $bdd->query("SELECT * FROM user WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM user");

if ($query->rowCount()) {
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
}

require_once('../include/_header_admin.php');

?>

<link rel="stylesheet" href="../css/styles.css">

<div class="container-fluid px-4">

    <h1 class="mt-4">Membres</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="../index_admin.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Membre</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <a href="?action=create" class="btn btn-primary col-3"><i class="bi bi-bookmark-plus"></i> Créer un membre</a>
            </div>
        </div>
    </div>

    <?php
        if(isset($_GET['action']) && $_GET['action'] === 'create') {
            include '_form_user.php';
        }

        if( isset($_GET['action']) && $_GET['action'] === 'update') {
            include '_form_user.php';
        }
    ?>

    <div class="row">
        <?= $confirmSupp ?? '' ?>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tous les membres inscrits
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Pseudo</th>
                    <th class="text-center">Prénom</th>
                    <th class="text-center">Nom</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Téléphone</th>
                    <th class="text-center">Civilité</th>
                    <th class="text-center">Statut</th>
                    <th class="text-center">Enregistré le</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user) : ?>
                        <tr>
                            <td class="text-center"><?= $user['id_user'] ?></td>
                            <td class="text-center"><?= $user['pseudo'] ?></td>
                            <td class="text-center"><?= $user['prenom'] ?></td>
                            <td class="text-center"><?= $user['nom'] ?></td>
                            <td class="text-center"><?= $user['email'] ?></td>
                            <td class="text-center"><?= $user['telephone'] ?></td>
                            <td class="text-center"><?= $user['civilite'] ?></td>
                            <td class="text-center"><?= $user['statut'] ?></td>
                            <td class="text-center"><?= $user['created_at'] ?></td>
                            <td class="text-center">
                                <!-- TODO: Corriger le problème "amp;" dans l'URL  -->
                                <a href="?action=update&id_user=<?= $user['id_user'] ?>"
                                   class="text-primary"
                                   title="Modifier un membre"><i class="bi bi-pencil-fill"></i></a>

                                <a href="?action=delete&id_user=<?= $user['id_user'] ?>"
                                   class="ms-2 text-danger"
                                   title="Supprimer un membre"
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

