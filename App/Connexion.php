<html>
    <head>
        <meta charset="utf-8"/>
        <title>Connexion</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <script type="text/javascript" src="../Librairies/jquery-3.5.1.min.js"></script>
    </head>
    <body>
        <div id="divConnexion">
            <form method="Post" class="formulaire" autocomplete="off">
                <h1>Se connecter</h1>

                <input type="text" placeholder="Pseudo" name="pseudo"/>
                <input type="password" placeholder="Mot de passe" name="password"/>
                <input type="submit" id="connecter" value="Se connecter"/>
                <span class="error"></span><br/><br/>
                <a id="aInscription" href="Inscription.php">S'inscrire</a>
            </form>
        </div>
        
        <script>
            $(document).ready(function() {
                $("br").hide();
                $("form").submit(function(e) {
                    e.preventDefault();
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("SelectUtilisateur.php", data, "json")
                    .done(function(retour) {
                        console.log(retour);
                        if(retour == "Success") {
                            document.location.href = 'Index.php';
                        } else {
                            $(".error").text("Pseudo ou mot de passe invalide");
                            $("br").show();
                        }
                    })
                    .fail(function() {
                        alert("Failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });
            });
        </script>
    </body>
</html>