<!-- Formulaire permettant d'ajouter une nouevlle société  -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Société</h1>
            <form action="?controller=<?php $_GET['controller'] ?>&action=ajout_client" method="post">
                <h2>Informations société</h2>
                <input type="text" placeholder="Société" id='sté' name='client' class="input-case">
                <input type="tel" placeholder="Numéro de téléphone" name='tel' class="input-case" autocomplete="tel">
                <h2>Informations composante</h2>
                <input type="text" placeholder="Nom de la mission" name='mission' class="input-case">
                <input type="text" placeholder="Composante" name='composante' class="input-case">
                <div class="form-names">
                    <select name="type-bdl">
                        <option selected>Type de bon de livraison </option>
                        <option value="journee">Journée </option>
                        <option value="demi-journee">Demi-journée </option>
                        <option value="heure">Heure </option>
                    </select>
                    <input type="date" placeholder="Date de début" name="date-mission" class="input-case">
                </div>
                <h4>Adresse</h4>
                <div class="form-address">
                    <input type="number" placeholder="Numéro de voie" name="numero-voie"
                           class="input-case form-num-voie">
                    <input type="text" placeholder="Type de voie" name="type-voie" class="input-case form-type-voie">
                    <input type="text" placeholder="Nom de voie" name="nom-voie" class="input-case form-nom-voie">
                </div>
                <div class="form-address">
                    <input type="number" placeholder="Code postal" name="cp" class="input-case form-cp">
                    <input type="text" placeholder="Ville" name="ville" class="input-case form-ville">
                </div>
                <h2>Informations interlocuteur</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom-interlocuteur" class="input-case">
                    <input type="text" placeholder="Nom" name="nom-interlocuteur" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-interlocuteur'
                       class="input-case">
                <h2>Informations commercial</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom-commercial" class="input-case">
                    <input type="text" placeholder="Nom" name="nom-commercial" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-commercial' class="input-case">
                <div class="buttons" id="create">
                    <button type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?>
