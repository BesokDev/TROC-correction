<?php

require_once('../../include/_init.php');

// Si l'admin a cliqué sur "modifier" (dans "show_categorie.php")
if ($_GET['action'] === 'update') {
    $query= $bdd->prepare("SELECT * FROM categorie WHERE id_categorie=:id_categorie");
    $query->bindValue(':id_categorie', $_GET['amp;id_categorie']);

    $query->execute();

    if($query->rowCount()) {
        $categorie = $query->fetch(PDO::FETCH_ASSOC);
    }
}

// Si le formulaire a été soumis
if($_POST){

    extract($_POST);

    if(isset($_GET['action']) && $_GET['action'] === 'submit_create') {
        $data = $bdd->prepare("INSERT INTO categorie VALUES (NULL, :titre, :mots_clefs)");

        $confirm = "<span class='d-block col-md-6 mx-auto mb-3 bg-success text-center text-white p-4 rounded'>La catégorie a bien été ajouté</span>";
    }

    # TODO: Corriger le problème "amp;" dans $_GET => ici aussi
    if(isset($_GET['action']) && $_GET['action'] === 'submit_update' && isset($_GET['id_categorie'])) {
        $data = $bdd->prepare("UPDATE categorie SET titre = :titre, mots_clefs = :mots_clefs WHERE id_categorie = :id_categorie");

        $data->bindValue(":id_categorie", $_GET["id_categorie"], PDO::PARAM_INT);
        $confirm = "<p class='col-md-5 mx-auto bg-success text-center text-white p-4 rounded'>La catégorie a bien été modifié</p>";
    }

    $data->bindValue(':titre', $titre);
    $data->bindValue(':mots_clefs', $mots_clefs);

    $data->execute();

    header('location: show_categorie.php');
}
?>

<div class="container-fluid px-4">

    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-keyboard"></i>
            <?= $_GET['action'] === 'create' ? 'Ajouter une nouvelle' : 'Modifier la'?>  catégorie
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4"><?= $_GET['action'] === 'create' ? 'Nouvelle catégorie' : "Modifier $categorie[titre]" ?></h3></div>
                        <div class="card-body">
                            <!-- TODO: Corriger le problème "amp;" dans $_GET  -->
                            <form action="_form_categorie.php?action=<?= isset($_GET['action']) && $_GET['action'] === 'create' ? 'submit_create' : 'submit_update&id_categorie='. $_GET['amp;id_categorie'] ?>" method="post" novalidate>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input type="text" class="form-control" id="titre" name="titre" placeholder="Nom de la nouvelle catégorie" value="<?= $categorie['titre'] ?? '' ?>" />
                                            <label for="titre">Nom de la nouvelle catégorie</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="mots_clefs" name="mots_clefs" placeholder="Mots-clés pour la catégorie"><?= $categorie['mots_clefs'] ?? '' ?></textarea>
                                            <label for="mots_clefs">Mots-clés pour la catégorie</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 mb-0">
                                    <div class="d-grid">
                                        <input type="submit" class="btn btn-primary btn-block" value="Publier" />
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small"><a class=" text-danger" href="show_categorie.php">Annuler</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>