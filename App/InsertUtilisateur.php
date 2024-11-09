<?php 
    session_start();
    
    if(!empty($_POST["pseudo"]) and !empty($_POST["password"]) and !empty($_POST["confirm"]) 
        and $_POST["password"] == $_POST["confirm"]) {
        //Connexion à la base de données
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            //Check si le pseudo d'utilisateur n'est pas déjà utilisé
            $requete = $bdd->prepare("select * from utilisateur;");
            $requete->execute();
            while($utilisateur = $requete->fetch()) {
                if($utilisateur["pseudo"] == $_POST["pseudo"]) {
                    echo false;
                    exit();
                }
            }

            //Ajoute le nouvel utilisateur à la base de données
            $requete = $bdd->prepare("insert into utilisateur(pseudo, motDePasse, niveauGestion) 
            values(:pseudo, md5(:password), 0);");
            $requete->bindValue(":pseudo", $_POST["pseudo"], PDO::PARAM_STR);
            $requete->bindValue(":password", $_POST["password"], PDO::PARAM_STR);
            $requete->execute();
            $_SESSION["pseudo"] = $_POST["pseudo"];
            echo "Success";
        } catch(Exception $e) {
            echo false;
        }
    }
?>