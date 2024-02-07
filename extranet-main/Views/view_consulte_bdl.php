<!-- Vue permettant de consulter un bdl et si il est interlocuteur client de le contester ou valider -->

<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class="bdl-container">
    <div class="bdl__table">

        <table class="bdl-table">
            <thead>
                <tr class="bdl-head">
                    <th>Date</th>
                    <th><?= $bdl['type_bdl'] ?></th>
                    <th>Commentaire</th>
                </tr>
            </thead>
            <tbody id="joursTableBody">
                <?php if (is_array($activites) || is_object($activites)) :?>
                <?php foreach ($activites as $activite) : ?>
                    <tr>
                        <td><?= $activite["date_bdl"] ?></td>
                        <td><?php
                            if (isset($activite['journee'])): if ($activite['journee']): echo 'Présent';
                            else: echo 'Absent'; endif; endif;
                            if (isset($activite['nb_heure'])): echo $activite['nb_heure']; endif;
                            if (isset($activite['nb_demi_journee'])): echo $activite['nb_demi_journee']; endif;
                            ?></td>
                        <td><?= $activite["commentaire"] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (str_contains($_GET['controller'], 'interlocuteur')): ?>
        <div class="button-valide-container">
            <button class="button-delete button-valide"
                    onclick="window.location='?controller=interlocuteur&action=valider_bdl&id=<?php echo $_GET['id'] ?>&valide=false'"
                    id="button-get-data">Contester
            </button>
            <button class="button-primary button-valide"
                    onclick="window.location='?controller=interlocuteur&action=valider_bdl&id=<?php echo $_GET['id'] ?>&valide=true'"
                    id="button-get-data">Valider
            </button>
        </div>
    <?php endif; ?>
</div>
<?php
require 'view_end.php';
?>
