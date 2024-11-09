
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Home</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link href="../fontawesome-free-5.15.3-web/css/all.css" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="style.css"/>
        <script type="text/javascript" src="../Librairies/jquery-3.5.1.min.js"></script>
    </head>

    <body>
        <!-- NavBar -->
        <div id="nav"><?php include "NavBar.php" ?></div>

        <script>
            $(document).ready(function(){
                i=0;
                $.ajax({
                    url : "SelectListeFlashcardPostee.php",
                    dataType : "json",
                    success : function(data) {
                        if(data.length==0)
                        {
                            $("#titrePost").text("Aucune liste encore postée");
                        }
                        for(var liste of data) {
                            $("#affichage").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
                        }  
                    }
                });

                //Reading of the categories in the database
                $.ajax({
                    url : "SelectCategorie.php",
                    dataType : "json",
                    success : function(data) {
                        for(var listeCategorie of data) {
                            $("#selectListeCat").append("<option>" + listeCategorie.nom + "</option>");
                            $("#selectCatCreation").append("<option>" + listeCategorie.nom + "</option>");
                            $("#selectCatModif").append("<option>" + listeCategorie.nom + "</option>");
                        }  
                    }
                });

                //Gestion des catégories
                $("#selectListeCat").change(function() {
                    var cat = $("#selectListeCat option:selected").text();
                    if(cat == "Toutes categories") {
                        $.ajax({
                            url : "SelectListeFlashcardPostee.php",
                            dataType : "json",
                            success : function(data) {
                                $("div[style='max-width: 18rem;']").hide();
                                $(".inputPerso").hide();
                                if(data.length==0) {
                                    $("#titrePost").text("Aucune liste encore postée");
                                    $("#titrePost").show();
                                }
                                if(data.length>=1) {
                                    $("#titrePost").text("Listes de vos flashcards");
                                }
                                for(var liste of data) {
                                    //Writing of the current lists contained in the database
                                    $("#affichage").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
                                }  
                            }
                        });
                    } else {
                        $.ajax({
                            url : "SelectListeFlashcardCatPostee.php",
                            data : {"categorie" : cat, "postee" : 1},
                            type : "post",
                            dataType : "json",
                            success : function(data) {
                                $("div[style='max-width: 18rem;']").hide();
                                $(".inputPerso").hide();
                                if(data.length==0) {
                                    $("#titrePost").text("Aucune liste encore postée");
                                    $("#titrePost").show();
                                }
                                if(data.length>=1) {
                                    $("#titrePost").text("Listes de vos flashcards");
                                }
                                for(var liste of data) {
                                    //Writing of the current lists contained in the database
                                    $("#affichage").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
                                }
                            }
                        });
                    }
                });
            });

            //Affichage des flashcards d'une liste
            function afficherFlashcard(id) {
                $("div[style='max-width: 18rem;']").hide();
                $(".inputperso").hide();
                $("#btnListeFlashcard").hide();
                $("#selectListeCat").hide();
                $("#retour").prop("hidden", false);
                $("#idListeCreation").val(id);

                //Affichage des flashcards de la liste
                $.ajax({
                    url : "SelectFlashcards.php",
                    type : "post",
                    data : {'id' : id},
                    dataType : "json",
                    success : function(data) {
                        for(var flashcard of data) {
                            //Writing of the current lists contained in the database
                            $("#affichage").after('<div class="card text-white bg-' + flashcard.couleur + ' mb-3" id="couleur' + flashcard.id_Flashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + flashcard.question + '</h5></i></div></div>');
                        }
                    }
                });;
            }

            //Refresh la page des flashcards pour revenir sur les listes de flashcards
            function retour() {
                document.location.reload();
            }

            
        //REVISION
        function reviser(id) {
                flashcards = [];
                numFlashcard = 0;
                $.ajax({
                    url : "SelectFlashcards.php",
                    async : false,
                    type : "post",
                    data : {'id' : id},
                    dataType : "json",
                    success : function(data) {
                        flashcards = data;
                    }
                });
                if(flashcards.length > 0) {
                    $("#questionRevision").val(flashcards[numFlashcard].question);
                    $("#reponseRevisionCorrigee").val(flashcards[numFlashcard].reponse);
                }
            }

            //Affiche la prochaine flashcard
            function prochaineFlashcard() {
                numFlashcard = numFlashcard +1;
                $("#reponseRevision").prop("disabled", false);
                $("#reponseRevision").val("");
                $("#correctionRevision").prop("hidden", true);
                if(flashcards.length > numFlashcard) {
                    $("#questionRevision").val(flashcards[numFlashcard].question);
                    $("#reponseRevisionCorrigee").val(flashcards[numFlashcard].reponse);
                } else {
                    numFlashcard = 0;
                    $("#questionRevision").val(flashcards[numFlashcard].question);
                    $("#reponseRevisionCorrigee").val(flashcards[numFlashcard].reponse);
                }
            }

            //Vérifie si la réponse encodée par l'utilisateur est correcte ou non
            function verifierReponseRevision() {
                $("#reponseRevision").prop("disabled", true);
                if($("#reponseRevision").val()==flashcards[numFlashcard].reponse)
                {
                    $("#messageCorrectionRevision").text("Réponse correcte !");
                    $("#messageCorrectionRevision").css("color", "green")
                }
                else
                {
                    /*$("#mauvaiseReponse").prop("hidden", false);
                    //$("#reponseRevisionCorrigee")=flashcard.reponse;*/
                    $("#messageCorrectionRevision").text("Réponse incorrecte ! La réponse correcte était : " + flashcards[numFlashcard].reponse);
                    $("#messageCorrectionRevision").css("color", "red");
                }
                $("#correctionRevision").prop("hidden", false);
            }
        </script>

        <div class="contenu">
            </br>
            <h1>Bienvenue <?php echo $_SESSION["pseudo"];?> !</h1>

            </br></br>
            <h2 id="titrePost">Listes des flashcards postées</h2>
            <!-- Bouton de retour -->
            <button class="inputPerso2" id="retour" onclick="retour();" hidden><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Retour"><i class="fas fa-arrow-alt-circle-left"></i></p></span></button>

            <select class="form-select" id="selectListeCat">
                <!-- Contenu lu dans la base de données --> 
                <option selected>Toutes categories</option>
            </select>

            <p id="affichage"></p>

        <!-- _______________________ -->
        <!--         Révision        -->
        <!-- _______________________ -->

            <!-- Modal de révision FlashCard -->
            <div id="modalBootstrap">
                <div class="modal fade" id="RevisionFlashCard" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="revisionCard" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <!-- Titre -->
                            <div class="modal-header">
                                <h1 class="modal-title" id="editCardLabel"><span class="badge badge-light">&nbsp;Révision&nbsp;</span></h1>
                            </div>
                            <div class="modal-body">
                                <form class="modificationFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formRevisionFlashcard">

                                    <!-- Id flashcard --> 
                                    <input type="number" id = "idRevisionFlashcard" name="id" hidden/>

                                    <!-- Question -->
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-lg">Question</span>
                                        </div>
                                        <textarea class="form-control" aria-label="Question" id="questionRevision" name="question" disabled></textarea>
                                    </div>

                                    </br>

                                    <!-- Réponse -->
                                    <div class="input-group input-group-lg" id="divReponseRevision">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroup-sizing-lg">Réponse</span>
                                        </div>
                                        <textarea class="form-control" aria-label="Reponse" id="reponseRevision" name="reponse"></textarea>
                                    </div>

                                    </br>
                                    
                                    <button type="button" class="btn btn-secondary" onclick="verifierReponseRevision();">Vérifier</button>
                                    
                                    </br></br>

                                    <div id="correctionRevision">
                                        <span id="messageCorrectionRevision"></span>
                                    </div>

                                    </br></br>
                                    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                        <button type="button" onclick="prochaineFlashcard();" class="inputPerso2">Suivante</button>
                                    </div>
                                </form>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>