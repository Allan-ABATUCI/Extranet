<!-- Vue permettant au gestionnaire de voir toutes les missions et d'en créer une -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>

<div class='main-contrainer'>
    <div class="dashboard-container">
        <h1>Missions</h1>
        <?php require_once 'view_dashboard.php'; ?>
        <div class="add-mission-container">
            <button type="button" class="button-primary" onclick="window.location='<?= $buttonLink ?>'">+ Créer Mission</button>
        </div>
    </div>
</div>

<?php
require 'view_end.php';
?>
