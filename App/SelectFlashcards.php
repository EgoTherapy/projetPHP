<?php
    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
    try {
        $requete = $bdd->prepare("select * from flashcard where id_ListeFlashcard = :id;");
        $requete->bindValue(":id", $_POST["id"], PDO::PARAM_INT);
        $requete->execute();

        $flashcards = $requete->fetchAll(PDO::FETCH_ASSOC);
        echo utf8_encode(json_encode($flashcards));
    } catch(Exception $e) {
        echo "Failure";
    }
?>