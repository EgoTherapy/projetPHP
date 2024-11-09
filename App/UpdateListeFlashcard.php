<?php
    session_start();

    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["nom"]) && isset($_POST["choixCouleur"])) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("update listeflashcard set nom = :nom, couleur = :couleur, postee = :postee, categorie = :categorie where id_Listeflashcard = :id and pseudo = :pseudo;");
            $requete->bindValue(":nom", $_POST["nom"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            if(isset($_POST["postee"])) {
                $requete->bindValue(":postee", 1, PDO::PARAM_INT);
            } else {
                $requete->bindValue(":postee", 0, PDO::PARAM_INT);
            }
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->bindValue(":categorie", $_POST["categorie"], PDO::PARAM_STR);
            $requete->execute();
    
            echo $_POST["id"];
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>