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
        $annee = isset($_GET['annee']) ? e($_GET['annee']) : null;
        $mois = isset($_GET['mois']) ? e($_GET['mois']) : null;
        $composante = isset($_GET['composante']) ? e($_GET['composante']) : null;

        if ($annee && $mois) {
            $typeBdl = $bd->getbdltype((int) $composante, (int) $_SESSION['id'], (int) $annee, (int) $mois, 0);
            //type periode bdl= index j0 

            if ($typeBdl == 'Créneau') {
                $activites = $bd->getAllNbHeureActivite($annee, $mois, $composante, $_SESSION['id']);
            } else {
                $activites['demijournee'] = $bd->getAllDemiJourActivite($annee, $mois, $composante, $_SESSION['id']);
                $activites['journee'] = $bd->getAllJourActivite($annee, $mois, $composante, $_SESSION['id']);
            }

            $data_avant = $bd->getbdl((int) $annee, (int) $mois, (int) $composante, (int) $_SESSION['id']);

            $data = ['type' => $typeBdl, 'menu' => $this->action_get_navbar(), 'bdl' => $activites, '$data' => $data_avant];
            $this->render("bdl", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
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
            $cardLink = '?controller=prestataire&action=afficher_bdl';
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
        // Assign sanitized $_POST values
        $idComposante = isset($_POST['composante']) ? $_POST['composante'] : '';
        $annee = isset($_POST['annee']) ? $_POST['annee'] : '';
        $mois = isset($_POST['mois']) ? $_POST['mois'] : '';
        $idPrestataire = isset($_POST['idPrestataire']) ? $_POST['idPrestataire'] : '';

        // Check if any of the sanitized values are empty
        if ($idComposante !== '' && $annee !== '' && $mois !== '' && $idPrestataire !== '') {
            // Call the addBdl function
            $result = $bd->addbdl($idComposante, $annee, $mois, $idPrestataire);

            // Output result
            if ($result) {
                echo "BDL added successfully.";
            } else {
                echo "Failed to add BDL.";
            }
        } else {
            // Handling if any required $_POST values are missing
            echo "Please provide all required values.";
        }
        $this->action_ajout_bdl_form();

    }
}
