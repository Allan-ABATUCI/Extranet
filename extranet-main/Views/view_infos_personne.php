<!-- Vue permettant de voir les informations d'une personne quelconque sur laquelle on a cliqué -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="add-container">
        <div class="form-abs">
            <h1>Informations</h1>
            <form action="?controller=<?= $_GET['controller'] ?>&action=maj_infos_personne&id=<?= $person['id_personne'] ?>" method="post">
                <div class="form-names">
                    <input type="text" placeholder="<?= $person['prenom'] ?>" name="prenom" class="input-case">
                    <input type="text" placeholder="<?= $person['nom'] ?>" name="nom" class="input-case">
                </div>
                <input type="email" placeholder="<?= $person['email'] ?>" name='email' id='mail-1' class="input-case">
                <div class="buttons" id="create">
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
<?php
require 'view_end.php';
?><?php
