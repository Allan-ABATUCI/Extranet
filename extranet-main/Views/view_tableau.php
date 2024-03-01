<!-- Vue permettant au prestataire de voir les missions qui lui ont été assignées -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class='main-contrainer'>
    <div class="dashboard-container">
        <h1>
            <?= $title ?>
        </h1>

        <?php require_once 'view_dashboard.php'; ?>
        <?php if ($_GET['controller'] == 'gestionnaire' || $_GET['controller'] == 'interlocuteur' || $_GET['controller'] == 'administrateur') {
            echo '            <div class="add-mission-container"><button type="button" class="button-primary" onclick="window.location=' . $buttonLink . '">+ Créer
                Mission</button></div>';
        } ?>
    </div>
</div>

<?php
require 'view_end.php';
?>