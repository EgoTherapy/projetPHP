<?php
    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from objectif where pseudo = :user;");
        $requete->bindValue(":user", $_SESSION["pseudo"], PDO::PARAM_STR);
        $requete->execute();

        $listeObjectifs = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($listeObjectifs));
    } catch(Exception $e) {
        echo "Failure";
    }
?>