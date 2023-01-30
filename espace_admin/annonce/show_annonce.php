<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

if(isset($_GET['action']) && $_GET['action'] === 'show') {
    header("Location: ../../show_annonce_detail.php?id_annonce=".$_GET["amp;id_annonce"]);
}

// Si l'admin a cliqué sur "supprimer"
if (isset($_GET['action']) && $_GET['action'] === 'delete') {

       $query = $bdd->prepare("DELETE FROM annonce WHERE id_annonce=:id_annonce");
    $query->bindValue(':id_annonce', $_GET['amp;id_annonce']);

    if ($query->execute() && $bdd->query("DELETE FROM photo WHERE id_photo=". $_GET['amp;id_photo']) !== false) {
        // Suppression de la photo de l'annonce
        unlink(UPLOAD_FOLDER . $_GET['amp;photo']);

        $confirmSupp = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>L\'annonce </strong>a bien été supprimée.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

# Message pour la modification en fonction de son état
if(isset($_GET['update_success'])) {
    if($_GET['update_success'] === 'true') {
        $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                                        <strong>Tout s\'est bien passé ! </strong>L\'annonce est bien modifiée.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>';
    } else {
        $confirmMessage = '<div class="alert alert-warning alert-dismissible fade show text-center mt-3" role="alert">
                                    <strong>Une erreur s\'est produite ! </strong>Veuillez réessayer la modification.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
    }
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

    <div class="row">
        <?= $confirmSupp ?? '' ?>
        <?= $confirmMessage ?? '' ?>
    </div>

    <div class="row">
        <div class="col-12 mx-auto">

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
                            <?php if(isset($annonces) && !empty($annonces)): ?>
                                <?php foreach($annonces as $annonce) : ?>
                                    <tr>
                                        <td class="text-center"><?= $annonce['id_annonce'] ?></td>
                                        <td class="text-center"><?= strlen($annonce['titre']) > 30 ? substr($annonce['titre'], 0, 30) : $annonce['titre'] ?></td>
                                        <td class="text-center"><?= strlen($annonce['desc_courte']) > 20 ? substr($annonce['desc_courte'], 0, 20) : $annonce['desc_courte'] ?></td>
                                        <td class="text-center"><?= strlen($annonce['desc_longue']) > 30 ? substr($annonce['desc_longue'], 0, 30) : $annonce['desc_longue'] ?></td>
                                        <td class="text-center"><?= $annonce['prix'] ?> €</td>
                                        <td class="text-center"><img src="<?= UPLOAD_URL . $annonce['photo'] ?>" style="object-fit: contain" width="220" height="120" alt="Une photo d'annonce"></td>
                                        <td class="text-center"><?= $annonce['pays'] ?></td>
                                        <td class="text-center"><?= $annonce['ville'] ?></td>
                                        <td class="text-center"><?= $annonce['adresse'] ?></td>
                                        <td class="text-center"><?= $annonce['cp'] ?></td>
                                        <td class="text-center"><?php $membre = findUser($annonce['id_user'], $bdd); echo $membre['pseudo'];  ?>
                                        </td>
                                        <td class="text-center"><?php $queryFindCategorie = $bdd->query("SELECT titre FROM categorie WHERE id_categorie=$annonce[id_categorie]");
                                            $categorie = $queryFindCategorie->fetch(PDO::FETCH_ASSOC); echo $categorie['titre']; ?>
                                        </td>
                                        <td class="text-center"><?= $annonce['created_at'] ?></td>
                                        <td class="text-center">
                                            <a href="?action=show&id_annonce=<?= $annonce['id_annonce'] ?>"
                                               class="text-success"
                                               title="Voir l'annonce"><i class="bi bi-eye-fill"></i></a>
                                            <a href="admin_form_annonce?action=update&id_annonce=<?= $annonce['id_annonce'] ?>"
                                               class="text-primary"
                                               title="Modifier une annonce"><i class="bi bi-pencil-fill"></i></a>
                                            <a href="?action=delete&id_annonce=<?= $annonce['id_annonce'] ?>&id_photo=<?= $annonce['id_photo'] ?>&photo=<?= $annonce['photo'] ?>"
                                               class="ms-2 text-danger"
                                               title="Supprimer une annonce"
                                               onclick="return confirm('Cette action entraînera la suppression définitive. Veuillez confirmer la suppression')"><i class="bi bi-x-square"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td class="col-md-8 mx-auto" colspan="14">
                                        <h5 class="text-warning text-center">Aucune annonce</h5>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('../include/_footer_admin.php') ?>
<script src="../js/scripts.js"></script>
<script src="../js/datatables-simple-demo.js"></script>

