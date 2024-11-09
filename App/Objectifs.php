<html>
    <head>
        <meta charset="utf-8"/>
        <title>Liste des objectifs</title>

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
                i = 0;
                //Getting the highest id
                $.ajax({
                    url : "SelectMaxIDObjectifs.php",
                    success : function(data) {
                        i = data;
                        console.log(data);
                    }
                });

                $.ajax({
                    url : "SelectObjectifs.php",
                    dataType : "json",
                    success : function(data) {
                        if(data.length==0) {
                            $("#creation").text("Aucun objectif créé");
                            $("#btnModif").prop("hidden", true);
                            $("#btnSupp").prop("hidden", true);
                        }
                        else {
                            if(data.length==1) {
                                $("#creation").text("Objectif");
                            }
                        }
                        for(var objectifs of data) {
                            $("#selectObjectifs").append("<option value= " + objectifs.id + ">" + objectifs.nom + "</option>");
                            $("#selectObjectifsSupp").append("<option value= " + objectifs.id + ">" + objectifs.nom + "</option>");

                            //Displaying the objectives
                            $('#creation').after("<a class='list-group-item list-group-item-" + objectifs.couleur + " list-group-item-action' id='list-" + objectifs.id + "-list' data-toggle='list' href='#list-" + objectifs.id + "' role='tab' aria-controls='1'><p id='nom'>"+ objectifs.nom +"</p></a>");
                            $('#nav-tabContent').after('<div class="tab-pane fade" id="list-' + objectifs.id + '" role="tabpanel" aria-labelledby="list-' + objectifs.id + '-list"><p id="desc' + objectifs.id + '">' + objectifs.description + '</p></div>');
                        }  
                    }
                });

                //Création d'un objectif
                $("#nomDescObj").submit(function(e){
                    //e.preventDefault();
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("InsertObjectifs.php", data, "json")
                    .done(function(data) {
                        if(data == "Success") {
                            //document.location.href = 'ListeFlashcard.php';
                            getValue();
                        }
                    })
                    .fail(function() {
                        alert("Failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });

                //Select pour la modification d'un objectif
                $("#selectObjectifs").change(function() {
                    $("#nomModif").val($("#selectObjectifs option:selected").text());
                    var id = $("#selectObjectifs option:selected").val()
                    var couleur = $("a[id=list-" + id + "-list]").attr("class").split(" ")[1].split("-")[3];
                    var desc = $("#desc" + id).html();
                    $("#modifCouleur input[value=" + couleur + "]").attr("checked", "checked");
                    $("#idModif").val(parseInt(id));
                    $("#modifDescObjectif").val(desc);
                });

                //Modification d'un objectif
                $("#formModification").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("UpdateObjectifs.php", data, "json")
                    .fail(function() {
                        alert("Failed");
                    });
                    $(this).find("input").prop("disabled", false);
                });

                //Select pour la suppression d'un objectif
                $("#selectObjectifsSupp").change(function() {
                    $("#nomSupp").val($("#selectObjectifsSupp option:selected").text());
                    var id = $("#selectObjectifsSupp option:selected").val()
                    var desc = $("#desc" + id).html();
                    $.ajax({
                        url : "SelectObjectifs.php",
                        type : "post",
                        data : {"id" : id},
                        dataType : "json",
                        success : function(data) {
                            $("#idSupp").val(parseInt(id));
                            $("#suppDescObjectif").val(desc);
                        }
                    });
                });

                //Suppression d'un objectif
                $("#formSuppression").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $(this).find("textarea").prop("disabled", true);
                    $.post("DeleteObjectif.php", data, "json");
                    $(this).find("input").prop("disabled", false);
                    $(this).find("textarea").prop("disabled", false);
                });
            });

    //Récupération des infos d'un objectif
            function getValue() {
                // Récupération des valeurs du formulaire
                var nomObj = document.getElementById("nomObjectif").value;
                var descObj = document.getElementById("descObjectif").value;
                var coulObj = $("input[type='radio'][name='choixCouleur']:checked").val();
                
                // Incrémentation de l'indice pour le prochain objectif
                i=i+1;

                //Ajouts visuels
                $('#creation').after("<a class='list-group-item list-group-item-" + coulObj + " list-group-item-action' id='list-" + i + "-list' data-toggle='list' href='#list-" + i + "' role='tab' aria-controls='1'><p id='nom'>"+ nomObj +"</p></a>");
                $('#nav-tabContent').after('<div class="tab-pane fade" id="list-' + i + '" role="tabpanel" aria-labelledby="list-' + i + '-list"><p id="desc"' + i + '>' + descObj + '</p></div>');
            }
        </script>

        <div class="contenu">
            </br>
            <h1>Liste de vos objectifs</h1>
            </br>
            <!-- Bouton de création -->
            <button class="inputPerso" data-target="#newObjectif" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajouter"><i class="fas fa-plus-circle"></i></p></span></button>
            <!-- Bouton de modification -->
            <button class="inputPerso" id="btnModif" data-target="#editObjectif" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Modifier"><i class="fas fa-edit"></i></p></span></button>
            <!-- Bouton de suppresssion -->
            <button class="inputPerso" id="btnSupp" data-target="#deleteObjectif" data-toggle="modal"><span id="couleur"><p class="card-text" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Supprimer"><i class="fas fa-trash"></i></p></span></button>

            </br></br>

            <div class="row">
                <div class="col-4">
                    <div class="list-group" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-dark list-group-item-action" id="creation" style="text-align:center; font-weight:bold; color:#01011A;">Objectifs</i></a>
                        <!--Contenu ajoutable-->
                    </div>
                </div>
                <div class="col-8">
                    <div class="tab-content" id="nav-tabContent">
                        <!--Contenu ajoutable-->
                    </div>
                </div>
                
                <!-- Modal de création -->
                <div id="modalBootstrap">
                    <div class="modal fade" id="newObjectif" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="newObjectif" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Titre -->
                                <div class="modal-header">
                                    <h1 class="modal-title" id="newObjectifLabel"><span class="badge badge-light">&nbsp;Création d'un nouvel objectif&nbsp;</span></h1>
                                </div>

                                <div class="modal-body">
                                    <form class="creationObjectif" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="nomDescObj">

                                        <!-- Nom -->
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text " id="inputGroup-sizing-lg">Nom</span>
                                            </div>
                                            <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" id="nomObjectif" autofocus="true" name="nom">
                                        </div>
                                    
                                        </br></br>

                                        <!-- Description -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Description</span>
                                            </div>
                                            <textarea class="form-control" aria-label="Description" id="descObjectif" name="description"></textarea>
                                        </div>

                                        </br></br>

                                        <!-- Couleur -->
                                        <div id="creationCouleur"><?php include "Couleurs.php" ?></div>
                                
                                        <fieldset class="BoutonSurLaDroite">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            <!--<button type="button" class="btn btn-secondary" id="creation" onclick="getValue();" data-dismiss="modal">Créer</button>-->
                                            <input type="submit" value="Créer" class="inputPerso"/>
                                        </fieldset>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de modification -->
                <div id="modalBootstrap">
                    <div class="modal fade" id="editObjectif" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="editObjectif" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Titre -->
                                <div class="modal-header">
                                    <h1 class="modal-title" id="newObjectifLabel"><span class="badge badge-light">&nbsp;Modification d'un objectif&nbsp;</span></h1>
                                </div>
                                <div class="modal-body">
                                    <form class="creationObjectif" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formModification">
                                        <select id="selectObjectifs">
                                            <option selected disabled>Choisissez un objectif</option>
                                            <!-- Contenu lu dans la base de données -->
                                        </select>

                                        <!-- Nom -->
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text " id="inputGroup-sizing-lg">Nom</span>
                                            </div>
                                            <input type="number" value=0 id="idModif" name="id" hidden/> 
                                            <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" id="nomModif" autofocus="true" name="nom">
                                        </div>
                                    
                                        </br></br>

                                        <!-- Description -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Description</span>
                                            </div>
                                            <textarea class="form-control" aria-label="Description" id="modifDescObjectif" name="description"></textarea>
                                        </div>

                                        </br></br>

                                        <!-- Couleur -->
                                        <div id="modifCouleur"><?php include "Couleurs.php" ?></div>
                                
                                        <fieldset class="BoutonSurLaDroite">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            <input type="submit" value="Modifer" class="inputPerso"/>
                                        </fieldset>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal de suppression -->
                <div id="modalBootstrap">
                    <div class="modal fade" id="deleteObjectif" id="modalBootstrap" data-backdrop="static" data-keyboard="false"  aria-labelledby="deleteObjectif" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <!-- Titre -->
                                <div class="modal-header">
                                    <h1 class="modal-title" id="newObjectifLabel"><span class="badge badge-light">&nbsp;Suppression d'un objectif&nbsp;</span></h1>
                                </div>
                                <div class="modal-body">
                                    <form class="creationObjectif" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" autocomplete="off" id="formSuppression">
                                        <select id="selectObjectifsSupp">
                                            <option selected disabled>Choisissez un objectif</option>
                                            <!-- Contenu lu dans la base de données -->
                                        </select>

                                        <!-- Nom -->
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text " id="inputGroup-sizing-lg">Nom</span>
                                            </div>
                                            <input type="number" value=0 id="idSupp" name="id" hidden/> 
                                            <input type="text" class="form-control" aria-label="Nom" aria-describedby="inputGroup-sizing-sm" id="nomSupp" autofocus="true" name="nom" disabled>
                                        </div>
                                    
                                        </br></br>

                                        <!-- Description -->
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Description</span>
                                            </div>
                                            <textarea class="form-control" aria-label="Description" id="suppDescObjectif" name="description" disabled></textarea>
                                        </div>

                                        </br></br>
                                
                                        <fieldset class="BoutonSurLaDroite">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                            <input type="submit" value="Supprimer" class="inputPerso"/>
                                        </fieldset>
                                    </form>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>