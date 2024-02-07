<?php

class Controller_administrateur extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Renvoie le tableau de bord de l'administrateur avec les variables adéquates
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
        $_SESSION['role'] = 'administrateur';
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $bdlLink = '?controller=administrateur&action=mission_bdl';
            $buttonLink = '?controller=administrateur&action=ajout_mission_form';
            $headerDashboard = ['Société', 'Composante', 'Nom Mission', 'Préstataire assigné', 'Bon de livraison'];
            $data = ['menu' => $this->action_get_navbar(), 'bdlLink' => $bdlLink, 'buttonLink' => $buttonLink, 'header' => $headerDashboard, 'dashboard' => $bd->getDashboardGestionnaire()];
            return $this->render('gestionnaire_missions', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour le gestionnaire
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [['link' => '?controller=administrateur&action=clients', 'name' => 'Société'],
            ['link' => '?controller=administrateur&action=composantes', 'name' => 'Composantes'],
            ['link' => '?controller=administrateur&action=missions', 'name' => 'Missions'],
            ['link' => '?controller=administrateur&action=prestataires', 'name' => 'Prestataires'],
            ['link' => '?controller=administrateur&action=commerciaux', 'name' => 'Commerciaux'],
            ['link' => '?controller=administrateur&action=gestionnaires', 'name' => 'Gestionnaires']];
    }

    /**
     * Renvoie la liste de tous les gestionnaires
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_gestionnaires(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=administrateur&action=ajout_gestionnaire_form';
            $cardLink = '?controller=administrateur&action=infos_personne';
            $data = ['title' => 'Gestionnaires', 'cardLink' => $cardLink, 'buttonLink' => $buttonLink, "person" => $bd->getAllGestionnaires(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
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
            $buttonLink = '?controller=administrateur&action=ajout_client_form';
            $cardLink = '?controller=administrateur&action=infos_client';
            $data = ['title' => 'Société', 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'person' => $bd->getAllClients(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de tous les prestataires
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_prestataires()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=administrateur&action=ajout_prestataire_form';
            $cardLink = '?controller=administrateur&action=infos_personne';
            $data = ['title' => 'Prestataires', 'cardLink' => $cardLink, "buttonLink" => $buttonLink, "person" => $bd->getAllPrestataires(), 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
    }

    /**
     * Renvoie la liste de tous les commerciaux
     * La vérification de l'identifiant de Session permet de s'assurer que la personne est connectée en faisant partie de la base de données
     * @return void
     */
    public function action_commerciaux()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $buttonLink = '?controller=administrateur&action=ajout_commercial_form';
            $cardLink = '?controller=administrateur&action=infos_personne';
            $data = ['title' => 'Commerciaux', 'cardLink' => $cardLink, 'buttonLink' => $buttonLink, "person" => $bd->getAllCommerciaux(), 'menu' => $this->action_get_navbar()];
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
            $buttonLink = '?controller=administrateur&action=ajout_composante_form';
            $title = 'Composantes';
            $cardLink = '?controller=administrateur&action=infos_composante';
            $data = ['title' => $title, 'person' => $bd->getAllComposantes(), 'buttonLink' => $buttonLink, 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar()];
            $this->render("liste", $data);
        }
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
            $cardLink = '?controller=administrateur&action=consulter_bdl';
            $data = ['title' => 'Bons de livraison', 'cardLink' => $cardLink, 'menu' => $this->action_get_navbar(), 'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), e($_GET['id-prestataire']))];
            $this->render('liste', $data);
        }
        $this->action_dashboard();
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Fonctions de mise à jour                              */
    /*--------------------------------------------------------------------------------------*/

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
            $data = ['person' => $bd->getInfosPersonne($_GET['id']), 'menu' => $this->action_get_navbar()];
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
            $infos = $bd->getInfosComposante($_GET['id']);
            $prestataires = $bd->getPrestatairesComposante(e($_GET['id']));
            $commerciaux = $bd->getCommerciauxComposante(e($_GET['id']));
            $interlocuteurs = $bd->getInterlocuteursComposante(e($_GET['id']));
            $bdl = $bd->getBdlComposante(e($_GET['id']));
            $cardLink = '?controller=administrateur';
            $data = ['infos' => $infos,
                'prestataires' => $prestataires,
                'commerciaux' => $commerciaux,
                'interlocuteurs' => $interlocuteurs,
                'bdl' => $bdl,
                'cardLink' => $cardLink,
                'menu' => $this->action_get_navbar()];
            $this->render('infos_composante', $data);
        }
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

    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires pour assigner                             */
    /*--------------------------------------------------------------------------------------*/
    /**
     * Vérifie d'avoir les informations nécessaire à l'assignation d'un prestataire dans une mission
     * @return void
     */
    public function action_assigner_prestataire()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $bd = Model::getModel();
        if (isset($_POST['email'])) {
            $bd->assignerPrestataire(e($_POST['email']), e($_POST['mission']));
        }
        $this->action_dashboard();
    }

    public function action_assigner_commercial_interlocuteur()
    {
        $bd = Model::getModel();
        if (isset($_POST['email']) && isset($_POST['client'])) {
            $bd->assignerCommercial(e($_POST['email']), e($_POST['client']));
        }
    }

    /*--------------------------------------------------------------------------------------*/
    /*                                Formulaires d'ajout                                  */
    /*--------------------------------------------------------------------------------------*/
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
     * Renvoie la vue du formulaire pour l'ajout d'une composante
     * @return void
     */
    public function action_ajout_composante_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_composante', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un prestataire
     * @return void
     */
    public function action_ajout_prestataire_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_prestataire', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'une mission
     * @return void
     */
    public function action_ajout_mission_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_mission', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un client
     * @return void
     */
    public function action_ajout_client_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_client', $data);
    }

    /**
     * Renvoie la vue du formulaire pour l'ajout d'un commercial
     * @return void
     */
    public function action_ajout_commercial_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_commercial', $data);
    }
    public function action_ajout_gestionnaire_form()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $data = ['menu' => $this->action_get_navbar()];
        $this->render('ajout_gestionnaire', $data);
    }

    /**
     * Vérifie qu'il y'a toutes les informations nécessaire pour l'ajout d'un(e) client/société
     * @return void
     */
    public function action_ajout_client()
    {
        $bd = Model::getModel();
        if (isset($_POST['client']) &&
            isset($_POST['tel']) &&
            isset($_POST['mission']) &&
            isset($_POST['type-bdl']) &&
            isset($_POST['date-mission']) &&
            isset($_POST['composante']) &&
            isset($_POST['numero-voie']) &&
            isset($_POST['type-voie']) &&
            isset($_POST['nom-voie']) &&
            isset($_POST['cp']) &&
            isset($_POST['ville']) &&
            isset($_POST['prenom-interlocuteur']) &&
            isset($_POST['nom-interlocuteur']) &&
            isset($_POST['email-interlocuteur']) &&
            isset($_POST['prenom-commercial']) &&
            isset($_POST['nom-commercial']) &&
            isset($_POST['email-commercial']) &&
            !$bd->checkSocieteExiste(e($_POST['client']))) {

            $bd->addClient(e($_POST['client']), e($_POST['tel']));
            $this->action_ajout_composante();
        }
        $this->action_ajout_client_form();
    }

    /**
     * Vérifie d'avoir les informations nécessaire et que le commercial n'existe pas en tant que personne et commercial avant de l'ajouter
     * @return void
     */
    public function action_ajout_commercial()
    {
        $bd = Model::getModel();
        if (isset($_POST['email-commercial']) && !$bd->checkCommercialExiste(e($_POST['email-commercial']))) {
            $bd->addCommercial(e($_POST['email-commercial']));
        }
    }

    /**
     * Vérfie d'avoir toutes les informations nécessaire et que la personne n'existe pas avant la création d'un gestionnaire.
     * @return void
     */
    public function action_ajout_gestionnaire()
    {
        $bd = Model::getModel();
        if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email-gestionnaire'])) {
            $this->action_ajout_personne(e($_POST['nom']), e($_POST['prenom']), e($_POST['email-gestionnaire']));
            $bd->addGestionnaire(e($_POST['email-gestionnaire']));
            $this->action_gestionnaires();
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
                $this->action_ajout_personne(e($_POST['nom-interlocuteur']), e($_POST['prenom-interlocuteur']), e($_POST['email-interlocuteur']));
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
        if (isset($_POST['client']) && isset($_POST['composante'])){
            $id = $bd->getIdComposante(e($_POST['composante']), e($_POST['client']));
            $bd->assignerInterlocuteurComposanteByIdComposante($id['id_composante'], e($_POST['email-interlocuteur']));
        }
    }

    /**
     * Fonction qui créée une composante, son interlocuteur, commercial et les assigne a elle, et créée la mission.
     * @return void
     */
    public function action_ajout_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) &&
            isset($_POST['numero-voie']) &&
            isset($_POST['type-voie']) &&
            isset($_POST['nom-voie']) &&
            isset($_POST['cp']) &&
            isset($_POST['ville']) &&
            !$bd->checkComposanteExiste(e($_POST['composante']), e($_POST['client']))) {
            $bd->addComposante(e($_POST['type-voie']),
                e($_POST['cp']),
                e($_POST['numero-voie']),
                e($_POST['nom-voie']),
                e($_POST['client']),
                e($_POST['composante']));
            $this->action_ajout_interlocuteur_dans_composante();
            $this->action_ajout_commercial_dans_composante();
            $this->action_ajout_mission();
        }
        if($_POST['tel']){
            $this->action_ajout_client_form();
        }
        else{
            $this->action_ajout_composante_form();
        }
    }

    /**
     * Vérifie que la mission n'existe pas pour ensuite la créer
     * @return void
     */
    public function action_ajout_mission()
    {
        $bd = Model::getModel();
        if (!$bd->checkMissionExiste(e($_POST['mission']), e($_POST['composante']))) {
            $bd->addMission(e($_POST['type-bdl']),
                e($_POST['mission']),
                e($_POST['date-mission']),
                e($_POST['composante']),
                e($_POST['client']));
        }
    }

    /**
     * Vérifie d'avoir toutes les informations d'un prestataire pour ensuite créer la personne et l'ajouter en tant que prestataire
     * @return void
     */
    public function action_ajout_prestataire(){
        $bd = Model::getModel();
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email-prestataire'])){
            $this->action_ajout_personne(e($_POST['nom']), e($_POST['prenom']), e($_POST['email-prestataire']));
            $bd->addPrestataire(e($_POST['email-prestataire']));
        }
    }

    /**
     * Vérifie d'avoir les informations nécessaires pour l'ajout d'un prestataire dans une misison
     * @return void
     */
    public function action_ajout_prestataire_dans_mission()
    {
        $bd = Model::getModel();
        if (isset($_POST['mission']) && isset($_POST['email-prestataire']) && $_GET['id'] && $bd->checkPrestataireExiste(e($_POST['email-prestataire']))) {
            $bd->assignerPrestataire(e($_POST['email-prestataire']), e($_POST['mission']), e($_GET['id']));
        }
        $this->ajout_prestataire_form();
    }

    /**
     * Vérifie d'avoir les informations nécessaires pour l'ajout d'un commercial dans une composante
     * @return void
     */
    public function action_ajout_commercial_dans_composante()
    {
        $bd = Model::getModel();
        if (isset($_POST['composante']) && isset($_POST['email-commercial']) && isset($_POST['client'])) {
            $this->action_ajout_commercial();
            $bd->assignerCommercial(e($_POST['email-commercial']), e($_POST['composante']), e($_POST['client']));
        } elseif (isset($_POST['email-commercial']) && isset($_GET['id-composante'])) {
            $this->action_ajout_commercial();
            $bd->assignerCommercialByIdComposante(e($_POST['email-commercial']), e($_GET['id-composante']));
            $this->action_ajout_commercial_form();
        }
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
}

?>
