<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

require_once('../include/_header_admin.php');

// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $query = $bdd->prepare("DELETE FROM categorie WHERE id_categorie=:id_categorie");
    $query->bindValue(':id_categorie', $_GET['amp;id_categorie']);

    if ($query->execute()) {
        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>La catégorie </strong>a bien été supprimé.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

$query = $bdd->query("SELECT * FROM categorie");

if ($query->rowCount()) {
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
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
            if(isset($_GET['action']) && $_GET['action'] === 'create') {
                include '_form_categorie.php';
            }

            if( isset($_GET['action']) && $_GET['action'] === 'update') {
                include '_form_categorie.php';
            }
        ?>

        <div class="row">
            <?= $confirmSupp ?? '' ?>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Toutes les catégories en ligne
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Titre</th>
                        <th class="text-center">Mots-clés</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($categories) && !empty($categories)): ?>
                            <?php foreach($categories as $categorie) : ?>
                                <tr>
                                    <td class="text-center"><?= $categorie['id_categorie'] ?></td>
                                    <td class="text-center"><?= $categorie['titre'] ?></td>
                                    <td class="text-center"><?= $categorie['mots_clefs'] ?></td>
                                    <td class="text-center">
                                        <!-- TODO: Corriger le problème "amp;" dans l'URL  -->
                                        <a href="?action=update&id_categorie=<?= $categorie['id_categorie'] ?>"
                                           class="text-primary"
                                           title="Modifier une catégorie"><i class="bi bi-pencil-fill"></i></a>

                                        <a href="?action=delete&id_categorie=<?= $categorie['id_categorie'] ?>"
                                           class="ms-2 text-danger"
                                           title="Supprimer une catégorie"
                                           onclick="return confirm('Cette action entraînera la suppression définitive. Veuillez confirmer la suppression')"><i class="bi bi-x-square"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else : ?>
                            <tr>
                                <td class="col-md-8 mx-auto" colspan="14">
                                    <h5 class="text-warning text-center">Aucune catégorie</h5>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<?php require_once('../include/_footer_admin.php') ?>
<script src="../js/scripts.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

