<?php 
    session_start();

    if($_SESSION["pseudo"] != "Guest") {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("delete from objectif where id = :id and pseudo = :pseudo;");
            $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->execute();

            echo "Success";
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>