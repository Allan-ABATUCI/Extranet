<!-- Formulaire permettant d'ajouter un nouveau commercial  -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Ajout Commercial</h1>
            <form
                action="?controller=<?php $_GET['controller'] ?>>&action=<?php if(isset($_GET['id'])): echo 'ajout_commercial_dans_composante&id-composante=' . $_GET['id']; else: echo 'ajout_commercial'; endif;?>"
                method="post">
                <h2>Informations personnelles</h2>
                <div class="form-names">
                    <input type="text" placeholder="Prénom" name="prenom" class="input-case">
                    <input type="text" placeholder="Nom" name="nom" class="input-case">
                </div>
                <input type="email" placeholder="Adresse email" name='email-commercial' id='mail-1' class="input-case">
                <?php if (!isset($_GET['id-composante'])): ?>
                    <h2>Informations professionnelles</h2>
                    <input type="text" placeholder="Société" name='client' id='sté' class="input-case">
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
