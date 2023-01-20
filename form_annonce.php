<?php
require_once('include/_init.php');


$query = $bdd->query("SELECT * FROM categorie;");

if($query->rowCount()) {
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
}

require_once('include/_header.php');
?>


<div class="row d-flex justify-content-between mt-3">
    <h1 class="text-center mx-auto mt-3">Publier une annonce</h1>

    <div class="container-fluid col-md-10 px-4">

        <form action="?action=create" method="post" enctype="multipart/form-data">

            <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">1 - Contenu de l'annonce</h3>
            <div class="row">
                <small class="text-danger fst-italic text-decoration-underline mb-2">* champs obligatoires</small>
            </div>

            <div class="row">
                <div class="col-6">
                    <label for="titre" class="form-label">Titre de l'annonce</label>
                    <input type="text" id="titre" name="titre" class="form-control">
                </div>
                <div class="col-6">
                    <label for="categorie" class="form-label">Catégorie de l'annonce</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <?php if(isset($categories) & !empty($categories)) : ?>
                        <?php foreach($categories as $categorie) : ?>
                                <option value="<?= strtolower($categorie['titre']) ?>"><?= ucfirst($categorie['titre']) . ' (' . $categorie['mots_clefs'] .')' ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <label for="desc_courte" class="form-label">Description courte de l'annonce</label>
                    <textarea name="desc_courte" id="desc_courte" rows="3" class="form-control"></textarea>
                </div>
                <div class="col-6">
                    <label for="desc_longue" class="form-label">Description longue de l'annonce</label>
                    <textarea name="desc_longue" id="desc_longue" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="text" id="prix" name="prix" class="form-control">
                </div>
            </div>
            <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">2 - Adresse</h3>

            <div class="row mt-4">
                <div class="col-6">
                    <label for="pays" class="form-label">Pays</label>
                    <input type="text" id="pays" name="pays" class="form-control">
                </div>
                <div class="col-6">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" id="ville" name="ville" class="form-control">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-6">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" id="adresse" name="adresse" class="form-control" placeholder="Adresse figurant dans l'annonce">
                </div>
                <div class="col-6">
                    <label for="cp" class="form-label">Code Postal</label>
                    <input type="text" id="cp" name="cp" class="form-control" placeholder="Code Postal figurant dans l'annonce">
                </div>
            </div>

            <button type="submit" class="d-block mx-auto mt-3 btn btn-success col-3">Publier</button>
        </form>
    </div>

    </div>

<?php require_once('include/_footer.php') ?>