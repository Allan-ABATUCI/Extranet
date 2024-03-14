<?php
require 'view_begin.php';
require 'view_header.php'; ?>
<div class="bdl-container">
    <div class="bdl__table">
        <table class="bdl-table">
            <form action="">
                <?php

                if ($bdl['type_bdl'] == 'Heure') {
                    echo "
                <label for='heure_arrivee'>Heure de début:</label>
                <input type='time' name='heure_arrivee'>";
                    echo "
                <label for='heure_depart'>Heure de fin:</label>
                <input type='time' name='heure_depart'>";
                }
                ?>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>
                            <?= $bdl['type_bdl'] ?>
                        </th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody id="joursTableBody">

                    <?php
                    $mois = $bdl['mois'];
                    if ($mois == 1 || $mois == 3 || $mois == 5 || $mois == 7 || $mois == 8 || $mois == 10 || $mois == 12) {
                        $nbjour = 31;
                    } else if ($mois == '02') {
                        $nbjour = 28;
                    } else {
                        $nbjour = 30;
                    }
                    for ($i = 0; $i < $nbjour; $i++) {
                        echo '<tr>';
                        echo '<td class = "date">' . $bdl . '</td>';
                        if ($bdl['type_bdl'] == 'Heure') {
                            echo '<td>' . $data['heure_arrivee'] . " - " . $data['heure_depart'] . '</td>';
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </form>
        </table>
    </div>
    <button type="button" id="button-get-data">Enregistrer</button>
</div>
<?php
require 'view_end.php'
    ?>