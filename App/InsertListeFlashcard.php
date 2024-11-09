<?php 
    session_start();
    
    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["nom"]) && isset($_POST["choixCouleur"])) {
        //Connexion à la base de données
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            //Recherche du plus grand id
            $requete = $bdd->prepare("select max(id_ListeFlashcard) as id from listeflashcard;");
            $requete->execute();
            $idMax = $requete->fetch();

            if($idMax == NULL) {
                $idMax["id"] = 0;
            }
            $idMax["id"] = $idMax["id"] + 1;

            //Ajoute la nouvelle liste à la base de données
            $requete = $bdd->prepare("insert into listeflashcard(id_ListeFlashcard, nom, couleur, pseudo, dateCreation, postee, categorie) 
            values(:id, :nom, :couleur, :user, now(), :postee, :categorie);");
            $requete->bindValue(":id", $idMax["id"], PDO::PARAM_INT);
            $requete->bindValue(":nom", $_POST["nom"], PDO::PARAM_STR);
            $requete->bindValue(":user", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            if(isset($_POST["postee"])) {
                $requete->bindValue(":postee", 1, PDO::PARAM_INT);
            } else {
                $requete->bindValue(":postee", 0, PDO::PARAM_INT);
            }
            $requete->bindValue(":categorie", $_POST["categorie"], PDO::PARAM_STR);
            $requete->execute();
        } catch(Exception $e) {
            echo "Failed";
        }
    }
?>