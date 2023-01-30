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

    extract($_POST);

    // Vérification que les champs ne soient pas vides
    foreach($_POST as $value) {
        if(empty($value)) {
            $error = true;
        }
    }

    if(!isset($error)) {

        if (isset($_GET['form']) && $_GET['form'] === 'commentaire') {

            $query = $bdd->prepare("INSERT INTO commentaire VALUES (NULL, :id_user, :id_annonce, :commentaire, :created_at, NULL, NULL)");

            $query->bindValue(':id_user', $_SESSION['user']['id_user']);
            $query->bindValue(':id_annonce', $_GET['id_annonce']);
            $query->bindValue(':commentaire', $commentaire);
            $query->bindValue(':created_at', date('Y-m-d'));

            if ($query->execute()) {
                $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                                <strong>Votre commentaire </strong>a bien été enregistré.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
            }
        }

        if (isset($_GET['form']) && $_GET['form'] === 'note') {

            $query = $bdd->prepare("INSERT INTO note VALUES (NULL, :id_user_notant, :id_user_auteur, :note, :avis, :created_at, NULL, NULL)");

            $query->bindValue(':id_user_notant', $_SESSION['user']['id_user']);
            $query->bindValue(':id_user_auteur', $id_user_auteur);
            # (int) permet de convertir la string en integer
//            $query->bindValue(':note', (int)$note);
            $query->bindValue(':note', $note);
            $query->bindValue(':avis', $avis);
            $query->bindValue(':created_at', date('Y-m-d'));

            if ($query->execute()) {
                $confirmMessage = '<div class="alert alert-success alert-dismissible fade show text-center mt-3" role="alert">
                                <strong>Votre note et avis </strong>ont bien été enregistrés.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
            }
        }
    }
}

//$query = $bdd->query("SELECT * FROM annonce WHERE deleted_at IS NULL");
$query = $bdd->query("SELECT * FROM annonce WHERE id_annonce=$_GET[id_annonce]");

if ($query->rowCount()) {
    // Récupération de l'annonce
    $annonce = $query->fetch(PDO::FETCH_ASSOC);

    // Récupération du nom du membre de l'annonce
    $membre = findUser($annonce['id_user'], $bdd);

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
                <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalContact" >Contacter <?= $membre['prenom'] ?></a>
            </div>
            <hr class="mt-2 col-12 bg-warning">
        </div>

        <div class="row d-flex justify-content-around mt-2">
            <div class="col-6 mx-0 px-0"><img src="<?= UPLOAD_URL . $annonce['photo'] ?>" alt="" width="500" height="330" style="object-fit: contain"></div>
            <div class="col-6">
                <h6>Description</h6>
                <p><?= $annonce['desc_longue'] ?></p>
            </div>
        </div>

        <div class="row d-flex justify-content-around mt-5">
            <div class="col-3 text-center"><i class="bi bi-calendar-check h5 text-warning"></i> Publiée le : <?= $annonce['created_at'] ?></div>
            <div class="col-3 text-center"><i class="bi bi-person-fill h5 text-warning"> </i><a href="" class="text-decoration-none"><?= ucfirst($membre['prenom']) ?></a> ⭐⭐⭐⭐⭐</div>
            <div class="col-2 text-center"><i class="bi bi-currency-euro h5 text-warning"> </i> <?= $annonce['prix'] ?> €</div>
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
                                    <?php if(isConnect()) : ?>
                                        <?php if($commentaire['id_user'] == $_SESSION['user']['id_user']) : ?>
                                            <a href="?action=delete_commentaire&id_commentaire=<?= $commentaire['id_commentaire'] ?>&id_annonce=<?= $annonce['id_annonce'] ?>" class="text-danger" title="Supprimer votre commentaire"><i class="bi bi-x-circle"></i></a>
                                        <?php endif; ?>
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
                <div class="col-6 mx-0 px-0"><a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModalToggle">Déposer un commentaire ou une note</a></div>
            <?php else : ?>
                <div class="col-6 mx-0 px-0"><a href="connexion.php" class="text-decoration-none">Se connecter pour laisser un commentaire</a></div>
            <?php endif; ?>

            <div class="col-6 mx-0 px-0 text-end"><a href="index.php" class="text-decoration-none">Revenir vers les annonces</a></div>
        </div>

        <!--        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Faites votre choix</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                <button class="btn btn-outline-primary me-3" data-bs-target="#modalCommentaire" data-bs-toggle="modal" data-bs-dismiss="modal">Déposer un commentaire</button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-primary" data-bs-target="#modalNote" data-bs-toggle="modal" data-bs-dismiss="modal">Laisser une note et un avis</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Commentaire -->
        <div class="modal fade" id="modalCommentaire" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="exampleModalLabel">Déposer un commentaire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="?id_annonce=<?= $_GET['id_annonce'] ?>&form=commentaire" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <textarea name="commentaire" id="commentaire" rows="6" placeholder="Écrivez votre commentaire ici" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
<!--                            <button class="me-auto btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Revenir aux choix</button>-->
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Envoyer</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- Modal Note -->
        <div class="modal fade" id="modalNote" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="exampleModalLabel">Laisser une note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="?id_annonce=<?= $_GET['id_annonce'] ?>&form=note" method="post">
                        <div class="modal-body">
                            <div class="row input-group">
                                <div class="col-4">
                                    <select name="note" id="note" class="form-select">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="col-8">
                                    <input type="text" placeholder="Votre email" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <textarea name="avis" id="avis" rows="6" placeholder="Écrivez votre avis ici" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
<!--                            <button class="me-auto btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Revenir aux choix</button>-->
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Envoyer</button>
                        </div>

                        <input type="hidden" name="id_user_auteur" value="<?= $annonce['id_user'] ?>">
                    </form>

                </div>
            </div>
        </div>
        <!-- Modal Contact -->
        <div class="modal fade" id="modalContact" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="exampleModalLabel">Contacter la personne</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="get">
                        <div class="modal-body">

                            <div class="row input-group mx-auto">
                                <div class="col-6">
                                    <input type="text" name="email" placeholder="Votre email" class="form-control">
                                </div>
                                <div class="col-6">
                                    <input type="text" name="sujet" placeholder="Quel est le sujet ?" class="form-control">
                                </div>
                            </div>
                            <div class="row input-group mt-3 mx-auto">
                                <div class="col-12">
                                    <textarea name="corps" rows="4" placeholder="Écrivez votre email ici" class="form-control"></textarea>
                                </div>
                            </div>

                            <input type="hidden" name="id_annonce" value="<?= $annonce['id_annonce'] ?>">

                        </div>
                        <div class="modal-footer">
                            <!--                            <button class="me-auto btn btn-sm btn-primary"  data-bs-toggle="modal" data-bs-target="#exampleModalToggle" data-bs-dismiss="modal"><i class="bi bi-arrow-left"></i> Revenir aux choix</button>-->
                            <small class="me-auto text-warning"><i class="bi bi-phone"></i> Téléphone : <span class="text-decoration-underline"><?= $membre['telephone'] ?></span></small>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Envoyer à <?= $membre['email'] ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
<!--        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

    </div>
</div>


<?php require_once('include/_footer.php') ?>