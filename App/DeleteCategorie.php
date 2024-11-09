<?php
    session_start();

    if($_SESSION["gestion"] == 1) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("set foreign_key_checks = 0;");
            $requete->execute();

            $requete = $bdd->prepare("update listeflashcard set categorie = 'Toutes categories' where categorie = :categorie;");
            $requete->bindValue(":categorie", $_POST["categorie"], PDO::PARAM_STR);
            $requete->execute();

            $requete = $bdd->prepare("set foreign_key_checks = 1;");
            $requete->execute();

            $requete = $bdd->prepare("delete from categorie where nom = :categorie;");
            $requete->bindValue(":categorie", $_POST["categorie"], PDO::PARAM_STR);
            $requete->execute();
            
            echo $_POST["categorie"];
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>