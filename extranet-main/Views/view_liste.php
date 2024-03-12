<!-- Vue permettant de faire la liste d'un type de personne -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
<div class='liste-prestataire-contrainer'>
    <h1>
        <?= $title ?>
    </h1>
    <div class="element-recherche">
        <input type="text" id="recherche" name="recherche" placeholder="Rechercher un <?= $title ?>...">
        <?php if (
            ((str_contains($_GET['controller'], 'gestionnaire') || str_contains($_GET['controller'], 'administrateur')) && !isset($_GET['id']))
            || ((str_contains($_GET['controller'], 'prestataire') && isset($person[0]['annee'])))
        ) : ?>
            <button type="submit" class="button-primary" onclick="window.location='<?= $buttonLink ?>'">Ajouter
            </button>
        <?php endif; ?>
    </div>

    <div class="element-block">
        <?php foreach ($person as $p) : ?>
            <a href='<?= $cardLink ?><?php if (isset($p['mois']) || isset($p['annee'])) :
                                            echo '&annee=' . $p['annee'] . '&mois=' . $p['mois'];
                                        else :
                                            echo '&id=' . $p['id'];
                                        endif; ?>' class="block card">
                <h2>
                    <?php
                    if (array_key_exists('nom', $p)) :
                        echo $p['nom'] . ' ' . $p['prenom'];
                    endif;
                    if (array_key_exists('nom_client', $p) and array_key_exists('tel_client', $p)) :
                        echo $p['nom_client'];
                    endif;
                    if (array_key_exists('nom_composante', $p) and !array_key_exists('nom_client', $p)) :
                        echo "<p>" . $p['nom_composante'] . "</p>";
                    endif;
                    ?>
                </h2>
                <h3>
                    <?php
                    if (array_key_exists('mois', $p)) :
                        echo $p['annee'] . ' - ' . $p['mois'];
                    endif;
                    if (array_key_exists('nom_client', $p) and !array_key_exists('tel_client', $p)) :
                        echo $p['nom_client'];
                    endif;
                    if (array_key_exists('tel_client', $p)) :
                        echo $p['tel_client'];
                    endif;
                    ?>
                </h3>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php
require 'view_end.php';
?>