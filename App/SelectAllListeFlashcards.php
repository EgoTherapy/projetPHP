<?php
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from listeflashcard");
        $requete->execute();

        $listeFlashcards = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($listeFlashcards));
    } catch(Exception $e) {
        echo "Failure";
    }
?>