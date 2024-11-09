<?php
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select nom from categorie where nom != 'Toutes categories';");
        $requete->execute();

        $listeCat = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($listeCat));
    } catch(Exception $e) {
        echo "Failure";
    }
?>