<?php
    session_start();

    if($_SESSION["pseudo"] != "Guest") {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("delete from flashcard where id_ListeFlashcard = :id and pseudo = :pseudo;");
            $requete->bindValue(":id", $_POST["idFlashcard"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->execute();

            $requete = $bdd->prepare("delete from listeflashcard where id_ListeFlashcard = :id and pseudo = :pseudo;");
            $requete->bindValue(":id", $_POST["idFlashcard"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->execute();

            echo "Success";
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>