<html>
    <head>  
        <meta charset="utf-8"/>
        <title>Admin</title>

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

        <form id="formUtilisateur" class="formAdmin">
            <fieldset class="fieldsetAdmin">
                <legend>Gestion des adminstrateurs</legend>
                <br/><select id="selectUtilisateur">
                    <option selected disabled>Choississez un utilisateur</option>
                </select><br/><br/>
                <input type="submit" value="Donner les droits d'administrateur"/>
            </fieldset>
        </form>

        <form id="formAdmin" class="formAdmin">
            <fieldset class="fieldsetAdmin">
                <legend>Gestion des adminstrateurs</legend>
                <br/><select id="selectAdmin">
                    <option selected disabled>Choississez un adminstrateur</option>
                </select><br/><br/>
                <input type="submit" value="Retirer les droits d'aministrateur"/>
            </fieldset>
        </form>

        <form id="ajoutCat" class="formAdmin">
            <fieldset class="fieldsetAdmin">
                <legend>Ajout de catégories</legend>
                <br/><input type="text" id="nouvelleCat" name="nomCat" placeholder="Nom catégorie"/><br/><br/>
                <input type="submit" id="submitAjoutCat" value="Ajouter"/>
            </fieldset>
        </form>

        <form id="suppressionCat" class="formAdmin">
            <fieldset class="fieldsetAdmin">
                <legend>Suppression de catégories</legend>
                <br/><select id="selectCategorie" name="categorie">
                    <option selected disabled>Choississez une catégorie</option>
                </select><br/><br/>
                <input type="submit" id="submitSuppressionCat" value="Supprimer"/>
            </fieldset>
        </form>

        <form id="suppressionListe" class="formAdmin">
            <fieldset class="fieldsetAdmin">
                <legend>Suppression de listes de flahscards</legend>
                <br/><select id="selectListe" name="liste">
                    <option selected disabled>Choississez une liste de flashcards</option>
                </select><br/><br/>
                <input type="submit" id="submitSuppressionListe" value="Supprimer"/>
            </fieldset>
        </form>

        <script>
            $(document).ready(function() {
                //Ajout des utilisateurs dans la liste des utilisateurs
                $.ajax({
                    url : "SelectUtilisateurs.php",
                    type : "post",
                    dataType : "json",
                    success : function(data) {
                        for(utilisateur of data) {
                            if(utilisateur.niveauGestion == 0) {
                                $("#selectUtilisateur").append("<option>" + utilisateur.pseudo + "</option>");
                            } else {
                                $("#selectAdmin").append("<option>" + utilisateur.pseudo + "</option>");
                            }
                        }
                    }
                });
                //Passage d'un utilisateur en admin
                $("#formUtilisateur").submit(function() {
                    var pseudo = $("#selectUtilisateur option:selected").text();
                    if(pseudo != "Choississez un utilisateur") {
                        $.ajax({
                            url : "UpdateToAdmin.php",
                            type : "post",
                            data : {"pseudo" : pseudo},
                            dataType : "json"
                        });
                    }
                });
                //Passage d'un adminstrateur en utilisateur
                $("#formAdmin").submit(function() {
                    var pseudo = $("#selectAdmin option:selected").text();
                    if(pseudo != "Choississez un adminstrateur") {
                        $.ajax({
                            url : "UpdateToUtilisateur.php",
                            type : "post",
                            data : {"pseudo" : pseudo},
                            dataType : "json"
                        });
                    }
                });
                //Ajout des catégories dans le select pour la suppression
                $.ajax({
                    url : "SelectCategorie.php",
                    dataType : "json",
                    success : function(data) {
                        for(var categorie of data) {
                            $("#selectCategorie").append("<option>" + categorie.nom + "</option>");
                        }  
                    }
                });
                //Ajout d'une catégorie
                $("#ajoutCat").submit(function() {
                    var data = $(this).serialize();
                    $(this).find("input").prop("disabled", true);
                    $.post("InsertCategorie.php", data, "json");
                    $(this).find("input").prop("disabled", false);
                });
                //Suppression d'une catégorie
                $("#suppressionCat").submit(function(e) {
                    var data = $(this).serialize();
                    $(this).find("select").prop("disabled", true);
                    $.post("DeleteCategorie.php", data, "json");
                    $(this).find("select").prop("disabled", false);
                });
                //Ajout des listes de flashcards dans le select pour la suppression
                $.ajax({
                    url : "SelectAllListeFlashcards.php",
                    dataType : "json",
                    success : function(data) {
                        for(liste of data) {
                            $("#selectListe").append("<option value=" + liste.id_ListeFlashcard + ">" + liste.nom + "</option>");
                        }
                    }
                });
                //Suppression d'une liste de flashcards
                $("#suppressionListe").submit(function(e) {
                    var id = parseInt($("#selectListe option:selected").val());
                    console.log(id);
                    $.ajax({
                        url : "DeleteListeFlashcardAdmin.php",
                        type : "post",
                        data : {"id" : id},
                        dataType : "json"
                    });
                })
            });
        </script>
    </body>
</html>