<?php 
    session_start();

    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["nom"]) && isset($_POST["choixCouleur"])) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("update objectif set nom = :nom, couleur = :couleur, description = :desc where id = :id and pseudo = :pseudo;");
            $requete->bindValue(":nom", $_POST["nom"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->bindValue(":desc", $_POST["description"], PDO::PARAM_STR);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->execute();
    
            echo $_POST["id"];
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>