<?php 
    session_start();

    if($_SESSION["gestion"] == 1 && isset($_POST["nomCat"])) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");

        try {
            $requete = $bdd->prepare("insert into categorie(nom) values(:nom);");
            $requete->bindValue(":nom", $_POST["nomCat"], PDO::PARAM_STR);
            $requete->execute();
            
            echo "Success";
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>