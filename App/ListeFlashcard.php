<html>
    <head>
        <meta charset="utf-8"/>
        <title>Liste des fiches</title>

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
                flashcards = [];
                numFlashcard = 0;
                //Getting the highest id
                $.ajax({
                    url : "SelectMaxIDListe.php",
                    success : function(data) {
                        i = data;
                    }
                });
                //Reading of the lists in the database
                $.ajax({
                    url : "SelectListeFlashcard.php",
                    dataType : "json",
                    success : function(data) {
                        if(data.length==0) {
                            $("#titrePost").text("Aucune liste encore créée");
                            $("#titrePost").show();
                            $("#btnModif").prop("hidden", true);
                            $("#btnSupp").prop("hidden", true);
                        }
                        if(data.length>=1) {
                            $("#titrePost").hide();
                            $("#listeFlash").text("Listes de vos flashcards");
                            $("#btnModif").prop("hidden", false);
                            $("#btnSupp").prop("hidden", false);
                        }
                        for(var liste of data) {
                            //Adding the lists in the select for the modifications
                            $("#selectListeFlashcard").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                            //Adding the lists in the select for the suppression
                            $("#selectListeFlashcardSupp").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                            //Writing of the current lists contained in the database
                            $("#cree").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
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

    //GESTION DE LISTES DE FLASHCARDS
                //Création d'une liste de flashcards
                $("#nomCat").submit(function(e){
                    //e.preventDefault();
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("InsertListeFlashcard.php", data, "json")
                    .done(function(data) {
                        if(data == "Success") {
                            creerCat();
                        }
                    })
                    .fail(function() {
                        alert("Failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });

                //Select pour la modification d'une liste de flashcards
                $("#selectListeFlashcard").change(function() {
                    $("#nomListeFlashcard").val($("#selectListeFlashcard option:selected").text());
                    var id = $("#selectListeFlashcard option:selected").val()
                    var couleur = $("div[id=couleur" + id + "]").attr("class").split(" ")[2].substring(3);
                    $("#modifCouleurListe input[value=" + couleur + "]").attr("checked", "checked");
                    $("#idModif").val(parseInt(id));
                    $.ajax({
                        url : "SelectListe.php",
                        type : "post",
                        data : {"id" : id},
                        dataType : "json",
                        success : function(data) {
                            if(data.postee == 1) {
                                $("#listePosteeMod").prop("checked", true);
                            } else {
                                $("#listePosteeMod").prop("checked", false);
                            }
                            $("#selectCatModif option").filter(function() {
                                return $(this).text() == data.categorie;
                            }).prop("selected", true);
                        }
                    })
                });

                //Modification d'une liste de flashcards
                $("#formModification").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("UpdateListeFlashcard.php", data, "json")
                    .fail(function() {
                        alert("Failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });

                //Select pour la suppression d'une liste de flashcards
                $("#selectListeFlashcardSupp").change(function() {
                    $("#nomListeFlashcardSupp").val($("#selectListeFlashcardSupp option:selected").text());
                    var id = $("#selectListeFlashcardSupp option:selected").val()
                    $("#idSuppCard").val(parseInt(id));
                });

                //Suppression d'une liste de flashcards
                $("#formSuppCard").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("DeleteListeFlashcard.php", data, "json")
                    .fail(function() {
                        alert("Failed");
                        console.log("failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });

    //GESTION DE FLASHARDS
                //Création d'une flashcard
                $("#creationFlashcard").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $(this).find("textarea").prop("disabled", true);
                    $.post("InsertFlashcard.php", data, "json")
                    .fail(function() {
                        alert("Failed");
                    })
                    $(this).find("input").prop("disabled", false);
                    $(this).find("textarea").prop("disabled", false);
                });

                //Select pour la modification d'une flashcard
                $("#selectFlashcard").change(function() {
                    $("#modificationCouleurFlashcard input[type=radio]").prop("checked", false);
                    var id = $("#selectFlashcard option:selected").val();
                    $.ajax({
                        url : "SelectFlashcard.php",
                        type : "post",
                        data : {"id" : id},
                        dataType : "json",
                        success : function(data) {
                            $("#idModifFlashcard").val(data.id_Flashcard);
                            $("#questionModif").val(data.question);
                            $("#reponseModif").val(data.reponse);
                            $("#modifCouleurFlashcard input[value=" + data.couleur + "]").prop("checked", true);
                        }
                    });
                });

                //Modification d'une flashcard
                $("#formModifFlashcard").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $(this).find("textarea").prop("disabled", true);
                    $.post("UpdateFlashcard.php", data, "json")
                    .fail(function(){
                        alert("Failed");
                    })
                    $(this).find("input").prop("disabled", false);
                    $(this).find("textarea").prop("disabled", false);
                });

                //Select pour la suppression d'une flashcard
                $("#selectFlashcardSupp").change(function() {
                    $("#modificationCouleurFlashcard input[type=radio]").prop("checked", false);
                    var id = $("#selectFlashcardSupp option:selected").val();
                    $.ajax({
                        url : "SelectFlashcard.php",
                        type : "post",
                        data : {"id" : id},
                        dataType : "json",
                        success : function(data) {
                            $("#idSuppFlashcard").val(data.id_Flashcard);
                            $("#questionSupp").val(data.question);
                            $("#reponseSupp").val(data.reponse);
                            $("#suppCouleurFlashcard input[value=" + data.couleur + "]").prop("checked", true);
                        }
                    });
                });

                //Suppression d'une flashcard
                $("#formSuppFlashcard").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $(this).find("textarea").prop("disabled", true);
                    $.post("DeleteFlashcard.php", data, "json")
                    .fail(function() {
                        alert("Failed");
                    })
                    $(this).find("input").prop("disabled", false);
                    $(this).find("textarea").prop("disabled", false);
                });

                //Réinitialisation du modal révision lorsqu'on le ferme
                $("#fermerRevision").click(function() {
                    $("#reponseRevision").prop("disabled", false);
                    $("#verifierRevision").prop("hidden", false);
                    $("#suivanteRevision").prop("hidden", false);
                })

                //Gestion des catégories
                $("#selectListeCat").change(function() {
                    var cat = $("#selectListeCat option:selected").text();
                    if(cat == "Toutes categories") {
                        $.ajax({
                            url : "SelectListeFlashcard.php",
                            dataType : "json",
                            success : function(data) {
                                $("div[style='max-width: 18rem;']").hide();
                                $("button[id*='bouton']").hide();  
                                if(data.length==0) {
                                    $("#titrePost").text("Aucune liste encore créée");
                                    $("#titrePost").show();
                                    $("#btnModif").prop("hidden", true);
                                    $("#btnSupp").prop("hidden", true);
                                }
                                if(data.length>=1) {
                                    $("#titrePost").hide();
                                    $("#listeFlash").text("Listes de vos flashcards");
                                    $("#btnModif").prop("hidden", false);
                                    $("#btnSupp").prop("hidden", false);
                                }
                                for(var liste of data) {
                                    //Adding the lists in the select for the modifications
                                    $("#selectListeFlashcard").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                                    //Adding the lists in the select for the suppression
                                    $("#selectListeFlashcardSupp").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                                    //Writing of the current lists contained in the database
                                    $("#cree").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
                                }  
                            }
                        });
                    } else {
                        $.ajax({
                            url : "SelectListeFlashcardCat.php",
                            data : {"categorie" : cat, "postee" : 0},
                            type : "post",
                            dataType : "json",
                            success : function(data) {
                                $("div[style='max-width: 18rem;']").hide();
                                $("button[id*='bouton']").hide(); 
                                if(data.length==0) {
                                    $("#titrePost").text("Aucune liste encore créée");
                                    $("#titrePost").show();
                                    $("#btnModif").prop("hidden", true);
                                    $("#btnSupp").prop("hidden", true);
                                }
                                if(data.length>=1) {
                                    $("#titrePost").hide();
                                    $("#listeFlash").text("Listes de vos flashcards");
                                    $("#btnModif").prop("hidden", false);
                                    $("#btnSupp").prop("hidden", false);
                                }
                                for(var liste of data) {
                                    //Adding the lists in the select for the modifications
                                    $("#selectListeFlashcard").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                                    //Adding the lists in the select for the suppression
                                    $("#selectListeFlashcardSupp").append("<option value= " + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                                    //Writing of the current lists contained in the database
                                    $("#cree").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '" onclick="reviser(' + liste.id_ListeFlashcard + ');"><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
                                }
                            }
                        });
                    }
                });
            });

    //Affichage des flashcards d'une liste
            function afficherFlashcard(id) {
                $("#selectListeCat").hide();
                $("div[style='max-width: 18rem;']").hide();
                $(".inputPerso").hide();
                $("#btnListeFlashcard").hide();
                $("#creerFlashcard").prop("hidden", false);
                $("#modifierFlashcard").prop("hidden", false);
                $("#supprimerFlashcard").prop("hidden", false);
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
                            //Adding the lists in the select for the modifications
                            $("#selectFlashcard").append("<option value= " + flashcard.id_Flashcard + ">" + flashcard.question + "</option>");
                            $("#selectFlashcardSupp").append("<option value= " + flashcard.id_Flashcard + ">" + flashcard.question + "</option>");
                            //Writing of the current lists contained in the database
                            $("#flash").after('<div class="card text-white bg-' + flashcard.couleur + ' mb-3" id="couleur' + flashcard.id_Flashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + flashcard.question + '</h5></i></div></div>');
                        }
                    }
                });
            }

            //Refresh la page des flashcards pour revenir sur les listes de flashcards
            function retour() {
                document.location.reload();
            }

    //Fonction pour la création d'une liste de flashcards
            function creerCat() {
                // Récupération des valeurs du formulaire
                var nomScCat = document.getElementById("nom").value;

                //Pour voir quel radio button est checked
                //                                  |
                //                                  v
                var coulScCat = $("input[type='radio'][name='choixCouleur']:checked").val();
               
                // Incrémentation de l'indice pour la prochaine liste de flashcards
                i=i+1;

                //Ajout visuel
                $("#cree").after('<div class="card text-white bg-' + liste.couleur + ' mb-3" id="couleur' + liste.id_ListeFlashcard + '" style="max-width: 18rem;"> <div class="card-body"> <h5 class="card-title" id="nom">' + liste.nom + '</h5></i></div></div> <button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '"onclick="afficherFlashcard(' + liste.id_ListeFlashcard + ');">Afficher les flashcards</button>&nbsp;&nbsp;&nbsp;<button class="inputPerso" id="bouton' + liste.id_ListeFlashcard + '><span id="couleur"><p class="card-text" data-target="#RevisionFlashCard" data-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Réviser"><i class="fas fa-book-reader"></i></p></span></button>');
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
                } else {
                    $("#questionRevision").val("Aucune flashcards");
                    $("#reponseRevision").prop("disabled", true);
                    $("#verifierRevision").prop("hidden", true);
                    $("#suivanteRevision").prop("hidden", true);
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
                    $("#messageCorrectionRevision").text("Réponse incorrecte ! La réponse correcte était : " + flashcards[numFlashcard].reponse);
                    $("#messageCorrectionRevision").css("color", "red");
                }
                $("#correctionRevision").prop("hidden", false);
            }
        </script>


        <div>
            </br>
            <h1 id="listeFlash">Liste de vos flashcards</h1>
            </br>
            <h2 id="titrePost"></h2>
            </br>
        </div>
        
        <!-- Liste des différentes catégories créées au préalable par un admin -->
        <div class="input-group mb-3">
            <select class="form-select" id="selectListeCat">
                <!-- Contenu lu dans la base de données --> 
                <option selected>Toutes categories</option>
            </select>
        </div>

    <!-- _______________________ -->
    <!--   Liste de flashcards   -->
    <!-- _______________________ -->

        <div id="btnListeFlashcard">
            <!-- Bouton de création -->
            <button class="inputPerso" id="btnCreer" data-target="#newCard" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajouter"><i class="fas fa-plus-circle"></i></p></span></button>
            <!-- Bouton de modification -->
            <button class="inputPerso" id="btnModif" data-target="#editCard" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modifier"><i class="fas fa-edit"></i></p></span></button>
            <!-- Bouton de suppresssion -->
            <button class="inputPerso" id="btnSupp" data-target="#SuppCard" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Supprimer"><i class="fas fa-trash"></i></p></span></button>
        </div>

        <p id="cree"></p>
        <!--Contenu ajoutable-->
        
        <!-- Modal de création -->
        <div id="modalCreationListe">
            <div class="modal fade" id="newCard" data-backdrop="static" data-keyboard="false"  aria-labelledby="newListe" aria-hidden="true" >
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h2 class="modal-title" id="newCardLabel"><span class="badge badge-light">&nbsp;&nbsp;&nbsp;Création d'une nouvelle catégorie&nbsp;</span></h2>
                        </div>

                        <div class="modal-body">
                            <form class="creationListeFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="nomCat">
                            
                                <!-- Nom -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Nom</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" name="nom" id="nom">
                                </div>

                                </br>

                                <select id="selectCatCreation" name="categorie">
                                    <option selected>Toutes categories</option>
                                </select>

                                </br></br>
                                                                
                                <div id="nav"><?php include "Couleurs.php" ?></div>

                                </br></br>

                                <h5><input type="checkbox" id="listePosteeCr" name="postee"><span class="badge badge-light">&nbsp;Rendre public&nbsp;</span></input></h5>

                                <fieldset class="BoutonSurLaDroite">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Créer" class="inputPerso"/>
                                </fieldset>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de modification -->
        <div id="modalModificationListe">
            <div class="modal fade" id="editCard" data-backdrop="static" data-keyboard="false"  aria-labelledby="modifListe" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h1 class="modal-title" id="editCardLabel"><span class="badge badge-light">&nbsp;Modification d'une catégorie&nbsp;</span></h1>
                        </div>
                        <div class="modal-body">
                            <form class="modificationListeFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formModification">
                                <div class="select-style">
                                    <select id="selectListeFlashcard">
                                        <option selected disabled>Choisissez une liste</option>
                                        <!-- Contenu lu dans la base de données --> 
                                    </select>
                                </div>

                                <!-- Nom -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Nom</span>
                                    </div>
                                    <input type="number" value=0 id="idModif" name="id" hidden/> 
                                    <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" id="nomListeFlashcard" name="nom">
                                </div>
                        
                                </br>

                                <select id="selectCatModif" name="categorie">
                                    <option selected>Toutes categories</option>
                                </select>

                                </br></br>

                                <div id="modifCouleurListe"><?php include "Couleurs.php" ?></div>

                                </br></br>

                                <div class="modal-footer">
                                    <h5><input type="checkbox" id="listePosteeMod" name="postee"><span class="badge badge-light">&nbsp;Rendre public&nbsp;</span></input></h5>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Modifier" class="inputPerso"/>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
         <!-- Modal de suppression -->
         <div id="modalBootstrap">
            <div class="modal fade" id="SuppCard" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="suppListe" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h1 class="modal-title" id="editCardLabel"><span class="badge badge-light">&nbsp;Suppression d'une catégorie&nbsp;</span></h1>
                        </div>
                        <div class="modal-body">
                            <form class="modificationFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formSuppCard">
                                <select id="selectListeFlashcardSupp">
                                    <option selected disabled>Choisissez une catégorie</option>
                                    <!-- Contenu lu dans la base de données --> 
                                </select>

                                <!-- Id flashcard --> 
                                <input type="number" value=0 id="idSuppCard" name="idFlashcard" hidden/>

                                <!-- Nom -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Nom</span>
                                    </div>
                                    <input type="number" value=0 id="idSupp" name="id" hidden/> 
                                    <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" id="nomListeFlashcardSupp" name="nom" disabled>
                                </div>

                                </br></br>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Supprimer" class="inputPerso2"/>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    <!-- _______________________ -->
    <!--        Flashcards       -->
    <!-- _______________________ -->

        <!-- Bouton de retour -->
        <button class="inputPerso2" id="retour" onclick="retour();" hidden><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Retour"><i class="fas fa-arrow-alt-circle-left"></i></p></span></button>
        <!-- Bouton de création -->
        <button class="inputPerso2" id="creerFlashcard" data-target="#newFlashcard" data-toggle="modal" hidden><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajouter"><i class="fas fa-plus-circle"></i></p></span></button>
        <!-- Bouton de modification -->
        <button class="inputPerso2" id="modifierFlashcard" data-target="#editFlashCard" data-toggle="modal"hidden><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modifier"><i class="fas fa-edit"></i></p></span></button>
        <!-- Bouton de suppresssion -->
        <button class="inputPerso2" id="supprimerFlashcard" data-target="#SuppFlashCard" data-toggle="modal" hidden><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Supprimer"><i class="fas fa-trash"></i></p></span></button>

        </br>
        <p id="flash"></p>

        <!-- Modal de création FlashCard -->
        <div id="modalCreationFlashcard">
            <div class="modal fade" id="newFlashcard" data-backdrop="static" data-keyboard="false"  aria-labelledby="newCard" aria-hidden="true" >
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h2 class="modal-title" id="newCardLabel"><span class="badge badge-light">&nbsp;&nbsp;&nbsp;Création d'une nouvelle flashcard&nbsp;</span></h2>
                        </div>

                        <div class="modal-body">
                            <form class="creationFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="creationFlashcard">
                                <input type="number" value="1" id="idListeCreation" name="id" hidden/>
                                <!-- Question -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Question</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Question" id="questionCreation" name="question"></textarea>
                                </div></br>
                                <!-- Réponse -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Réponse</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Reponse" id="reponseCreation" name="reponse"></textarea>
                                </div>

                                </br></br>

                                <div id="nav"><?php include "Couleurs.php" ?></div>
                                
                                <fieldset class="BoutonSurLaDroite">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Créer" class="inputPerso2"/>
                                </fieldset>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de modification FlashCard -->
        <div id="modalBootstrap">
            <div class="modal fade" id="editFlashCard" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="editCard" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h1 class="modal-title" id="editCardLabel"><span class="badge badge-light">&nbsp;Modification d'une flashcard&nbsp;</span></h1>
                        </div>
                        <div class="modal-body">
                            <form class="modificationFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formModifFlashcard">
                                <select id="selectFlashcard">
                                    <!-- Contenu lu dans la base de données --> 
                                    <option selected disabled>Choisissez une flashcard</option>
                                </select>

                                <!-- Id flashcard --> 
                                <input type="number" id = "idModifFlashcard" name="id" hidden/>

                                <!-- Question -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Question</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Question" id="questionModif" name="question"></textarea>
                                </div></br>
                                <!-- Réponse -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Réponse</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Reponse" id="reponseModif" name="reponse"></textarea>
                                </div>

                                </br></br>

                                <div id="modifCouleurFlashcard"><?php include "Couleurs.php" ?></div>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Modifier" class="inputPerso2"/>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de suppression FlashCard -->
        <div id="modalBootstrap">
            <div class="modal fade" id="SuppFlashCard" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="suppCard" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Titre -->
                        <div class="modal-header">
                            <h1 class="modal-title" id="editCardLabel"><span class="badge badge-light">&nbsp;Suppression d'une flashcard&nbsp;</span></h1>
                        </div>
                        <div class="modal-body">
                            <form class="modificationFlashcard" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formSuppFlashcard">
                                <select id="selectFlashcardSupp">
                                    <!-- Contenu lu dans la base de données --> 
                                    <option selected disabled>Choisissez une flashcard</option>
                                </select>

                                <!-- Id flashcard --> 
                                <input type="number" id = "idSuppFlashcard" name="id" hidden/>

                                <!-- Question -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Question</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Question" id="questionSupp" name="question" disabled></textarea>
                                </div></br>
                                <!-- Réponse -->
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-lg">Réponse</span>
                                    </div>
                                    <textarea class="form-control" aria-label="Reponse" id="reponseSupp" name="reponse" disabled></textarea>
                                </div>

                                </br></br>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                    <input type="submit" value="Supprimer" class="inputPerso2"/>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                                
                                <button type="button" class="btn btn-secondary" onclick="verifierReponseRevision();" id="verifierRevision">Vérifier</button>
                                
                                </br></br>

                                <div id="correctionRevision">
                                    <span id="messageCorrectionRevision"></span>
                                </div>

                                </br></br>
                                
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="fermerRevision">Fermer</button>
                                    <button type="button" onclick="prochaineFlashcard();" class="inputPerso2" id="suivanteRevision">Suivante</button>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>