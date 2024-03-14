<?php

class Controller_prestataire extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Renvoie le tableau de bord du prestataire avec les variables adéquates
     * @return void
     */
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['role'])) {
            unset($_SESSION['role']);
        }
        $_SESSION['role'] = 'prestataire';

        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $bdlLink = '?controller=prestataire&action=composante_bdl';
            $headerDashboard = ['Société', 'Composante', 'Bon de livraison'];
            $data = [
                'title' => "Mes composantes",
                'menu' => $this->action_get_navbar(),
                'bdlLink' => $bdlLink,
                'header' => $headerDashboard,
                'dashboard' => $bd->getDashboardPrestataire($_SESSION['id'])
            ];
            $this->render('tableau', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Renvoie la vue qui montre les informations de l'utilisateur connecté
     * @return void
     */
    public function action_infos()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->render('infos', ['menu' => $this->action_get_navbar()]);
    }

    /**
     * Action qui retourne les éléments du menu pour le prestataire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=prestataire&action=dashboard', 'name' => 'Composante'],
            ['link' => '?controller=prestataire&action=liste_bdl', 'name' => 'Bons de livraison']
        ];
    }

    /**
     * Ajoute dans la base de données la date à laquelle le prestataire est absent
     * @return void
     */


    /**
     * Renvoie la vue qui lui permet de remplir son bon de livraion avec le bon type
     * @return void
     */
    public function action_afficher_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['annee']) && isset($_GET['mois'])) {

            $typeBdl = $bd->getbdltype($_GET['annee'], $_GET['mois'], $_GET['composante']);
            if (isset($typeBdl)) {
                if (array_key_exists('numero', $typeBdl)) {
                    $typeBdl['type_bdl'] = 'Heure';
                } elseif (array_key_exists('idType', $typeBdl)) {
                    $typeBdl['type_bdl'] = 'Demi-journée';
                } else {
                    $typeBdl['type_bdl'] = 'Journée';
                }

                $data_avant = $bd->getbdl(e($_GET['annee']), e($_GET['mois']), e($_GET['composante']), $_SESSION['id']);


                $data = ['menu' => $this->action_get_navbar(), 'bdl' => $typeBdl, '$data' => $data_avant];
                $this->render("bdl", $data);
            } else {
                echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
            }
        }
    }
    /**
     * Vérifie d'avoir les informations nécessaire pour renvoyer la vue liste avec les bonnes variables pour afficher la liste des bons de livraisons du prestataire en fonction de la mission
     * @return void
     */
    function action_composante_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $buttonLink = '?controller=prestataire&action=ajout_bdl_form';
            $cardLink = '?controller=prestataire&action=afficher_bdl&composante=' . e($_GET['id']);
            $data = [
                'title' => 'Bon de livraison de la composante' . e($_GET['nom']),
                'buttonLink' => $buttonLink,
                'cardLink' => $cardLink,
                'menu' => $this->action_get_navbar(),
                'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), $_SESSION['id'])
            ];
        }
        $this->render('liste', $data);
    }

    /**
     * Renvoie la liste des bons de livraison du prestataire connecté
     * @return void
     */
    public function action_liste_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $cardLink = '?controller=prestataire&action=afficher_bdl';
            $buttonLink = '?controller=prestataire&action=ajout_bdl_form';
            $data = [
                'title' => 'Mes Bons de livraison',
                'buttonLink' => $buttonLink,
                'cardLink' => $cardLink,
                'menu' => $this->action_get_navbar(),
                "person" => $bd->getAllBdlPrestataire($_SESSION['id'])
            ];
            $this->render("liste", $data);
        }
    }

    /**
     * Vérifie d'avoir les informations nécessaires pour créer un bon de livraison
     * @return void
     */
    public function action_prestataire_creer_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id']) && isset($_POST['mission'])) {
            $bd->addBdlForPrestataire($_SESSION['id'], e($_POST['mission']));
        } else {
            echo 'Une erreur est survenue lors de la création du bon de livraison';
        }
    }



    /**
     * Renvoie le formulaire pour ajouter un bon de livraison
     * @return void
     */
    public function action_ajout_bdl_form()
    {
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_bdl', $data);
    }

    /**
     * Vérifie d'avoir les informations nécessaire pour ajouter un bon de livraison à une mission
     * @return void
     */
    public function action_ajout_bdl()
    {
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_POST['mission'] && $_POST['mois'] && $_POST['composante']) {
            $bd->addBdlInMission(e($_POST['mission']), e($_POST['composante']), e($_POST['mois']), $_SESSION['id']);
        }
        $this->action_ajout_bdl_form();
    }
}
