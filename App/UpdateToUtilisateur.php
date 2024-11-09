<?php 
    session_start();
    
    if($_SESSION["gestion"] == 1) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");

        try {
            $requete = $bdd->prepare("update utilisateur set niveauGestion = 0 where pseudo = :pseudo;");
            $requete->bindValue(":pseudo", $_POST["pseudo"], PDO::PARAM_STR);
            $requete->execute();

            echo $_POST["pseudo"];
        } catch (Exception $e) {
            echo "Fail";
        }
    }
?>