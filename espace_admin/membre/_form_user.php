<?php
require_once('../../include/_init.php');

// Si l'admin a cliqué sur "modifier" (dans "show_user.php")
if ($_GET['action'] === 'update' || $_GET['action'] === 'submit_update' ) {

    $query= $bdd->prepare("SELECT * FROM user WHERE id_user=:id_user");
    // TODO: Corriger le problème "amp;" dans $_GET ici aussi
    $query->bindValue(':id_user', $_GET['amp;id_user'] ?? $_GET['id_user']);

    $query->execute();

    if($query->rowCount()) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }
}

// Si le formulaire a été soumis
if($_POST){

    extract($_POST);

    // classe bootstrap : bordure rouge
    $border = "border border-danger";

    if (empty($pseudo)) {
        $errorPseudo = "<p class='text-danger font-italic'>Il vous faut un pseuso</p>";
        $error = true;
    }

    if($_GET['action'] === 'submit_create' || $pseudo !== $currentPseudo) {
        // 3. Contrôler la validité du pseudo, si le pseudo est existant en BDD, alors on affiche un message d'erreur. Faites de même pour le champ 'email'
        $verifPseudo = $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
        $verifPseudo->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $verifPseudo->execute();
        if ($verifPseudo->rowCount()) {
            $errorPseudo = "<p class='text-danger font-italic'>Le pseudo $pseudo est déjà pris. Veuillez en saisir un autre.</p>";
            $error = true;
        }
    }

    if (empty($email)) {
         $errorEmail = "<p class='text-danger font-italic'>Il faut renseigner un email</p>";
        $error = true;
    }
// 4. Informer l'internaute si les mots de passe ne correspondent pas.
    if ($password !== $confirm_mdp) {
        $errorMdp = "<p class='text-danger font-italic'>Attention ! Les mots de passe ne sont pas identiques</p>";
        $error = true;

    }

    if (!isset($error)) {

        // 5. Gérer les failles XSS
        foreach ($_POST as $key => $value) {
            $_POST[$key] = htmlspecialchars($value);
        }

        if(isset($_GET['action']) && $_GET['action'] === 'submit_create') {

            $query = $bdd->prepare("INSERT INTO user VALUES (NULL, :pseudo, :password, :nom, :prenom, :email, :telephone, :civilite, :statut, :created_at, NULL, NULL)");

            // CRYPTAGE DU MDP EN BDD
            $password = password_hash($password, PASSWORD_BCRYPT);

            $query->bindValue(':created_at', date('Y-m-d'));
            $confirm = "<span class='d-block col-md-6 mx-auto mb-3 bg-success text-center text-white p-4 rounded'>Le membre a bien été ajouté</span>";
        }

        if(isset($_GET['action']) && $_GET['action'] === 'submit_update' && isset($_GET['id_user'])) {
            $query = $bdd->prepare("UPDATE user SET pseudo=:pseudo, password=:password, nom=:nom, prenom=:prenom, email=:email, telephone=:telephone, civilite=:civilite, statut=:statut, updated_at=:updated_at WHERE id_user=:id_user");

            if($currentPassword !== $password) {
                // CRYPTAGE DU MDP EN BDD
                $password = password_hash($password, PASSWORD_BCRYPT);
            }

            $query->bindValue(":id_user", $_GET["id_user"], PDO::PARAM_INT);
            $query->bindValue(":updated_at", date('Y/m/d'));
            $confirmMessage = "<p class='col-md-5 mx-auto bg-success text-center text-white p-4 rounded'>Le membre a bien été modifié</p>";
        }

        $query->bindValue(':pseudo', $pseudo);
        $query->bindValue(':password', $password);
        $query->bindValue(':nom', $nom);
        $query->bindValue(':prenom', $prenom);
        $query->bindValue(':email', strtolower($email));
        $query->bindValue(':telephone', $telephone);
        $query->bindValue(':civilite', $civilite);
        $query->bindValue(':statut', $statut, PDO::PARAM_INT);

        $query->execute();

        header('location: show_user.php');

    } // end if(!isset($error))
    require_once('../include/_header_admin.php');

    echo '<link rel="stylesheet" href="../css/styles.css">';
} // end if($_POST)

?>

<div class="container-fluid px-4">

    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-keyboard"></i>
            <?= $_GET['action'] === 'create' ? 'Ajouter un nouveau' : 'Modifier le'?>  membre
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card shadow border-0 rounded-lg">
                        <div class="card-header"><h3 class="text-center font-weight-light my-4"><?= $_GET['action'] === 'create' ? 'Nouveau membre' : "Modifier $user[pseudo]" ?></h3></div>
                        <div class="card-body">
                            <!-- TODO: Corriger le problème "amp;" dans $_GET  -->
                            <form action="_form_user.php?action=<?= (isset($_GET['action']) && $_GET['action'] === 'create') ? 'submit_create' : 'submit_update&id_user=' . (isset($_GET['amp;id_user']) ? $_GET['amp;id_user'] : $_GET['id_user']) ?>" method="post" novalidate>

                                <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">1 - Identifiants</h3>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="pseudo">Pseudo</label>
                                        <input type="text" id="pseudo" name="pseudo" class="form-control <?php if(isset($errorPseudo)) echo $border; ?>" value="<?= $user['pseudo'] ?? '' ?>">
                                        <?= $errorPseudo ?? '' ?>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" class="form-control <?php if(isset($errorEmail)) echo $border; ?>" value="<?= $user['email'] ?? '' ?>">
                                        <?= $errorEmail ?? '' ?>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-6">
                                        <label for="password">Mot de passe</label>
                                        <input type="password" id="password" name="password" class="form-control" value="<?= $user['password'] ?? '' ?>">
                                    </div>
                                    <div class="col-6">
                                        <label for="confirm_mdp">Confirmer mot de passe</label>
                                        <input type="password" id="confirm_mdp" name="confirm_mdp" class="form-control" value="<?=  $user['password'] ?? '' ?>">
                                        <?= $errorMdp ?? '' ?>
                                    </div>
                                </div>

                                <!--  ////////////////////////////////////////////////////////////////////////////////////////////////////////////  -->
                                <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">2 - Civilité</h3>

                                <div class="input-group mt-2 mb-3">
                                    <label class="input-group-text" for="civilite">Civilité</label>
                                    <select class="form-select" id="civilite" name="civilite">
                                        <option value="m" <?= (isset($_GET['action']) && $_GET['action'] === 'update') && $user['civilite'] === 'm' ? 'selected' : '' ?>>Homme</option>
                                        <option value="f" <?= (isset($_GET['action']) && $_GET['action'] === 'update') && $user['civilite'] === 'f' ? 'selected' : '' ?>>Femme</option>
                                    </select>
                                </div>
                                <div class="row mt-2">
                                    <div class="form-group col-4">
                                        <label for="prenom">Prénom</label>
                                        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= $user['prenom'] ?? '' ?>">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="nom">Nom</label>
                                        <input type="text" id="nom" name="nom" class="form-control" value="<?= $user['nom'] ?? '' ?>">
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="telephone">Téléphone</label>
                                        <input type="text" id="telephone" name="telephone" class="form-control" value="<?= $user['telephone'] ?? '' ?>">
                                    </div>
                                </div>

                                <!--  ////////////////////////////////////////////////////////////////////////////////////////////////////////////  -->
                                <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">3 - Statut</h3>

                                <div class="input-group mt-2 mb-3">
                                    <label class="input-group-text" for="statut">Statut du membre</label>
                                    <select class="form-select" id="statut" name="statut">
                                        <option value="0" <?= (isset($_GET['action']) && $_GET['action'] === 'update') && $user['statut'] === '0' ? 'selected' : '' ?>>Membre</option>
                                        <option value="1" <?= (isset($_GET['action']) && $_GET['action'] === 'update') && $user['statut'] === '1' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </div>

                                <div class="mt-4 mb-0">
                                    <div class="d-grid">
                                        <input type="submit" class="btn btn-primary btn-block" value="<?= isset($_GET['action']) && ($_GET['action'] === 'update' || $_GET['action'] === 'submit_update' ) ? 'Modifier' : 'Créer'?>" />
                                    </div>
                                </div>

                                <input type="hidden" name="currentPassword" value="<?= $user['password'] ?? '' ?>">
                                <input type="hidden" name="currentPseudo" value="<?= $user['pseudo'] ?? '' ?>">
                            </form>

                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small"><a class=" text-danger" href="show_user.php">Annuler</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>