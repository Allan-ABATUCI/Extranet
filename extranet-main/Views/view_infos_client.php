<!-- Vue permettant de voir les informations de la société -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="composante-container">
        <form action="?controller=<?= $_GET['controller'] ?>&action=maj_infos_client&id=<?= $_GET['id'] ?>"
              method="post">
            <div class="infos-composante">
                <h2>Informations Société</h2>
                <div class="form-infos-composante">
                    <input type="text" placeholder="<?= $infos['nom_client'] ?>" name='client' id='cpt'
                           class="input-case">
                    <input type="tel" placeholder="<?= $infos['telephone_client'] ?>" id='sté' name='telephone-client'
                           class="input-case">
                </div>
                <div class="buttons" id="create">
                    <button type="submit">Enregistrer</button>
                </div>
            </div>
        </form>

        <div class="infos-container">
            <div class="infos__colonne">
                <h2>Interlocuteurs</h2>
                <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_interlocuteur_form&id-client=<?= $_GET['id'] ?>"
                   class="ajout"><i class="fa fa-solid fa-user-plus"></i> &nbsp; Ajouter</a>
                <?php foreach ($interlocuteurs as $i): ?>
                    <a href="?controller=<?= $_GET['controller'] ?>&action=infos_personne&id=<?= $i['id_personne'] ?>"
                       class="block">
                        <h3><?= $i['nom'] . ' ' . $i['prenom'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="infos__colonne">
                <h2>Composantes</h2>
                <?php if (!str_contains('commercial', $_GET['controller'])): ?>
                    <a href="?controller=<?= $_GET['controller'] ?>&action=ajout_composante_form" class="ajout"><i
                            class="fa fa-solid fa-user-plus"></i>
                        &nbsp; Ajouter</a>
                <?php else: ?>
                    <a href="" class="ajout"></a>
                <?php endif; ?>
                <?php foreach ($composantes as $c): ?>
                    <a href='?controller=<?= $_GET['controller'] ?>&action=infos_composante&id=<?= $c['id_composante'] ?>'
                       class="block">
                        <h3><?= $c['nom_composante'] ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php
require 'view_end.php';
?>
