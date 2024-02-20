INSERT INTO personne (id_personne, nom, prenom, email, mot_de_passe, telephone) VALUES
(0,'Ellouze', 'Slim', 'slim.ellouze@performvision.fr', 'mdp123', '0123456789'); --id0 =admin

-- Peuplement de la table localite
INSERT INTO localite (cp, ville) VALUES
('12345', 'Ville A'),
('23456', 'Ville B'),
('34567', 'Ville C');

-- Peuplement de la table type_voie
INSERT INTO type_voie (libelle) VALUES
('Rue'),
('Avenue'),
('Boulevard');

-- Peuplement de la table type
INSERT INTO type (libelle) VALUES
('Type A'),
('Type B'),
('Type C');

-- Peuplement de la table personne
INSERT INTO personne (nom, prenom, email, mot_de_passe, telephone) VALUES
('Dupont', 'Jean', 'jean.dupont@example.com', 'mdp123', '0123456789'),
('Martin', 'Sophie', 'sophie.martin@example.com', 'pass456', '9876543210'),
('Dubois', 'Pierre', 'pierre.dubois@example.com', 'password', '1234567890');

-- Peuplement de la table client
INSERT INTO client (nom_client, tel_client) VALUES
('Client A', '1111111111'),
('Client B', '2222222222'),
('Client C', '3333333333');

-- Peuplement de la table commercial
INSERT INTO commercial (id_commercial) VALUES
(1),
(2),
(3);

-- Peuplement de la table adresse
INSERT INTO adresse (numero, nom_voie, id_typevoie, id_localite) VALUES
(123, 'Rue de la Liberté', 1, 1),
(456, 'Avenue des Fleurs', 2, 2),
(789, 'Boulevard Voltaire', 3, 3);

-- Peuplement de la table composante
INSERT INTO composante (nom_composante, id_client, id_adresse) VALUES
('Composante A', 1, 1),
('Composante B', 2, 2),
('Composante C', 3, 3);

-- Peuplement de la table prestataire
INSERT INTO prestataire (id_prestataire) VALUES
(1),
(2),
(3);

-- Peuplement de la table gestionnaire
INSERT INTO gestionnaire (id_gestionnaire) VALUES
(1),
(2),
(3);

INSERT INTO interlocuteur (id_interlocuteur) VALUES
(1),
(2),
(3);

INSERT INTO bdl (id_composante, id_gestionnaire, annee, mois, signature_interlocuteur, signature_prestataire, commentaire, id_prestataire, id_interlocuteur)
VALUES 
(1, 1, 2023, 1, 'John Doe', 'ABC Inc.', 'This is a comment.', 1, 1),
(2, 2, 2023, 2, 'Jane Smith', 'XYZ Corp.', 'Another comment.', 2, 2),
(3, 3, 2023, 3, 'Bob Johnson', '123 Company', 'Yet another comment.', 3,3);
