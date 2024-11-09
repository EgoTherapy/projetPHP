<html>
    <head>
        <meta charset="utf-8"/>
        <title>Inscription</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <script type="text/javascript" src="../Librairies/jquery-3.5.1.min.js"></script>
    </head>
    <body>
        <form class="formulaire" method="post" autocomplete="off">
            <h1>S'inscrire</h1>

            <input type="text" placeholder="Pseudo" name="pseudo"/>
            <input type="password" placeholder="Password" name="password"/>
            <input type="password" placeholder="Confirm password" name="confirm"/>
            <input type="submit" value="S'inscire" name="inscription"/>

            <a id="aConnexion" href="Connexion.php">Se connecter</a> 
        </form>
        <script>
            $(document).ready(function() {
                $("form").submit(function(e) {
                    e.preventDefault();
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("InsertUtilisateur.php", data, "json")
                    .done(function(data) {
                        if(data == "Success") {
                            document.location.href = 'Index.php';
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