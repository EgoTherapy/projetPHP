<?php
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");

    try {
        $requete = $bdd->prepare("select max(id) as id from objectif;");
        $requete->execute();

        $idMax = $requete->fetch();
        echo $idMax["id"];
    } catch(Exception $e) {
        echo "Failure";
    }
?>