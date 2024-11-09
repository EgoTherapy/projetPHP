<?php
    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from flashcard where id_Flashcard = :id;");
        $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
        $requete->execute();

        $flashcard = $requete->fetch();
        echo utf8_encode(json_encode($flashcard));
    } catch(Exception $e) {
        echo "Failure";
    }
?>