<?php

require_once('include/_init.php');

if(isConnect()) {
    header("location: profil.php");
}

// 2. Contrôler en PHP que l'on réceptionne bien toutes les données saisies dans le formulaire

extract($_POST);

if($_POST && !empty($_POST))
{
    // classe bootstrap : bordure rouge
    $border="border border-danger";

    // 3. Contrôler la validité du pseudo, si le pseudo est existant en BDD, alors on affiche un message d'erreur. Faites de même pour le champ 'email'
    $verifPseudo = $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
    $verifPseudo->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
    $verifPseudo->execute();

    if (empty($pseudo))
    {
        $errorPseudo= "<p class='text-danger font-italic'>Il vous faut un pseuso</p>";
        $error=true;
    }

    if ($verifPseudo->rowCount())
    {
        $errorPseudo= "<p class='text-danger font-italic'>Dommage, le pseudo $pseudo est déjà pris. Veuillez en saisir un autre.</p>";
        $error = true;
    }

    // ---------------------- EMAIL CHECK -------------------------
//    $verifEmail = $bdd->prepare("SELECT * FROM user WHERE email = :email");
//    $verifEmail->bindValue(':email', $email, PDO::PARAM_STR);
//    $verifEmail->execute();
//
//    if($verifEmail->rowCount())
//    {
//        $errorEmail= "<p class='text-danger font-italic'>Un compte existe déjà avec cet email : $email. Veuillez en saisir un autre.</p>";
//        $error = true;
//    }
//
//    if (empty($email))
//    {
//        $errorEmail= "<p class='text-danger font-italic'>Il faut renseigner un email</p>";
//        $error=true;
//    }

// 4. Informer l'internaute si les mots de passe ne correspondent pas.
    if($password !== $confirm_mdp)
    {
        $errorMdp = "<p class='text-danger font-italic'>Attention ! Les mots de passe ne sont pas identiques</p>";
        $error = true;
    }

    if(!isset($error))
    {
        // 5. Gérer les failles XSS
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        }

        // CRYPTAGE DU MDP EN BDD
        $password = password_hash($password, PASSWORD_BCRYPT);


        // 6. Si l'internaute a correctement remplit le formulaire, alors on peut réaliser le traitement PHP + SQL permettant d'insérer le membre en BDD (requete préparée | prepare() + bindValue())
        $insert=$bdd->prepare("INSERT INTO user VALUES (NULL, :pseudo, :password, :nom, :prenom, :email, :telephone, :civilite, :statut, :created_at, NULL, NULL)");

        $insert->bindValue(':pseudo', $pseudo);
        $insert->bindValue(':password', $password);
        $insert->bindValue(':nom', $nom);
        $insert->bindValue(':prenom', $prenom);
        $insert->bindValue(':email', strtolower($email));
        $insert->bindValue(':telephone', $telephone);
        $insert->bindValue(':civilite', $civilite);
        $insert->bindValue(':statut', 0, PDO::PARAM_INT);
        $insert->bindValue(':created_at', date('Y-m-d'));

        $insert->execute();

        // Après l'insertion en BDD, on redirige le user vers une page de confirmation d'inscription.
        header("location: valid_inscription.php");
    }

}

require_once('include/_header.php');

?>
<!-- Nous sommes dans la balise ouvrante .main (in "_nav.php") ... -->
<h1 class="text-center mx-auto mt-3 text-warning">INSCRIPTION</h1>


<form class="col-md-6 col-sm-9 mx-auto my-5" method="post" >

    <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">1 - Vos Identifiants</h3>

    <div class="row">
        <small class="text-danger fst-italic text-decoration-underline mb-2">* champs obligatoires</small>
    </div>

    <div class="row">
        <div class="form-group col-6">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" class="form-control <?php if(isset($errorPseudo)) echo $border; ?>">
            <?= $errorPseudo ?? '' ?>
        </div>
        <div class="form-group col-6">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control <?php if(isset($errorEmail)) echo $border; ?>">
            <?= $errorEmail ?? '' ?>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-6">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="col-6">
            <label for="confirm_mdp">Confirmer mot de passe</label>
            <input type="password" id="confirm_mdp" name="confirm_mdp" class="form-control">
            <?= $errorMdp ?? '' ?>
        </div>
    </div>

    <!--  ////////////////////////////////////////////////////////////////////////////////////////////////////////////  -->
    <h3 class="text-center text-warning my-4 bg-dark rounded mx-auto col-md-8">2 - Votre Civilité</h3>

    <div class="input-group mt-2 mb-3">
        <label class="input-group-text" for="civilite">Civilité</label>
        <select class="form-select" id="civilite" name="civilite">
            <option selected value="m">Homme</option>
            <option value="f">Femme</option>
        </select>
    </div>
    <div class="row mt-2">
        <div class="form-group col-4">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control">
        </div>
        <div class="form-group col-4">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control">
        </div>
        <div class="form-group col-4">
            <label for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" class="form-control">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="d-block mx-auto col-md-6 col-sm-6 btn btn-success">S'incrire</button>
    </div>
</form>


<!-- ... et la balise fermante .main (in "_footer.php") -->
<?php
require_once('include/_footer.php');
?>