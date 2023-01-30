<?php

///////////////////////////////////////////////////////////////////////////////////////
// ************************** FONCTIONS MEMBRE CONNECTÉ *****************************//
///////////////////////////////////////////////////////////////////////////////////////
function isConnect(): bool
{
    if(!isset($_SESSION['user'])) {
        return false;
    }

    return true;
}

//////////////////////////////////////////////////////////////////////////////////////
// ****************************** FONCTIONS ADMIN **********************************//
//////////////////////////////////////////////////////////////////////////////////////
///
function isAdminConnect(): bool
{
    return isConnect() && $_SESSION['user']['statut'] === '1';
}

//////////////////////////////////////////////////////////////////////////////////////
// ******************************* FONCTIONS DEV ***********************************//
//////////////////////////////////////////////////////////////////////////////////////

function slugify($text): string {

    $table = [
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y',
        'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
    ];

    // Retirer les espaces blancs
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // On retourne le slug
    return strtolower(strtr($text, $table));
}

function dd($arg): void
{
    var_dump($arg);  die();
}
//////////////////////////////////////////////////////////////////////////////////////
// ******************************* FONCTIONS SQL ***********************************//
//////////////////////////////////////////////////////////////////////////////////////
function findUser($id, PDO $bdd) {
    return $bdd->query("SELECT * FROM user WHERE id_user=$id")->fetch(PDO::FETCH_ASSOC);
}

function findAnnonce($id, PDO $bdd) {
    return $bdd->query("SELECT * FROM annonce WHERE id_annonce=$id")->fetch(PDO::FETCH_ASSOC);
}