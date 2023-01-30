<?php
require_once('include/_init.php');

if(!isConnect()) {
    header("Location: connexion.php");
}

if($_POST) {

    extract($_POST);

    if(isset($_GET['action']) && $_GET['action'] === 'create') {

        if($_FILES) {

            $mimeTypesAllowed = ['image/jpeg', 'image/png'];

            # Séparation du nom et de l'extension du fichier
            $filename = pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME);

            # Variabilisation de l'extension du fichier
            $extension = '.' . pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);

            # Slug du nom du fichier
            $slugFilename = slugify($filename);

            # Reconstruction du nom de fichier avec un id unique
            $newFilename = $slugFilename . '_' . uniqid() . $extension;

            $tmpFilePath = $_FILES['photo']['tmp_name'];
            # Vérification du type de fichier par son Mime Type
            if(in_array($_FILES['photo']['type'], $mimeTypesAllowed, true) && !empty($tmpFilePath)) {

                $newFilePath = UPLOAD_FOLDER . $newFilename;

                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $queryPhoto = $bdd->query("INSERT INTO photo VALUES (NULL, '".$newFilename."', NULL, NULL, NULL, NULL)");

                    $idPhoto = $bdd->lastInsertId();

                    $queryAnnonce = $bdd->prepare("INSERT INTO annonce VALUES (NULL, :titre, :desc_courte, :desc_longue, :prix, :photo, :pays, :ville, :adresse, :cp, :id_user, :id_photo, :id_categorie, :created_at, NULL, NULL)");

                    $queryAnnonce->bindValue(':titre', $titre);
                    $queryAnnonce->bindValue(':desc_courte', $desc_courte);
                    $queryAnnonce->bindValue(':desc_longue', $desc_longue);
                    $queryAnnonce->bindValue(':prix', (int)$prix);
                    $queryAnnonce->bindValue(':photo', $newFilename);
                    $queryAnnonce->bindValue(':pays', $pays);
                    $queryAnnonce->bindValue(':ville', $ville);
                    $queryAnnonce->bindValue(':adresse', $adresse);
                    $queryAnnonce->bindValue(':cp', $cp);
                    $queryAnnonce->bindValue(':id_user', $_SESSION['user']['id_user']);
                    $queryAnnonce->bindValue(':id_photo', $idPhoto);
                    $queryAnnonce->bindValue(':id_categorie',$categorie);
                    $queryAnnonce->bindValue(':created_at', date('Y/m/d'));

                    if ($queryAnnonce->execute()) {

                        $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                        <strong>Tout s\'est bien passé ! </strong>Votre annonce est bien enregistrée.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                    }

                }
            }
        } # end if($_FILES)
    }# end if(create)
} # end if($_POST)

$query = $bdd->query("SELECT * FROM categorie ORDER BY titre ASC;");

if($query->rowCount()) {
    $categories = $query->fetchAll(PDO::FETCH_ASSOC);
}

require_once('include/_header.php');
?>


<div class="row d-flex justify-content-between mt-3">
    <h1 class="text-center mx-auto mt-3">Publier une annonce</h1>

    <div class="row">
        <?= $confirmMessage ?? '' ?>
    </div>

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
                        <?php foreach($categories as $category) : ?>
                                <option value="<?= $category['id_categorie'] ?>"><?= ucfirst($category['titre']) . ' (' . $category['mots_clefs'] .')' ?></option>
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
            <div class="row mt-4">
                <div class="col-12">
                    <!-- Si "multiple" et pour avoir tous les fichiers dans $_FILES, le 'name' doit comporter des crochets : ex.: photo[] -->
                    <input type="file" name="photo" id="photo" class="form-control" accept=".jpg, .jpeg, .png">
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

            <button type="submit" class="d-block mx-auto col-4 my-4 btn btn-success">Publier</button>
        </form>
    </div>

</div>

<?php require_once('include/_footer.php') ?>