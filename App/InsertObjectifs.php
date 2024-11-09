<?php 
    session_start();
    
    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["nom"]) and !empty($_POST["description"]) && isset($_POST["choixCouleur"])) {
        //Connection to the database
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("select max(id) as id from objectif;");
            $requete->execute();
            $idMax = $requete->fetch();

            if($idMax == NULL) {
                $idMax["id"] = 0;
            }
            $idMax["id"] = $idMax["id"] + 1;

            $requete = $bdd->prepare("insert into objectif(id, pseudo, nom, dateObjectif, description, couleur) 
            values(:id, :user, :nom, now(), :description, :couleur);");
            $requete->bindValue(":id", $idMax["id"], PDO::PARAM_INT);
            $requete->bindValue(":user", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->bindValue(":nom", $_POST["nom"], PDO::PARAM_STR);
            $requete->bindValue(":description", $_POST["description"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            $requete->execute();
            echo "Success";
        } catch(Exception $e) {
            echo "Failed";
        }
    }
?>