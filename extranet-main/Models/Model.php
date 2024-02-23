<?php

class Model
{
    /**
     * Attribut contenant l'instance PDO
     */
    private $bd;

    /**
     * Attribut statique qui contiendra l'unique instance de Model
     */
    private static $instance = null;

    /**
     * Constructeur : effectue la connexion à la base de données.
     */
    private function __construct()
    {
        include "credentials.php";
        $this->bd = new PDO($dsn, $login, $mdp);
        $this->bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->bd->query("SET NAMES 'utf8'");
    }

    /**
     * Méthode permettant de récupérer un modèle car le constructeur est privé (Implémentation du Design Pattern Singleton)
     */
    public static function getModel()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Méthode permettant d'insérer une ligne dans la table personne
     * @param $nom
     * @param $prenom
     * @param $email
     * @param $mdp
     * @return bool
     */
    public function createPersonne($nom, $prenom, $email, $mdp)
    {
        $req = $this->bd->prepare('INSERT INTO PERSONNE(nom, prenom, email, mdp) VALUES(:nom, :prenom, :email, :mdp);');
        $req->bindValue(':nom', $nom);
        $req->bindValue(':prenom', $prenom);
        $req->bindValue(':email', $email);
        $req->bindValue(':mdp', $mdp);
        $req->execute();
        return (bool)$req->rowCount();

    }

    /* -------------------------------------------------------------------------
                            Méthodes DashBoard
        ------------------------------------------------------------------------*/

    /**
     * Méthode permettant de récupérer toutes les informations des missions en fonction de la composante, la société et les prestataires assignés
     * @return array|false
     */
    public function getDashboardGestionnaire()
    {
        $req = $this->bd->prepare("SELECT 
        nom_client,
        nom_composante,
        nom,
        prenom
    FROM 
        personne 
    JOIN 
        prestataire pr ON id_prestataire = id_personne
    JOIN 
        bdl b USING(id_prestataire)
    JOIN 
        composante USING(id_composante)
    JOIN 
        client USING (id_client)
    ");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------------------
                         Méthodes getAll...
     ------------------------------------------------------------------------*/
    /**
     * Méthode permettant de récupérer la liste des composantes
     * @return array|false
     */
    public function getAllComposantes()
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client)');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste de tous les commerciaux
     * @return array|false
     */
    public function getAllCommerciaux()
    {
        $req = $this->bd->prepare('SELECT personne.id_personne AS id, nom, prenom, nom_composante FROM estdans JOIN composante USING(id_composante) JOIN personne USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste de tous les prestataires
     * @return array|false
     */
    public function getAllPrestataires()
    {
        $req = $this->bd->prepare('SELECT p.id_personne AS id, nom, prenom, interne FROM PERSONNE p JOIN PRESTATAIRE pr ON p.id_personne =  pr.id_personne;');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste de toutes les sociétés
     * @return array|false
     */
    public function getAllClients()
    {
        $req = $this->bd->prepare('SELECT id_client AS id, nom_client, telephone_client FROM CLIENT;');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste de tous les gestionnaires
     * @return array|false
     */
    public function getAllGestionnaires()
    {
        $req = $this->bd->prepare('SELECT id_personne AS id, nom, prenom FROM GESTIONNAIRE JOIN PERSONNE USING(id_personne);');
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer le nom, prenom et email d'une personne en fonction de son identifiant
     * @param $id
     * @return mixed
     */
    public function getInfosPersonne($id)
    {
        $req = $this->bd->prepare('SELECT id_personne, nom, prenom, email FROM PERSONNE WHERE id_personne = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /* -------------------------------------------------------------------------
                            Méthodes Composante
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant de récupérer l'id d'un composant à l'aide de son nom et la société à laquelle il appartient
     * @param $composante
     * @param $client
     * @return mixed
     */
    public function getIdComposante($composante, $client)
    {
        $req = $this->bd->prepare('SELECT id_composante FROM COMPOSANTE JOIN CLIENT USING(id_client)
                     WHERE nom_composante = :composante and nom_client = :client ');
        $req->bindValue(':client', $client);
        $req->bindValue(':composante', $composante);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode permettant de récupérer les informations d'une composante
     * @param $id
     * @return mixed
     */
    public function getInfosComposante($id)
    {
        $req = $this->bd->prepare('SELECT id_composante, nom_composante, nom_client, numero, nom_voie, cp, ville, libelle
       FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN ADRESSE USING(id_adresse) JOIN LOCALITE USING(id_localite) JOIN TYPEVOIE USING(id_type_voie) WHERE id_composante = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /**
     * Méthode permettant de récupérer la liste des prestataires d'une composante
     * @param $id
     * @return array|false
     */
    public function getPrestatairesComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN TRAVAILLEAVEC USING(id_personne) 
           JOIN MISSION USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des commerciaux d'une composante
     * @param $id
     * @return array|false
     */
    public function getCommerciauxComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN COMMERCIAL USING(id_personne) 
           JOIN ESTDANS USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des interlocuteurs d'une composante
     * @param $id
     * @return array|false
     */
    public function getInterlocuteursComposante($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) 
       WHERE id_composante = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer la liste des bons de livraison liés d'une composante
     * @param $id_composante
     * @return array|false
     */
    public function getBdlComposante($id_composante)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_prestataire, id_bdl, nom, prenom, mois
       FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) 
           JOIN BON_DE_LIVRAISON ON id_personne = id_prestataire 
           JOIN MISSION USING(id_mission)
       WHERE id_composante = :id');

        $req->bindValue(':id', $id_composante, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                                Méthodes Societe
       ------------------------------------------------------------------------*/
    /**
     * Méthode peremettant de récupérer la liste des interlocuteurs d'une société
     * @param $id
     * @return array|false
     */
    public function getInterlocuteursSociete($id)
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_personne, nom, prenom
       FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) 
           JOIN DIRIGE USING(id_personne) JOIN COMPOSANTE USING(id_composante) JOIN CLIENT using(id_client) WHERE id_client = :id');

        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Méthode permettant de récupérer les informations d'une société
     * @param $id
     * @return mixed
     */
    public function getInfosSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_client, nom_client, telephone_client FROM CLIENT WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall()[0];
    }

    /**
     * Méthode permettant de récupérer la liste des composantes d'une société
     * @param $id
     * @return array|false
     */
    public function getComposantesSociete($id)
    {
        $req = $this->bd->prepare('SELECT id_composante, nom_composante FROM COMPOSANTE JOIN CLIENT using(id_client) WHERE id_client = :id');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Méthodes assigner...
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant le nom de la composante et de la société
     * @param $composante
     * @param $client
     * @param $email
     * @return bool
     */
    public function assignerInterlocuteurComposante($composante, $client, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT cl ON c.id_client = cl.id_client WHERE c.nom_composante = :nom_compo  AND cl.nom_client = :nom_client)");
        $req->bindValue(':nom_compo', $composante, PDO::PARAM_STR);
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant l'identifiant de la composante
     * @param $id_composante
     * @param $email
     * @return bool
     */
    public function assignerInterlocuteurComposanteByIdComposante($id_composante, $email)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  (SELECT id_personne FROM PERSONNE WHERE email=:email), :id_composante");
        $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'assigner un interlocuteur à une composante en connaissant le nom de la composante et l'identifiant de la société
     * @param $id_client
     * @param $email
     * @param $composante
     * @return bool
     */
    public function assignerInterlocuteurComposanteByIdClient($id_client, $email, $composante)
    {
        $req = $this->bd->prepare("INSERT INTO dirige (id_personne, id_composante) SELECT  
                                                    (SELECT id_personne FROM PERSONNE WHERE email=:email), 
                                                    (SELECT id_composante FROM COMPOSANTE WHERE id_client = :id_client and nom_composante = :composante)");
        $req->bindValue(':composante', $composante);
        $req->bindValue(':id_client', $id_client, PDO::PARAM_INT);
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /* -------------------------------------------------------------------------
                                Méthodes add...
       ------------------------------------------------------------------------*/
    /**
     * Méthode permettant d'ajouter une personne dans la table prestataire en connaissant son email
     * @param $email
     * @return bool
     */
    public function addPrestataire($email)
    {
        $req = $this->bd->prepare("INSERT INTO PRESTATAIRE (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table interlocuteur en connaissant son email
     * @param $email
     * @return bool
     */
    public function addInterlocuteur($email)
    {
        $req = $this->bd->prepare("INSERT INTO INTERLOCUTEUR (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table commercial en connaissant son email
     * @param $email
     * @return bool
     */
    public function addCommercial($email)
    {
        $req = $this->bd->prepare("INSERT INTO COMMERCIAL (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une personne dans la table gestionnaire en connaissant son email
     * @param $email
     * @return bool
     */
    public function addGestionnaire($email)
    {
        $req = $this->bd->prepare("INSERT INTO GESTIONNAIRE (id_personne) SELECT id_personne FROM personne WHERE email = :email");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter un client dans la table client avec ses informations
     * @param $client
     * @param $tel
     * @return bool
     */
    public function addClient($client, $tel)
    {
        $req = $this->bd->prepare("INSERT INTO client(nom_client, telephone_client) VALUES( :nom_client, :tel)");
        $req->bindValue(':nom_client', $client, PDO::PARAM_STR);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une composante en ajoutant les informations de son adresse dans la table adresse puis les informations de la composante dans la table composante
     * @param $libelleVoie
     * @param $cp
     * @param $numVoie
     * @param $nomVoie
     * @param $nom_client
     * @param $nom_compo
     * @return bool
     */
    public function addComposante($libelleVoie, $cp, $numVoie, $nomVoie, $nom_client, $nom_compo)
    {
        $req = $this->bd->prepare("INSERT INTO ADRESSE(numero, nom_voie, id_type_voie, id_localite) SELECT :num, :nomVoie, (SELECT id_type_voie FROM TypeVoie WHERE libelle = :libelleVoie), (SELECT id_localite FROM localite WHERE cp = :cp)");
        $req->bindValue(':num', $numVoie, PDO::PARAM_STR);
        $req->bindValue(':nomVoie', $nomVoie, PDO::PARAM_STR);
        $req->bindValue(':libelleVoie', $libelleVoie, PDO::PARAM_STR);
        $req->bindValue(':cp', $cp, PDO::PARAM_STR);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO COMPOSANTE(nom_composante, id_adresse, id_client) SELECT :nom_compo, (SELECT id_adresse FROM adresse ORDER BY id_adresse DESC LIMIT 1), (SELECT id_client FROM CLIENT WHERE nom_client = :nom_client)");
        $req->bindValue(':nom_client', $nom_client, PDO::PARAM_STR);
        $req->bindValue(':nom_compo', $nom_compo, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une mission avec ses informations et les identifiants de la composante et de la société auxquelles elle est liée
     * @param $type
     * @param $nom
     * @param $date
     * @param $nom_compo
     * @param $nom_client
     * @return bool
     */
    public function addMission($type, $nom, $date, $nom_compo, $nom_client)
    {
        $req = $this->bd->prepare("INSERT INTO MISSION (type_bdl, nom_mission, date_debut, id_composante) SELECT :type, :nom, :date, (SELECT id_composante FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE LOWER(nom_client) = LOWER(:nom_client) and LOWER(nom_composante) = LOWER(:nom_composante))");
        $req->bindValue(':nom', $nom);
        $req->bindValue(':type', $type);
        $req->bindValue(':date', $date);
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Heure
     * @param $commentaire
     * @param $id_bdl
     * @param $id_personne
     * @param $date_bdl
     * @param $nb_heure
     * @return bool
     */
    public function addNbHeureActivite($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_heure)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO NB_HEURE SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_heure");
        $req->bindValue(':nb_heure', $nb_heure);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Demi-Journée
     * @param $commentaire
     * @param $id_bdl
     * @param $id_personne
     * @param $date_bdl
     * @param $nb_dj
     * @return bool
     */
    public function addDemiJournee($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_dj)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO DEMI_JOUR SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_dj");
        $req->bindValue(':nb_dj', $nb_dj);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter une activité en fonction de si il s'agit d'un bon de livraison de type Journée
     * @param $commentaire
     * @param $id_bdl
     * @param $id_personne
     * @param $date_bdl
     * @param $nb_jour
     * @return bool
     */
    public function addJourneeJour($commentaire, $id_bdl, $id_personne, $date_bdl, $nb_jour)
    {
        $req = $this->bd->prepare("INSERT INTO ACTIVITE (commentaire, id_bdl, id_personne, date_bdl) VALUES(:commentaire, :id_bdl, :id_personne, :date_bdl)");
        $req->bindValue(':commentaire', $commentaire);
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':id_personne', $id_personne, PDO::PARAM_INT);
        $req->bindValue(':date_bdl', $date_bdl);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO JOUR(id_activite, journee) SELECT (SELECT id_activite FROM activite ORDER BY id_activite DESC LIMIT 1), :nb_jour");
        $req->bindValue(':nb_jour', $nb_jour);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant d'ajouter un bon de livraison dans la table BON_DE_LIVRAISON avec seulement les informations comme le mois, la mission et le prestataire.
     * @param $nom_mission
     * @param $nom_composante
     * @param $mois
     * @param $id_prestataire
     * @return bool|void
     */
    public function addBdlInMission($nom_mission, $nom_composante, $mois, $id_prestataire)
    {
        try {
            $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(mois, id_mission, id_prestataire) SELECT :mois, 
                                                                               (SELECT id_mission FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :mission and nom_composante = :composante),
                                                                               :id_prestataire");
            $req->bindValue(':mission', $nom_mission);
            $req->bindValue(':composante', $nom_composante);
            $req->bindValue(':mois', $mois);
            $req->bindValue(':id_prestataire', $id_prestataire, PDO::PARAM_INT);
            $req->execute();
            return (bool)$req->rowCount();
        } catch (PDOException $e) {
            error_log('Erreur PHP : ' . $e->getMessage());
            echo 'Une des informations est mauvaise';
        }
    }

    /**
     * Méthode permettant d'assigner un prestataire à une mission et lui créée un bon de livraison
     * @param $email
     * @param $mission
     * @param $id_composante
     * @return bool
     */
    public function assignerPrestataire($email, $mission, $id_composante)
    {
        $req = $this->bd->prepare("INSERT INTO travailleAvec (id_personne, id_mission) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission and id_composante = :id_composante)");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
        $req->execute();
        $req = $this->bd->prepare("INSERT INTO BON_DE_LIVRAISON(id_prestataire, id_mission, mois)  SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email),  (SELECT m.id_mission FROM MISSION m JOIN COMPOSANTE USING(id_composante) WHERE nom_mission = :nom_mission and id_composante = :id_composante), (SELECT TO_CHAR(NOW(), 'YYYY-MM') AS date_format)");
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':nom_mission', $mission, PDO::PARAM_STR);
        $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /**
     * Méthode permettant
     * @param $email
     * @param $composante
     * @param $client
     * @return bool
     */
    public function assignerCommercial($email, $composante, $client)
    {
        $req = $this->bd->prepare("INSERT INTO estDans (id_personne, id_composante) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), (SELECT c.id_composante FROM COMPOSANTE c JOIN CLIENT USING(id_client) WHERE nom_composante = :composante AND nom_client = :client)");
        $req->bindValue(':email', $email);
        $req->bindValue(':composante', $composante);
        $req->bindValue(':client', $client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function assignerCommercialByIdComposante($email, $id_composante)
    {
        $req = $this->bd->prepare("INSERT INTO estDans (id_personne, id_composante) SELECT  (SELECT p.id_personne FROM PERSONNE p WHERE p.email = :email), :id_composante");
        $req->bindValue(':email', $email);
        $req->bindValue(':id_composante', $id_composante, PDO::PARAM_INT);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function getAllBdlPrestataire($id_pr)
    {
        $req = $this->bd->prepare("SELECT  anne,mois, nom_composante FROM bdl JOIN prestataire ON id_prestataire =:id");
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getAllNbHeureActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT nb_heure, a.commentaire, date_bdl FROM NB_HEURE JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function getAllDemiJourActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT nb_demi_journee, a.commentaire, date_bdl FROM DEMI_JOUR JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function getAllJourActivite($id_bdl)
    {
        $req = $this->bd->prepare("SELECT journee, a.commentaire, date_bdl FROM JOUR JOIN ACTIVITE a USING(id_activite) JOIN BON_DE_LIVRAISON using(id_bdl) WHERE id_bdl = :id_bdl ORDER BY date_bdl");
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function setEstValideBdl($id_bdl, $id_interlocuteur, $valide)
    {
        $req = $this->bd->prepare("UPDATE BON_DE_LIVRAISON SET est_valide = :valide, id_interlocuteur = :id_interlocuteur WHERE id_bdl = :id_bdl");
        $req->bindValue(':id_interlocuteur', $id_interlocuteur, PDO::PARAM_INT);
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':valide', $valide);
        $req->execute();
        return (bool)$req->rowCount();

    }

    public function setNomPersonne($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET nom = :nom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setPrenomPersonne($id, $prenom)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET prenom = :prenom WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setEmailPersonne($id, $email)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET email = :email WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setMdpPersonne($id, $mdp)
    {
        $req = $this->bd->prepare("UPDATE PERSONNE SET mdp = :mdp WHERE id_personne = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':mdp', $mdp, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomClient($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET nom_client = :nom WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setTelClient($id, $tel)
    {
        $req = $this->bd->prepare("UPDATE CLIENT SET telephone_client = :tel WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':tel', $tel, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomComposante($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE COMPOSANTE SET nom_composante = :nom WHERE id_composante = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNumeroAdresse($id, $num)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET numero = :num WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':num', $num);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setNomVoieAdresse($id, $nom)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET nom_voie = :nom WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':nom', $nom);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setCpLocalite($id, $cp)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE cp = :cp)
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':cp', $cp);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setVilleLocalite($id, $ville)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_localite = (SELECT id_localite FROM LOCALITE WHERE LOWER(ville) = LOWER(:ville))
               WHERE id_adresse = (SELECT id_adresse FROM ADRESSE JOIN COMPOSANTE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':ville', $ville);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setLibelleTypevoie($id, $libelle)
    {
        $req = $this->bd->prepare("UPDATE ADRESSE SET id_type_voie = (SELECT id_type_voie FROM TYPEVOIE WHERE LOWER(libelle) = LOWER(:libelle))
               WHERE id_adresse = (SELECT id_adresse FROM COMPOSANTE JOIN ADRESSE USING(id_adresse) WHERE id_composante = :id)");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':libelle', $libelle);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setClientComposante($id, $client)
    {
        $req = $this->bd->prepare("UPDATE COMPOSANTE SET id_client = (SELECT id_client FROM CLIENT WHERE LOWER(nom_client) = LOWER(:client))
                  WHERE id_composante = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':client', $client);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setCommentaireActivite($id, $commentaire)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET commentaire = :commentaire WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDateBdlActivite($id, $date)
    {
        $req = $this->bd->prepare("UPDATE ACTIVITE SET date_bdl = :date WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':date', $date, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }


    public function setNbHeure($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE NB_HEURE SET nb_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDebutHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET debut_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setFinHeurePlageHoraire($id, $heure)
    {
        $req = $this->bd->prepare("UPDATE PLAGE_HORAIRE SET fin_heure = :heure WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':heure', $heure, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDemiJournee($id, $demi_journee)
    {
        $req = $this->bd->prepare("UPDATE DEMI_JOUR SET nb_demi_journee = :dj WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':dj', $demi_journee);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setJourneeJour($id, $jour)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET journee = :jour WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':jour', $jour, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setDebutHeureSuppJour($id, $debut)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET debut_heure_supp = :debut WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':debut', $debut, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    public function setFinHeureSuppJour($id, $fin)
    {
        $req = $this->bd->prepare("UPDATE JOUR SET fin_heure_supp = :fin WHERE id_activite = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->bindValue(':fin', $fin, PDO::PARAM_STR);
        $req->execute();
        return (bool)$req->rowCount();
    }

    /* -------------------------------------------------------------------------
                            Fonction Commercial
        ------------------------------------------------------------------------*/

    public function getDashboardCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante, nom, prenom, id_prestataire 
        FROM affecte
        JOIN composante c USING(id_composante) 
        JOIN bdl on id_prestataire = ta.id_personne
        JOIN PERSONNE p ON ta.id_personne = p.id_personne WHERE ed.id_personne=:id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function getDashboardPrestataire($id_prestataire)
    {
        $req = $this->bd->prepare('SELECT nom_client, nom_composante,id_composante 
        FROM client 
        JOIN composante USING(id_client) 
        JOIN bdl USING (id_composante) WHERE id_prestataire=:id');
        $req->bindValue(':id', $id_prestataire, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function getInterlocuteurForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante FROM represente JOIN composante USING(id_composante) JOIN client USING(id_client) JOIN personne USING(id_personne) WHERE id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getPrestataireForCommercial($id_co)
    {
        $req = $this->bd->prepare('SELECT DISTINCT nom, prenom, ta.id_personne as id FROM client JOIN composante USING(id_client) JOIN mission USING(id_composante) JOIN travailleavec ta USING(id_mission) JOIN PERSONNE p ON ta.id_personne = p.id_personne WHERE ed.id_personne = :id');
        $req->bindValue(':id', $id_co, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getComposantesForCommercial($id_commercial)
    {
        $req = $this->bd->prepare('SELECT id_composante AS id, nom_composante, nom_client FROM CLIENT JOIN COMPOSANTE using(id_client) JOIN estDans USING(id_composante) WHERE id_personne = :id');
        $req->bindValue(':id', $id_commercial, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall(PDO::FETCH_ASSOC);
    }

    public function getBdlTypeAndMonth($id_bdl)
    {
        $req = $this->bd->prepare("SELECT id_bdl, type_bdl, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) WHERE id_bdl = :id");
        $req->bindValue(':id', $id_bdl, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch();
    }

    public function getBdlsOfPrestataireByIdMission($id_mission, $id_prestataire)
    {
        $req = $this->bd->prepare("SELECT id_bdl, nom_mission, mois FROM BON_DE_LIVRAISON JOIN MISSION USING(id_mission) JOIN travailleavec USING(id_mission) WHERE id_mission = :id_mission and travailleavec.id_personne = :id_prestataire"
    );
        $req->bindValue(':id_mission', $id_mission, PDO::PARAM_INT);
        $req->bindValue(':id_prestataire', $id_prestataire, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIdActivite($date_activite, $id_bdl)
    {
        $req = $this->bd->prepare('SELECT id_activite FROM activite WHERE id_bdl = :id_bdl and date_bdl = :date');
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':date', $date_activite);
        $req->execute();
        return $req->fetch()[0];
    }

    /* -------------------------------------------------------------------------
                        Fonction Interlocuteur
    ------------------------------------------------------------------------*/

    public function dashboardInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT nom_mission, date_debut, nom, prenom, id_bdl FROM mission m JOIN travailleAvec USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    public function getEmailCommercialForInterlocuteur($id_in)
    {
        $req = $this->bd->prepare("SELECT email FROM dirige d JOIN estDans ed USING(id_composante) JOIN personne com ON ed.id_personne = com.id_personne WHERE d.id_personne = :id");
        $req->bindValue(':id', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Récupère les informations de l'interlocuteur client par rapport à sa mission
     * @return array|false
     */
    public function getClientContactDashboardData()
    {
        $req = $this->bd->prepare('SELECT nom_mission, date_debut, nom, prenom, id_bdl, ta.id_mission, ta.id_personne as id_prestataire FROM mission m JOIN travailleAvec ta USING(id_mission) JOIN personne p USING(id_personne) JOIN bon_de_livraison bdl ON m.id_mission= bdl.id_mission WHERE bdl.id_interlocuteur = :id;');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClientForCommercial()
    {
        $req = $this->bd->prepare('SELECT DISTINCT id_client AS id, nom_client, telephone_client FROM CLIENT JOIN COMPOSANTE USING(id_client) JOIN ESTDANS USING(id_composante) WHERE id_personne = :id;');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /**
     * Renvoie la liste des emails des commerciaux assignées à la mission de l'interlocuteur client
     * @param $idClientContact
     * @return void
     */
    public function getComponentCommercialsEmails($idClientContact)
    {
        $req = $this->bd->prepare('SELECT email FROM represente JOIN personne USING(id_interlocuteur) WHERE id_interlocuteur = :id');
        $req->bindValue(':id', $idClientContact, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère le mail dans la base de données grâce à l'identifiant de la personne
     * @param $id
     * @return void
     */
    function getEmailById($id)
    {
        $req = $this->bd->prepare('SELECT email FROM personne WHERE id_personne = :id;');
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode permettant de vérifier que le mail saisi existe bien.
     * @param $mail
     * @return integer
     **/
    public function mailExists($mail)
    {
        $req = $this->bd->prepare('SELECT email FROM PERSONNE WHERE email = :mail;');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $email = $req->fetch(PDO::FETCH_ASSOC);
        return sizeof($email) != 0;
    }

    public function getBdlPrestaForInterlocuteur($id_pr, $id_in)
    {
        $req = $this->bd->prepare("SELECT id_bdl, mois, nom_mission FROM BON_DE_LIVRAISON bdl JOIN MISSION m USING(id_mission) JOIN travailleAvec ta USING(id_mission) JOIN COMPOSANTE USING(id_composante) JOIN dirige d USING(id_composante) WHERE ta.id_personne = :id_pres AND d.id_personne = :id_inter");
        $req->bindValue(':id_inter', $id_pr, PDO::PARAM_INT);
        $req->bindValue(':id_pres', $id_in, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            Fonction Prestataire
        ------------------------------------------------------------------------*/

    public function getInterlocuteurForPrestataire($id_pr)
    {
        $req = $this->bd->prepare('SELECT nom, prenom, nom_client, nom_composante 
        FROM client 
        JOIN composante USING(id_client) 
        JOIN represente USING(id_interlocuteur) 
        JOIN bdl USING (id_interlocuteur)
        JOIN prestataire WHERE id_prestataire= :id');
        $req->bindValue(':id', $id_pr, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchall();
    }

    /* -------------------------------------------------------------------------
                            AUTRE
        ------------------------------------------------------------------------*/
    /**
     * Vérifie que le mot de passe correspond bien au mail. Si ils correspondent, une session avec les informations de la personne lié au mail débute.
     **/
    public function checkMailPassword($mail, $password)
    {
        $req = $this->bd->prepare('SELECT * FROM PERSONNE WHERE email = :mail');
        $req->bindValue(':mail', $mail);
        $req->execute();
        $realPassword = $req->fetchAll(PDO::FETCH_ASSOC);

        if ($realPassword) {
            if ($realPassword[0]['mot_de_passe'] == $password) {
                if (isset($_SESSION)) {
                    session_destroy();
                }
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['id'])) {
                    unset($_SESSION['id']);
                }
                $_SESSION['id'] = $realPassword[0]['id_personne'];
                $_SESSION['nom'] = $realPassword[0]['nom'];
                $_SESSION['prenom'] = $realPassword[0]['prenom'];
                $_SESSION['email'] = $realPassword[0]['email'];
                return true;
            }
        }
        return false;
    }

    /**
     * Méthode vérifiant les rôles de la personne. Si il n'y a qu'un seul rôle elle retourne simplement le nom de ce rôle. Si il y a plusieurs rôles, une liste des rôles sous forme de tableau.
     **/
    public function hasSeveralRoles()
    {
        $roles = [];
        $req = $this->bd->prepare('SELECT * FROM PRESTATAIRE WHERE id_prestataire = :id');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'prestataire';
        }

        $req = $this->bd->prepare('SELECT * FROM GESTIONNAIRE WHERE id_gestionnaire = :id');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'gestionnaire';
        }

        $req = $this->bd->prepare('SELECT * FROM COMMERCIAL WHERE id_commercial = :id');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'commercial';
        }

        $req = $this->bd->prepare('SELECT * FROM INTERLOCUTEUR WHERE id_interlocuteur = :id');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'interlocuteur';
        }

        $req = $this->bd->prepare('SELECT * FROM PERSONNE WHERE id_personne = :id AND :id=0');
        $req->bindValue(':id', $_SESSION['id'], PDO::PARAM_INT);
        $req->execute();
        if ($req->fetch(PDO::FETCH_ASSOC)) {
            $roles[] = 'administrateur';
        }

        if (sizeof($roles) > 1) {
            return ['roles' => $roles];
        }

        return $roles[0];
    }

    public function checkPersonneExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE WHERE email = :email) AS personne_existe;');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkComposanteExiste($nom_compo, $nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM COMPOSANTE JOIN CLIENT USING(id_client) WHERE nom_composante = :nom_composante AND nom_client = :nom_client) AS composante_existe');
        $req->bindValue(':nom_composante', $nom_compo);
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkSocieteExiste($nom_client)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM CLIENT WHERE nom_client = :nom_client) AS client_existe');
        $req->bindValue(':nom_client', $nom_client);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkMissionExiste($nom_mission, $nom_compo)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM MISSION JOIN COMPOSANTE USING(id_composante) WHERE nom_composante = :nom_compo AND nom_mission = :nom_mission) AS mission_existe');
        $req->bindValue(':nom_compo', $nom_compo);
        $req->bindValue(':nom_mission', $nom_mission);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkInterlocuteurExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN INTERLOCUTEUR USING(id_personne) WHERE email = :email) AS interlocuteur_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkCommercialExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN COMMERCIAL USING(id_personne) WHERE email = :email) AS commercial_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkPrestataireExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN PRESTATAIRE USING(id_personne) WHERE email = :email) AS prestataire_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkGestionnaireExiste($email)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM PERSONNE JOIN GESTIONNAIRE USING(id_personne) WHERE email = :email) AS gestionnaire_existe');
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetch()[0] == 't';
    }

    public function checkActiviteExiste($id_bdl, $date_activite)
    {
        $req = $this->bd->prepare('SELECT EXISTS (SELECT 1 FROM ACTIVITE WHERE id_bdl = :id_bdl and date_bdl = :date_activite)');
        $req->bindValue(':id_bdl', $id_bdl, PDO::PARAM_INT);
        $req->bindValue(':date_activite', $date_activite);
        $req->execute();
        return $req->fetch()[0] == 't';
    }
}