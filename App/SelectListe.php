<?php
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from listeflashcard where id_ListeFlashcard = :id;");
        $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
        $requete->execute();

        $liste = $requete->fetch(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($liste));
    } catch(Exception $e) {
        echo "Failure";
    }
?>