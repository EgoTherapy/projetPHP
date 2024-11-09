<?php

    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from listeflashcard where postee = 1;");
        $requete->execute();

        $listeFlashcardsPostee = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($listeFlashcardsPostee));
    } catch(Exception $e) {
        echo "Failure";
    }
?>