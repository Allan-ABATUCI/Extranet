<?php
/**
 * Fonction échappant les caractères html dans $message
 * @param string $message chaîne à échapper
 * @return string chaîne échappée
 */
function e($message)
{
    return htmlspecialchars($message, ENT_QUOTES);
}

/**
 * Fonction qui génère un mot de passe de 12 caractères
 * @return false|string
 */
function genererMdp(){
    // chaine de caractères qui sera mis dans le désordre:
    $Chaine = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // on mélange la chaine avec la fonction str_shuffle(), propre à PHP
    $Chaine = str_shuffle($Chaine);

    // ensuite on coupe à la longueur voulue avec la fonction substr(), propre à PHP aussi
    return substr($Chaine,0, 12);
}

function maj_infos_personne()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $id = $_SESSION['id'];
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }
    $bd = Model::getModel();
    if(isset($_POST['nom']) && !preg_match('/^ *$/', $_POST['nom'])){
        $bd->setNomPersonne($id, $_POST['nom']);
    }
    if(isset($_POST['prenom']) && !preg_match('/^ *$/', $_POST['prenom'])){
        $bd->setPrenomPersonne($id, $_POST['prenom']);
    }
    if(isset($_POST['email']) && !preg_match('/^ *$/', $_POST['email'])){
        $bd->setEmailPersonne($id, $_POST['email']);
    }
    if(isset($_POST['mdp']) && !preg_match('/^ *$/', $_POST['mdp'])){
        $bd->setMdpPersonne($id, $_POST['mdp']);
    }
}

function maj_infos_client()
{
    $bd = Model::getModel();
    if(isset($_GET['id'])){
        if(isset($_POST['client']) && !preg_match('/^ *$/', $_POST['client'])){
            $bd->setNomClient($_GET['id'], $_POST['client']);
        }
        if(isset($_POST['telephone-client']) && !preg_match('/^ *$/', $_POST['telephone-client'])){
            $bd->setTelClient($_GET['id'], $_POST['telephone-client']);
        }
    }
}

function maj_infos_composante(){
    $bd = Model::getModel();
    if(isset($_GET['id'])){
        if(isset($_POST['composante']) && !preg_match('/^ *$/', $_POST['composante'])){
            $bd->setNomComposante($_GET['id'], $_POST['composante']);
        }
        if(isset($_POST['client']) && !preg_match('/^ *$/', $_POST['client'])){
            $bd->setTelClient($_GET['id'], $_POST['telephone-client']);
        }
        if(isset($_POST['numero-voie']) && !preg_match('/^ *$/', $_POST['numero-voie'])){
            $bd->setNumeroAdresse($_GET['id'], $_POST['numero-voie']);
        }
        if(isset($_POST['type-voie']) && !preg_match('/^ *$/', $_POST['type-voie'])){
            $bd->setLibelleTypevoie($_GET['id'], $_POST['type-voie']);
        }
        if(isset($_POST['nom-voie']) && !preg_match('/^ *$/', $_POST['nom-voie'])){
            $bd->setNomVoieAdresse($_GET['id'], $_POST['nom-voie']);
        }
        if(isset($_POST['cp']) && !preg_match('/^ *$/', $_POST['cp'])){
            $bd->setCpLocalite($_GET['id'], $_POST['cp']);
        }
        if(isset($_POST['ville']) && !preg_match('/^ *$/', $_POST['ville'])){
            $bd->setVilleLocalite($_GET['id'], $_POST['ville']);
        }
    }
}