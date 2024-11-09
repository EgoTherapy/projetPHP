CREATE DATABASE IF NOT EXISTS projetTM CHARACTER SET = 'UTF8';

USE projetTM;

CREATE TABLE IF NOT EXISTS listeFlashcard(
id_ListeFlashcard int not null auto_increment,
nom varchar(80) not null,
couleur varchar(80) not null,
pseudo varchar(80) not null,
dateCreation date not null,
postee boolean not null,
categorie varchar(80) not null,
PRIMARY KEY(id_ListeFlashcard),
KEY(categorie, pseudo)
)ENGINE=INNODB;


CREATE TABLE IF NOT EXISTS flashcard(
id_Flashcard int not null auto_increment,
id_ListeFlashcard int not null,
pseudo varchar(80) not null,
question varchar(80) not null,
reponse varchar(80) not null,
couleur varchar(80) not null,
PRIMARY KEY(id_Flashcard),
KEY(id_ListeFlashcard, pseudo)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS utilisateur(
pseudo varchar(80) not null,
motDePasse varchar(100) not null,
niveauGestion tinyint not null,
PRIMARY KEY(pseudo)
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS objectif(
id int not null auto_increment,
pseudo varchar(80) not null,
nom varchar(100) not null,
dateObjectif date not null,
description varchar(250) not null,
couleur varchar(80) not null,
PRIMARY KEY(id),
KEY(pseudo)
)ENGINE=INNODB;



CREATE TABLE IF NOT EXISTS categorie(
nom varchar(80) not null,
PRIMARY KEY(nom)
)ENGINE=INNODB;

ALTER TABLE listeFlashcard ADD CONSTRAINT fkListeFlashcardCategorie FOREIGN KEY(categorie) REFERENCES categorie(nom),
ADD CONSTRAINT fkListeFlashcardUtilisateur FOREIGN KEY(pseudo) REFERENCES utilisateur(pseudo);

ALTER TABLE flashcard ADD CONSTRAINT fkFlashcardListeFlashCard FOREIGN KEY(id_ListeFlashcard) REFERENCES listeFlashCard(id_ListeFlashcard),
ADD CONSTRAINT fkFlashcardUtilisateur FOREIGN KEY(pseudo) REFERENCES utilisateur(pseudo);

ALTER TABLE objectif ADD CONSTRAINT fkObjectifUtilisateur FOREIGN KEY(pseudo) REFERENCES utilisateur(pseudo);


insert into categorie(nom) values("Toutes categories");