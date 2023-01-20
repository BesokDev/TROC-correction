<?php
require_once('include/_init.php');

// Gestion de la suppression d'un commentaire par son auteur'
if(isset($_GET['action']) && $_GET['action'] === 'delete_commentaire') {

    $query = $bdd->prepare("DELETE FROM commentaire WHERE id_commentaire=:id");
    $query->bindValue('id', $_GET['id_commentaire']);

    if($query->execute()) {
        $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <strong>Votre commentaire </strong>a bien été supprimé.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
    }
}

if($_POST) {
//    dd($_POST);

    extract($_POST);

    // Vérification que les champs ne soient pas vides
    foreach($_POST as $value) {
        if(empty($value)) {
            $error = true;
        }
    }

    if(!isset($error)) {
        $query = $bdd->prepare("INSERT INTO commentaire VALUES (NULL, :id_user, :id_annonce, :commentaire, :created_at, NULL, NULL)");

        $query->bindValue(':id_user', $_SESSION['user']['id_user']);
        $query->bindValue(':id_annonce', $_GET['id_annonce']);
        $query->bindValue(':commentaire', $commentaire);
        $query->bindValue(':created_at', date('Y-m-d H:i:s'));

        if($query->execute()) {
            $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                            <strong>Votre commentaire </strong>a bien été enregistré.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';

        }
    }
}

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce WHERE id_annonce=$_GET[id_annonce]");

if ($query->rowCount()) {
    // Récupération de l'annonce
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    // Récupération du nom du membre de l'annonce
    $queryFindUser = $bdd->query("SELECT prenom FROM user WHERE id_user=$annonce[id_user]");
    $membre = $queryFindUser->fetch(PDO::FETCH_ASSOC);

    // Récupération des autres annonces postées par ce même membre
    $queryOtherAnnonce = $bdd->query("SELECT * FROM annonce WHERE id_user=$annonce[id_user]");
    $otherAnnonce = $queryOtherAnnonce->fetchAll(PDO::FETCH_ASSOC);

    // Récupération de tous les commentaires de cette annonce
    $queryCommentaire = $bdd->query("SELECT * FROM commentaire WHERE id_annonce=$_GET[id_annonce]");
    $commentaires = $queryCommentaire->fetchAll(PDO::FETCH_ASSOC);

}

require_once('include/_header.php');
?>

<div class="row">
    <div class="col-11 mx-auto">

        <div class="row">
            <?= $confirmMessage ?? '' ?>
        </div>

        <div class="row d-flex justify-content-around mt-3">
            <h3 class="col-6 mx-0 px-0"><?= ucfirst($annonce['titre']) ?></h3>
            <div class="col-6 text-end mx-0 px-0">
                <a class="btn btn-success btn-sm">Contacter <?= $membre['prenom'] ?></a>
            </div>
            <hr class="mt-2 col-12 bg-warning">
        </div>

        <div class="row d-flex justify-content-around mt-2">
            <div class="col-6 mx-0 px-0"><img src="<?= UPLOAD_FOLDER . $annonce['photo'] ?>" alt="" width="500" height="330" style="object-fit: cover"></div>
            <div class="col-6">
                <h6>Description</h6>
                <p><?= $annonce['desc_longue'] ?></p>
            </div>
        </div>

        <div class="row d-flex justify-content-around mt-5">
            <div class="col-4 text-center"><i class="bi bi-calendar-check h5 text-warning"></i> Publiée le : <?= $annonce['created_at'] ?></div>
            <div class="col-3 text-center"><i class="bi bi-person-fill h5 text-warning"> </i><a href="" class="text-decoration-none"><?= ucfirst($membre['prenom']) ?></a> ⭐⭐⭐⭐⭐</div>
            <div class="col-1 text-center"><i class="bi bi-currency-euro h5 text-warning"> </i> <?= $annonce['prix'] ?> €</div>
            <div class="col-4 text-center"><i class="bi bi-geo-alt-fill h5 text-warning"> </i> <?= $annonce['adresse'] . ', ' . strtoupper($annonce['cp']) . ' ' . ucfirst($annonce['ville']) ?></div>
        </div>

        <div class="row mt-3 mx-0 px-0">
            <div class="col-12 mx-0 px-0">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d90512.33801933138!2d0.4882839500000001!3d44.85189989999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12aad07c6048f55f%3A0xd23474adfc552221!2s24100%20Bergerac!5e0!3m2!1sfr!2sfr!4v1674146539843!5m2!1sfr!2sfr"
                        height="250"
                        style="border:0; width: 100%;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        <div class="row mt-4">
            <h3 class="mx-0 px-0">Commentaires</h3>
            <hr class="col-12 bg-warning">
            <div class="col-12 mx-0 px-0">
                <?php if(isset($commentaires) && !empty($commentaires)) : ?>
                <?php foreach($commentaires as $commentaire) : ?>
                        <div class="alert alert-info mb-2" role="alert">
                            <div class="row">
                                <div class="col-2">
                                    <h5 class="alert-heading text-start"><?php $auteurCommentaire = $bdd->query("SELECT pseudo FROM user WHERE id_user=$commentaire[id_user]")->fetch(PDO::FETCH_ASSOC); echo $auteurCommentaire['pseudo'] ?></h5>
                                </div>
                                <div class="col-8 text-center">
                                    <span >posté le : <?= $commentaire['created_at'] ?></span>
                                </div>
                                <div class="col-2 text-end">
                                    <?php if($commentaire['id_user'] == $_SESSION['user']['id_user']) : ?>
                                        <a href="?action=delete_commentaire&id_commentaire=<?= $commentaire['id_commentaire'] ?>&id_annonce=<?= $annonce['id_annonce'] ?>" class="text-danger" title="Supprimer votre commentaire"><i class="bi bi-x-circle"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <p class="mt-3"><?= $commentaire['commentaire'] ?></p>
                            <hr class="mb-0">
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="row">
                        <div class="col-12 py-4 alert-warning rounded">
                            <div class="text-center fw-bolder">Aucun commentaire pour cet article</div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <div class="row mt-4">
            <h3 class="mx-0 px-0">Autres Annonces</h3>
            <hr class="col-12 bg-warning">
            <div class="col-12">

            </div>
        </div>

        <div class="row mt-3 d-flex justify-content-around">
            <?php if( isConnect()) : ?>
                <div class="col-6 mx-0 px-0"><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModal">Déposer un commentaire ou une note</a></div>
            <?php else : ?>
                <div class="col-6 mx-0 px-0"><a href="connexion.php" class="text-decoration-none">Se connecter pour laisser un commentaire</a></div>
            <?php endif; ?>

            <div class="col-6 mx-0 px-0 text-end"><a href="index.php" class="text-decoration-none">Revenir vers les annonces</a></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="exampleModalLabel">Déposer un commentaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="?id_annonce=<?= $_GET['id_annonce'] ?>" method="post" class="">
                        <div class="modal-body">
<!--                            <div class="row">-->
<!--                                <div class="col-6">-->
<!--                                    <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" class="form-control">-->
<!--                                </div>-->
<!--                                <div class="col-6">-->
<!--                                    <input type="text" name="email" id="email" placeholder="Votre email" class="form-control">-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="row">
                                <div class="col-12">
                                    <textarea name="commentaire" id="commentaire" rows="6" placeholder="Écrivez votre commentaire ici" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Envoyer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>


<?php require_once('include/_footer.php') ?>