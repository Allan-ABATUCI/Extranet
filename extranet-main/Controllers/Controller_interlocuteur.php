<?php

class Controller_interlocuteur extends Controller
{
    /**
     * @inheritDoc
     */
    public function action_default()
    {
        $this->action_dashboard();
    }

    /**
     * Affiche le tableau de bord de l'interlocuteur client en récupérant les informations grâce à son id
     * @return void
     */
    public function action_dashboard()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['id'])) {
            $bd = Model::getModel();
            $bdlLink = '?controller=interlocuteur&action=mission_bdl';
            $headerDashboard = ['Nom projet/société', 'Date', 'Préstataire assigné', 'Bon de livraison'];
            $data = ['header' => $headerDashboard, 'menu' => $this->action_get_navbar(), 'bdlLink' => $bdlLink, 'dashboard' => $bd->getClientContactDashboardData()];
            return $this->render('interlocuteur', $data);
        } else {
            echo 'Une erreur est survenue lors du chargement du tableau de bord';
        }
    }

    /**
     * Action qui retourne les éléments du menu pour l'interlocuteur
     * @return array[]
     */
    public function action_get_navbar()
    {
        return [['link' => '?controller=interlocuteur&action=dashboard', 'name' => 'Mes prestataires']];
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
        $data = ['role' => 'interlocuteur', 'menu' => $this->action_get_navbar()];
        $this->render('infos', $data);
    }

    public function action_maj_infos()
    {
        maj_infos_personne(); // fonction dans Utils
        $this->action_infos();
    }

    /**
     * Met à jour les informations de l'utilisateur connecté
     * @return void
     */
    public function action_mission_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id']) && isset($_GET['id-prestataire'])){
            $cardLink = '?controller=interlocuteur&action=consulter_bdl';
            $data = ['title' => 'Bons de livraison', 'menu' => $this->action_get_navbar(), 'cardLink' => $cardLink, 'person' => $bd->getBdlsOfPrestataireByIdMission(e($_GET['id']), e($_GET['id-prestataire']))];
            $this->render('liste', $data);
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
            $typeBdl = $bd->getBdlTypeAndMonth($_GET['id']);
            if($typeBdl['type_bdl'] == 'Heure'){
                $activites = $bd->getAllNbHeureActivite($_GET['id']);
            }
            if($typeBdl['type_bdl'] == 'Demi-journée'){
                $activites = $bd->getAllDemiJourActivite($_GET['id']);
            }
            if($typeBdl['type_bdl'] == 'Journée'){
                $activites = $bd->getAllJourActivite($_GET['id']);
            }

            $data = ['bdl' => $typeBdl, 'menu' => $this->action_get_navbar(), 'activites' => $activites];
            $this->render("consulte_bdl", $data);
        } else {
            echo 'Une erreur est survenue lors du chargement de ce bon de livraison';
        }
    }

    /**
     * Met à jour la colonne valide de la table BON_DE_LIVRAISON pour indiquer que le bon de livraison est validé
     * @return void
     */
    public function action_valider_bdl(){
        $bd = Model::getModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if(isset($_GET['id']) && isset($_GET['valide'])){
            $bd->setEstValideBdl(e($_GET['id']), $_SESSION['id'], e($_GET['valide']));
            $this->action_consulter_bdl();
        }
        else {
            echo 'Une erreur est survenue lors de la validation de ce bon de livraison';
        }
    }


    /**
     * Envoie un email au(x) commercial/commerciaux assigné(s) à la mission de l'interlocuteur client
     * @return void
     */
    public function action_envoyer_email()
    {
        session_start();
        $bd = Model::getModel();
        if (isset($_SESSION['id']) && $bd->getComponentCommercialsEmails($_SESSION['id'])) {
            $destinatairesEmails = implode(', ', array_column($bd->getComponentCommercialsEmails($_SESSION['id']), 'email'));
            $emetteur = $_SESSION['email'];
            $objet = $_POST['objet'];
            $message = e($_POST['message']);

            //header pour l'envoie du mail
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: <' . $emetteur . '>' . "\r\n";

            if (mail($destinatairesEmails, $objet, $message, $headers)) {
                $this->render('message', [$title => 'Email', $message => 'Le mail a été envoyé !']);
            } else {
                $this->render('message', [$title => 'Email', $message => "Une erreur est survenue lors de l'envoie du mail !"]);
            }
        }
    }

    /**
     * Lecture du fichier correspondant au bon de livraison pour l'envoyer au client
     * @return void
     */
    public function telecharger_bdl()
    {
        $cheminFichier = $cheminBdl . e($_GET['id']);

        // Vérifiez si le fichier existe
        if (file_exists($cheminFichier)) {
            // Définir les en-têtes HTTP pour le téléchargement
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($cheminFichier) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($cheminFichier));

            // Lire et renvoyer le contenu du fichier
            readfile($cheminFichier);
            exit;
        } else {
            // Le fichier n'existe pas
            echo "Le fichier n'existe pas.";
        }
    }
}
