<?php
    session_start();

    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["question"]) && !empty($_POST["reponse"]) && isset($_POST["choixCouleur"])) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("update flashcard set question = :question, reponse = :reponse, couleur = :couleur where id_Flashcard = :id and pseudo = :pseudo;");
            $requete->bindValue(":question", $_POST["question"], PDO::PARAM_STR);
            $requete->bindValue(":reponse", $_POST["reponse"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->execute();
    
            echo "Success";
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>