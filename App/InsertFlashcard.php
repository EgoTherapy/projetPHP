<?php 
    session_start();
    
    if($_SESSION["pseudo"] != "Guest" && !empty($_POST["question"]) && !empty($_POST["reponse"]) && isset($_POST["choixCouleur"])) {
        //Connexion à la base de données
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            //Récupère le plus grand id
            $requete = $bdd->prepare("select max(id_Flashcard) as id from flashcard;");
            $requete->execute();
            $id = $requete->fetch();
            if($id == NULL) {
                $id["id"] = 0;
            }
            $id["id"] = $id["id"] + 1;
            //Ajoute la nouvelle liste à la base de données
            $requete = $bdd->prepare("insert into flashcard(id_Flashcard, id_ListeFlashcard, pseudo, question,
                reponse, couleur) values(:idFlashcard, :idListe, :pseudo, :question, :reponse, :couleur);");
            $requete->bindValue(":idFlashcard", $id["id"], PDO::PARAM_INT);
            $requete->bindValue(":idListe", $_POST["id"], PDO::PARAM_INT);
            $requete->bindValue(":pseudo", $_SESSION["pseudo"], PDO::PARAM_STR);
            $requete->bindValue(":question", $_POST["question"], PDO::PARAM_STR);
            $requete->bindValue(":reponse", $_POST["reponse"], PDO::PARAM_STR);
            $requete->bindValue(":couleur", $_POST["choixCouleur"], PDO::PARAM_STR);
            $requete->execute();
            echo "Success";
        } catch(Exception $e) {
            echo "Failed";
        }
    }
?>