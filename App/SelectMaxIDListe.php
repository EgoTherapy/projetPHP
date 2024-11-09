<?php
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");

    try {
        $requete = $bdd->prepare("select max(id_ListeFlashcard) as id from listeflashcard;");
        $requete->execute();

        $idMax = $requete->fetch();
        echo $idMax["id"];
    } catch(Exception $e) {
        echo "Failure";
    }
?>