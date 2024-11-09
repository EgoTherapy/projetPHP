<?php
    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from listeflashcard where pseudo = :user;");
        $requete->bindValue(":user", $_SESSION["pseudo"], PDO::PARAM_STR);
        $requete->execute();

        $listeFlashcards = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($listeFlashcards));
    } catch(Exception $e) {
        echo "Failure";
    }
?>