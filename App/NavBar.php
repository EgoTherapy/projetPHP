<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link href="../fontawesome-free-5.15.3-web/css/all.css" rel="stylesheet">
        
        <link rel="stylesheet" type="text/css" href="style.css"/>
        <script type="text/javascript" src="../Librairies/jquery-3.5.1.min.js"></script>
    </head>

    <body>
        <?php 
            session_start();
            if(!isset($_SESSION["pseudo"])) {
                $_SESSION["pseudo"] = "Guest";
                $_SESSION["gestion"] = 0;
            }
        ?>
        <script>
            $(document).ready(function(){
                //Nous regardons si le joueur est déjà connecté pour mettre le message correct dans le bouton de déconnexion
                var pseudo = '<?php echo $_SESSION["pseudo"];?>';
                if(pseudo == "Guest") {
                    $("#btnDeconnexion").text("Se connecter");
                    $("#btnDeconnexion").attr("href", "Connexion.php");
                    $("a[href='ListeFlashcard.php']").prop("hidden", true);
                    $("a[href='Objectifs.php']").prop("hidden", true);
                } else {
                    $("#btnDeconnexion").text("Se déconnecter");
                }
                //Send to Index.php and change the session variable to "Guest" when we disconnect
                $("#btnDeconnexion").click(function(e) {
                    document.location.href="Connexion.php";
                    $.ajax({
                        url : "disconnect.php"
                    });
                });
                //Ajout du bouton admin si on a le niveau de gestion requis
                var gestion = '<?php echo $_SESSION["gestion"];?>';
                if(gestion == 1) {
                    $("a[href='Admin.php']").prop("hidden", false);
                }
            });
        </script>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="Index.php">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <a class="navbar-brand" href="ListeFlashcard.php">Fiches</a>
                    <a class="navbar-brand" href="Objectifs.php">Objectifs</a>
                    <a class="navbar-brand" href="Admin.php" hidden>Admin</a>
                    <a class="navbar-brand" href="Index.php" id="btnDeconnexion">Se déconnecter</a>
                </ul>

                <!--<form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Rechercher" aria-label="Search">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
                </form>-->
            </div>
        </nav>
    </body>
</html>