<?php 
require_once('include/_init.php');

// Lorque l'internaute clique sur le lien 'deconnexion', il transmet dans le même temps dans l'URL les paramètres 'action=deconnexion'
// La condition IF permet de vérifier si l'index 'action' est bien défini dans l'URL et qu'il a pour valeur 'deconnexion'.
// On entre dans le IF seulement dans le cas où l'internaute clique sur 'deconnexion'.
// Pour que l'internaute soit déconnecté, il faut soit supprimer la session ou vider une partie afin que l'index 'user' dans la session ne soit plus défini
if(isset($_GET['action']) && $_GET['action'] === 'deconnexion')
{
    unset($_SESSION['user']);
    header('location: connexion.php');
}

extract($_POST);

if(isConnect())
{
    header("location: index.php");
}

if($_POST)
{
    $data= $bdd->prepare("SELECT * FROM user WHERE pseudo = :pseudo");
    $data->bindValue(':pseudo', $pseudo);
    $data->execute();

    if($data->rowCount())
    {
        // echo "Ce pseudo ou cet email existant en BDD";
        $user = $data->fetch(PDO::FETCH_ASSOC);

        // password_verify('la string du form', 'le mdp en bdd hashé') ===> compare une clé de hashage (le mdp en bdd) à une chaine de caractères (le mdp saisi dans le formulaire) 
        if (password_verify($password, $user['password']))
        {
           // echo "MDP ok";

           // On passe en revue toutes les données de l'internaute récupérées en BDD de l'internaute qui a correctement remplit le formulaire de connexion
            // $user : tableau ARRAY contenant toute les données de l'utilisateur en BDD
           foreach($user as $key => $value)
           {
               if($key !== 'password') // on exclut la clef 'password' de l'array 'user' pour ne pas la stocker dans la session
               {
                   // sert à garder les infos du membre tout au long de sa session connectée sur le site, accessible partout dans le site

                   // On crée dans la session un index 'user' contenant un tableau ARRAY avec toutes les données de l'utilisateur
                    // C'est ce qui permettra d'identifier l'utilisateur connecté sur le site et cela lui permettra de naviguer sur le site tout en restant connecté
                   $_SESSION['user'][$key] = $value;
               }
           }
            header("location: index.php");

            // echo "<pre>"; print_r($_SESSION); echo "</pre>";

        }
        else
        {
            // erreur MDP
            $error = "<p class='text-center text-white bg-danger p-3 col-md-4 mx-auto'>Le couple pseudo et/ou mot de passe est invalide</p>";
        }
    } 
    else
    {
        // erreur pseudo
        $error = "<p class='text-center text-white bg-danger p-3 col-md-4 mx-auto'>Le couple pseudo et/ou mot de passe est invalide</p>";
    }
}




require_once('include/_header.php');
?>

<h1 class="display-4 text-center my-4">Identifiez-vous</h1>

<?php if(isset($error)) echo $error; ?>

<form class="col-md-4 mx-auto my-5" method="post" id="connexion">

    <div class="form-group">
        <label for="pseudo"> Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" class="form-control" value="<?php if(isset($pseudo)) echo $pseudo; ?>">
    </div>
    <div class="form-group mt-3">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-control">
    </div>

    <button type="submit" class="d-block mx-auto col-md-6 mt-4 btn btn-success">Connexion</button>
</form>

<?php 
require_once('include/_footer.php');
?>