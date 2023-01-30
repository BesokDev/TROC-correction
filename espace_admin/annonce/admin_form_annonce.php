<?php

require_once('../../include/_init.php');

if( ! isAdminConnect()) {
    header('location: ../../index.php');
}

# TODO: encore probleme 'amp;' dans $_GET
# Parce que le nom du param id_annonce change en fonction de si on clique depuis la <table> HTML ou le <form> HTML
$annonce = isset($_GET['amp;id_annonce']) ? findAnnonce($_GET['amp;id_annonce'], $bdd) : findAnnonce($_GET['id_annonce'], $bdd);

if($_POST) {

    extract($_POST);

    if(isset($_GET['action']) && $_GET['action'] === 'update') {

        # Gestion de la photo si l'ancienne est changée
        if ($_FILES && !empty($_FILES['photo']['name'])) {

            # Tableau des types de fichier photo acceptés par le MimeType
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

            # Vérification du type de fichier par son MimeType
            if (in_array($_FILES['photo']['type'], $mimeTypesAllowed, true) && !empty($tmpFilePath)) {

                $newFilePath = UPLOAD_FOLDER . $newFilename;

                # Si le fichier est bien uploadé, on enregistre en BDD
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $queryPhoto = $bdd->query("UPDATE photo SET photo1='".$newFilename."' WHERE id_photo=$annonce[id_photo]");

                    # Suppression de l'ancien fichier de photo (voir dossier 'troc/uploads')
                    unlink(UPLOAD_FOLDER . $current_photo);
                }
            }
        }# end if($_FILES)

        $query = $bdd->prepare('UPDATE annonce SET titre=:titre, desc_courte=:desc_c, desc_longue=:desc_l, prix=:prix, photo=:photo, pays=:pays, ville=:ville, adresse=:adresse, cp=:cp, id_user=:id_user, id_categorie=:id_categorie, updated_at=:updated_at WHERE id_annonce=:id');

        $query->bindValue(':titre', $titre);
        $query->bindValue(':desc_c', $desc_courte);
        $query->bindValue(':desc_l', $desc_longue);
        $query->bindValue(':prix', $prix);
        $query->bindValue(':photo', $newFilename ?? $current_photo);
        $query->bindValue(':pays', $pays);
        $query->bindValue(':ville', $ville);
        $query->bindValue(':adresse', $adresse);
        $query->bindValue(':cp', $cp);
        $query->bindValue(':id_user', $id_user);
        $query->bindValue(':id_categorie', $categorie);
        $query->bindValue(':updated_at', date('Y/m/d H:i'));
        $query->bindValue(':id', $_GET['id_annonce']);


        if ($query->execute()) {
            header('Location: show_annonce.php?update_success=true');
        } else {
            header('Location: show_annonce.php?update_success=false');
        }
    }# end if(action=update)
} # end if($_POST)

# Récupération des catégories pour l'input <select>
$queryCat = $bdd->query("SELECT * FROM categorie;");

# Si il y a un résultat, on variabilise tout dans une variable $categories
if($queryCat->rowCount()) {
    $categories = $queryCat->fetchAll(PDO::FETCH_ASSOC);
}

require_once('../include/_header_admin.php');
?>

<link rel="stylesheet" href="../css/styles.css">

<div class="row d-flex justify-content-between mt-3">

    <div class="row">
        <?= $confirmSupp ?? '' ?>
    </div>

    <h1 class="text-center mx-auto mt-3">Modifier l'annonce <?= $annonce['titre'] ?? '' ?></h1>
    <div class="container-fluid col-md-10 px-4">

        <form action="?action=update&id_annonce=<?= $annonce['id_annonce'] ?>" method="post" enctype="multipart/form-data">

            <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">1 - Contenu de l'annonce</h3>

            <div class="row">
                <div class="col-6">
                    <label for="titre" class="form-label">Titre de l'annonce</label>
                    <input type="text" id="titre" name="titre" class="form-control" value="<?= $annonce['titre'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label for="categorie" class="form-label">Catégorie de l'annonce</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <?php if(isset($categories) & !empty($categories)) : ?>
                            <?php foreach($categories as $category) : ?>
                                <option value="<?= $category['id_categorie'] ?>" <?= $category['id_categorie'] === $annonce['id_categorie'] ? 'selected' : '' ?> ><?= ucfirst($category['titre']) . ' (' . $category['mots_clefs'] .')' ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-6">
                    <label for="desc_courte" class="form-label">Description courte de l'annonce</label>
                    <textarea name="desc_courte" id="desc_courte" rows="3" class="form-control"><?= $annonce['desc_courte'] ?? '' ?></textarea>
                </div>
                <div class="col-6">
                    <label for="desc_longue" class="form-label">Description longue de l'annonce</label>
                    <textarea name="desc_longue" id="desc_longue" rows="3" class="form-control"><?= $annonce['desc_longue'] ?? '' ?></textarea>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="text" id="prix" name="prix" class="form-control" value="<?= $annonce['prix'] ?? '' ?>">
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
                    <input type="text" id="pays" name="pays" class="form-control" value="<?= $annonce['pays'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label for="ville" class="form-label">Ville</label>
                    <input type="text" id="ville" name="ville" class="form-control" value="<?= $annonce['ville'] ?? '' ?>">
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-6">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" id="adresse" name="adresse" class="form-control" placeholder="Adresse figurant dans l'annonce" value="<?= $annonce['adresse'] ?? '' ?>">
                </div>
                <div class="col-6">
                    <label for="cp" class="form-label">Code Postal</label>
                    <input type="text" id="cp" name="cp" class="form-control" placeholder="Code Postal figurant dans l'annonce" value="<?= $annonce['cp'] ?? '' ?>">
                </div>
            </div>

            <input type="hidden" name="current_photo" value="<?= $annonce['photo'] ?>">
            <input type="hidden" name="id_photo" value="<?= $annonce['id_photo'] ?>">
            <input type="hidden" name="id_user" value="<?= $annonce['id_user'] ?>">

            <button type="submit" class="d-block mx-auto col-4 my-4 btn btn-success">Modifier</button>
        </form>
    </div>

</div>


<?php require_once('../include/_footer_admin.php') ?>

<script src="../js/scripts.js"></script>