<?php

class Controller_commercial extends Controller
{
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Renvoie le tableau de bord du commercial avec les variables adéquates
     * @return void
     */
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['role'] = 'commercial';
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $bdlLink = '?controller=commercial&action=mission_bdl';
            $headerDashboard = ['Société', 'Composante','Nom Mission' ,'Préstataire assigné', 'Bon de livraison'];
            $data = ['menu'=>$this->action_get_navbar(), 'bdlLink' => $bdlLink, 'header' => $headerDashboard, 'dashboard' => $bd->getdashboardCommercial($_SESSION['id'])];
            return $this->render('prestataire_missions', $data);
        } 
        else 
        {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le commercial
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [
            ['link' => '?controller=commercial&action=dashboard', 'name' => 'Missions'],
            ['link' => '?controller=commercial&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=commercial&action=clients', 'name' => 'Clients'],
            ['link' => '?controller=commercial&action=prestataires', 'name' => 'Prestataires'],
            ];
    }

    /**
     * Vérifie d'avoir les informations nécessaire pour renvoyer la vue liste avec les bonnes variables pour afficher la liste des bons de livraisons d'un prestataire en fonction de la mission
     * @return void
     */
    public function action_mission_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id']) && isset($_GET['id-prestataire'])){
            $cardLink = '?controller=commercial&action=consulter_bdl';
            $data = ['title' => 'Bons de livraison', 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), 'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), e($_GET['id-prestataire']))];
            $this->render('liste', $data);
        }
        $this->action_dashboard();
    }

    /**
     * Met à jour les informations de l'utilisateur connecté
     * @return void
     */
    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    /**
     * Met à jour les informations du client
     * @return void
     */
    public function action_maj_infos_client()
    {
        maj_infos_client(); // fonction dans Utils
        $this->action_infos_client();
    }

    /**
     * Met à jour les informations de la personne
     * @return void
     */
    public function action_maj_infos_personne()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos_personne();
    }

    /**
     * Met à jour les informations de la composante
     * @return void
     */
    public function action_maj_infos_composante()
    {
        maj_infos_composante(); // fonction dans Utils
        $this->action_infos_composante();
    }

    /**
     * Vérifie qu'il existe dans l'url l'id qui fait référence au bon de livraison et renvoie la vue qui permet de consulter le bon de livraison
     * @return void
     */
    public function action_consulter_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $typeBdl = $bd->getBdlTypeAndMonth(e($_GET['id']));
            if($typeBdl['type_bdl'] == 'Heure'){
                $activites = $bd->getAllNbHeureActivite(e($_GET['id']));
            }
            if($typeBdl['type_bdl'] == 'Demi-journée'){
                $activites = $bd->getAllDemiJourActivite(e($_GET['id']));
            }
            if($typeBdl['type_bdl'] == 'Journée'){
                $activites = $bd->getAllJourActivite(e($_GET['id']));
            }

            $data = ['menu' => $this->action_get_navbar(), 'bdl' => $typeBdl, 'activites' => $activites];
            $this->render("consulte_bdl", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }

    /**
     * Renvoie la liste de tous les clients
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_clients()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=commercial&action=ajout_interlocuteur_form';
            $cardLink = '?controller=commercial&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getClientForCommercial(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de toutes les composantes
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_composantes()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            
            $title = 'Composantes';
            $cardLink = '?controller=commercial&action=infos_composante';
            $data = ['title' => $title, 'person' => $bd->getComposantesForCommercial($_SESSION['id']), 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste des interlocuteurs des composantes assignées au commercial connecté
     * @return void
     */
    public function action_commercial_interlocuteurs(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            $data=[$bd->getInterlocuteurForCommercial($_SESSION['id'])];
            $this->render("liste",$data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des clients.';
        }
    }

    /**
     * Renvoie la liste de tous les prestataires
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_prestataires(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])){
            $bd = Model::getModel();
            
            $cardLink = "?controller=commercial&action=infos_personne";
            $data = ['title' => 'Prestataires', 'cardLink' => $cardLink, "person" => $bd->getPrestataireForCommercial($_SESSION['id']), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
        else 
        {
            echo 'Une erreur est survenue lors du chargement des prestataire.';
        }
    }

    /**
     * Vérifie si la personne existe et la créée si ce n'est pas le cas
     * @param $nom
     * @param $prenom
     * @param $email
     * @return void
     */
    public function action_ajout_personne($nom, $prenom, $email)
    {
        $bd = Model::getModel();
        if (!$bd->checkPersonneExiste($email)) {
            $bd->createPersonne($nom, $prenom, $email, genererMdp());
        }
    }

    /**
     * Vérifie d'avoir toutes les informations nécessaires pour l'ajout d'un interlocuteur dans une composante
     * @return void
     */
    public function action_ajout_interlocuteur_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_GET['id-composante']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur'])) {
            if (!$bd->checkInterlocuteurExiste(e($_POST['email-interlocuteur']))) {
                $this->action_ajout_personne(e(_POST['nom-interlocuteur']), e($_POST['prenom-interlocuteur']), e($_POST['email-interlocuteur']));
                $bd->addInterlocuteur(e($_POST['email-interlocuteur']));
            }
            $bd->assignerInterlocuteurComposanteByIdComposante(e($_GET['id-composante']), e($_POST['email-interlocuteur']));
            $this->action_composantes();
        }
        if (isset($_GET['id-client']) && isset($_POST['email-interlocuteur']) && isset($_POST['nom-interlocuteur']) && isset($_POST['prenom-interlocuteur']) && isset($_POST['composante'])) {
            if (!$bd->checkInterlocuteurExiste(e($_POST['email-interlocuteur']))) {
                $this->action_ajout_personne(e($_POST['nom-interlocuteur']), e($_POST['prenom-interlocuteur']), e($_POST['email-interlocuteur']));
                $bd->addInterlocuteur(e($_POST['email-interlocuteur']));
            }
            $bd->assignerInterlocuteurComposanteByIdClient(e($_GET['id-client']), e($_POST['email-interlocuteur']), e($_POST['composante']));
            $this->action_clients();
        }
    }

    //Ajouter interlocuteur

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un interlocuteur
     * @return void
     */
    public function action_ajout_interlocuteur_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_interlocuteur', $data);
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
     * Vérifie qu'il existe dans l'url l'id qui fait référence au client et renvoie la vue qui affiche les informations sur le client
     * @return void
     */
    public function action_infos_client()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosSociete(e($_GET['id']));
            $composantes = $bd->getComposantesSociete(e($_GET['id']));
            $interlocuteurs = $bd->getInterlocuteursSociete(e($_GET['id']));
            $data = ['infos' => $infos,
                'composantes' => $composantes,
                'interlocuteurs' => $interlocuteurs,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_client', $data);
        }
    }

    /**
     * Vérifie qu'il existe un id qui fait référence à une personne de la base de données et renvoie la vue qui affiche les données
     * @return void
     */
    public function action_infos_personne()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $data = ['person' => $bd->getInfosPersonne(e($_GET['id'])), 'menu' => $this->action_get_navbar()];
            $this->render("infos_personne", $data);
        }
    }

    /**
     * Vérifie d'avoir un id dans l'url qui fait référence à la composante et renvoie la vue qui affiche les informations de la composante
     * @return void
     */
    public function action_infos_composante()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_GET['id'])) {
            $bd = Model::getModel();
            $infos = $bd->getInfosComposante(e($_GET['id']));
            $prestataires = $bd->getPrestatairesComposante(e($_GET['id']));
            $commerciaux = $bd->getCommerciauxComposante(e($_GET['id']));
            $interlocuteurs = $bd->getInterlocuteursComposante(e($_GET['id']));
            $bdl = $bd->getBdlComposante(e($_GET['id']));
            $data = ['infos' => $infos,
                'prestataires' => $prestataires,
                'commerciaux' => $commerciaux,
                'interlocuteurs' => $interlocuteurs,
                'bdl' => $bdl,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_composante', $data);
        }
    }

}
