<!-- Formulaire permettant à l'administrateur d'ajouter un nouveau gestionnaire  -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class="add-container">
    <div class="form-abs">
        <h1>Ajout Gestionnaire</h1>
        <form
            action="?controller=administrateur&action=ajout_gestionnaire"
            method="post">
            <h2>Informations personnelles</h2>
            <div class="form-names">
                <input type="text" placeholder="Prénom" name="prenom" class="input-case">
                <input type="text" placeholder="Nom" name="nom" class="input-case">
            </div>
            <input type="email" placeholder="Adresse email" name='email-gestionnaire' id='mail-1' class="input-case">
            <div class="buttons" id="create">
                <button type="submit">Créer</button>
            </div>
        </form>
    </div>
</div>

<?php
require 'view_end.php';
?>
