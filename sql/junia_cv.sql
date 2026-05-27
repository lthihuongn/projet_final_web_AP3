CREATE TABLE role(
   ID_role VARCHAR(50),
   label VARCHAR(50),
   PRIMARY KEY(ID_role)
);

CREATE TABLE étudiant(
   ID_etudiant VARCHAR(50),
   role VARCHAR(50) NOT NULL,
   mail VARCHAR(50) NOT NULL,
   Nom VARCHAR(50),
   Prénom VARCHAR(50),
   date_naissance DATE,
   telephone INT,
   adresse TEXT,
   photo VARCHAR(50),
   biographie TEXT,
   ID_role VARCHAR(50) NOT NULL,
   PRIMARY KEY(ID_etudiant),
   FOREIGN KEY(ID_role) REFERENCES role(ID_role)
);

CREATE TABLE entreprise(
   ID_entreprise VARCHAR(50),
   role VARCHAR(50) NOT NULL,
   mail VARCHAR(50) NOT NULL,
   Nom VARCHAR(50),
   telephone INT,
   adresse TEXT,
   photo VARCHAR(50),
   biographie TEXT,
   secteur VARCHAR(50),
   ID_role VARCHAR(50) NOT NULL,
   PRIMARY KEY(ID_entreprise),
   FOREIGN KEY(ID_role) REFERENCES role(ID_role)
);

CREATE TABLE administrateur(
   ID_administrateur VARCHAR(50),
   role VARCHAR(50) NOT NULL,
   mail VARCHAR(50) NOT NULL,
   Nom VARCHAR(50),
   ID_role VARCHAR(50) NOT NULL,
   PRIMARY KEY(ID_administrateur),
   FOREIGN KEY(ID_role) REFERENCES role(ID_role)
);

CREATE TABLE compétence(
   ID_comptence VARCHAR(50),
   label VARCHAR(50),
   PRIMARY KEY(ID_comptence)
);

CREATE TABLE type_de_contrat(
   ID_type_de_contrat VARCHAR(50),
   label VARCHAR(50),
   PRIMARY KEY(ID_type_de_contrat)
);

CREATE TABLE ecole(
   ID_ecole TEXT,
   label VARCHAR(50),
   PRIMARY KEY(ID_ecole)
);

CREATE TABLE Domaine_recherche(
   Domaine_recherche TEXT,
   label VARCHAR(50),
   PRIMARY KEY(Domaine_recherche)
);

CREATE TABLE cv_(
   ID_cv VARCHAR(50),
   ID_etudiant VARCHAR(50),
   titre VARCHAR(50),
   description VARCHAR(50),
   photo VARCHAR(50),
   nom VARCHAR(50),
   prénom VARCHAR(50),
   date_naissance VARCHAR(50),
   mail VARCHAR(50),
   Biographie VARCHAR(50),
   expérience_pro VARCHAR(50),
   ID_ecole TEXT NOT NULL,
   ID_type_de_contrat VARCHAR(50) NOT NULL,
   ID_etudiant_1 VARCHAR(50) NOT NULL,
   PRIMARY KEY(ID_cv),
   UNIQUE(ID_etudiant_1),
   FOREIGN KEY(ID_ecole) REFERENCES ecole(ID_ecole),
   FOREIGN KEY(ID_type_de_contrat) REFERENCES type_de_contrat(ID_type_de_contrat),
   FOREIGN KEY(ID_etudiant_1) REFERENCES étudiant(ID_etudiant)
);

CREATE TABLE Asso_7(
   ID_cv VARCHAR(50),
   Domaine_recherche TEXT,
   PRIMARY KEY(ID_cv, Domaine_recherche),
   FOREIGN KEY(ID_cv) REFERENCES cv_(ID_cv),
   FOREIGN KEY(Domaine_recherche) REFERENCES Domaine_recherche(Domaine_recherche)
);

CREATE TABLE Asso_8(
   ID_cv VARCHAR(50),
   ID_comptence VARCHAR(50),
   PRIMARY KEY(ID_cv, ID_comptence),
   FOREIGN KEY(ID_cv) REFERENCES cv_(ID_cv),
   FOREIGN KEY(ID_comptence) REFERENCES compétence(ID_comptence)
);
