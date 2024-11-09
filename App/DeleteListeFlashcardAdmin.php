<?php 
    session_start();

    if($_SESSION["gestion"] == 1) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("delete from flashcard where id_ListeFlashcard = :id;");
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->execute();

            $requete = $bdd->prepare("delete from listeflashcard where id_ListeFlashcard = :id;");
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->execute();

            echo "Success";
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>