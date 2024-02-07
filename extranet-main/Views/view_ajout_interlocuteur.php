<!-- Formulaire permettant l'ajout de nouvel interlocuteur -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Interlocuteur Client</h1>
            <form action="?controller=<?= $_GET['controller'] ?>&action=ajout_interlocuteur_dans_composante<?php
            if (isset($_GET['id'])): echo '&id-composante=' . $_GET['id']; endif;
            if (isset($_GET['id-client'])): echo '&id-client=' . $_GET['id-client']; endif;
            ?>" method="post">
                <h2>Informations personnelles</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom-interlocuteur" class="input-case">
                    <input type="text" placeholder="Nom" name="nom-interlocuteur" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-interlocuteur' id='mail-1'
                       class="input-case">
                <?php if (!isset($_GET['id'])): ?>
                    <h2>Informations professionnelles</h2>
                    <input type="text" placeholder="Composante" name='composante' id='cpt' class="input-case">
                <?php endif; ?>
                <div class="buttons" id="create">
                    <button type="submit">Créer</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?>
