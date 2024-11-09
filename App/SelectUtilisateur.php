<?php 
    session_start();
    if(isset($_POST["pseudo"]) and isset($_POST["password"])) {
        $bdd = new PDO("mysql:host=localhost;dbname=projettm", "root", "");
        try {
            $requete = $bdd->prepare("select * from utilisateur where pseudo like binary :pseudo;");
            $requete->bindValue(":pseudo", $_POST["pseudo"], PDO::PARAM_STR);
            $requete->execute();
            $result = $requete->fetch(PDO::FETCH_ASSOC);
            //echo utf8_encode(json_encode($result));
            if($result["motDePasse"] != null and $result["motDePasse"] == md5($_POST["password"])) {
                $_SESSION["pseudo"] = $result["pseudo"];
                $_SESSION["gestion"] = $result["niveauGestion"];
                echo "Success";
            } else {
                echo false;
            }
        } catch(Exception $e) {
            echo "Failure";
        }
    }
?>