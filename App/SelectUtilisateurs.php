<?php 
    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");

    try {
        $requete = $bdd->prepare("select * from utilisateur;");
        $requete->execute();
        $result = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($result));
    } catch(Exception $e) {
        echo "Failure";
    }
?>