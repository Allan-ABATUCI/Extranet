<?php
require 'view_begin.php';
require 'view_header.php'; ?>
<form action="">
    <?php

    if ($type == 'creneau') {
        echo "
                    <div class='creneau'>
                        <label for='heure_arrivee'>Heure de début:</label>
                        <input type='time' name='heure_arrivee'>";
        echo "
                        <label for='heure_depart'>Heure de fin:</label>
                        <input type='time' name='heure_depart'>
                </div>";
    }
    ?>
    <div class="bdl-container">


        <div class="bdl__table">
            <table class="bdl-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>
                            <?= $type ?>
                        </th>
                        <th>heures supplémentaires</th>
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
                    // Set the locale to French
                    setlocale(LC_TIME, 'fr_FR.UTF-8');

                    for ($i = 0; $i < $nbjour; $i++) {
                        // Get the timestamp for the date
                        $timestamp = mktime(0, 0, 0, $bdl['mois'], $i, $bdl['annee']);

                        echo '<tr>';
                        // Format the date to include the day of the week in French
                        echo '<td class="date">' . strftime('%A %d %B %Y', $timestamp) . '</td>';

                        if ($type == 'Créneau') {
                            echo '<td>' . $data['heure_arrivee'] . " - " . $data['heure_depart'] . '</td>';
                        }

                        echo '</tr>';
                    }

                    ?>
                </tbody>

            </table>
        </div>

        <button type="button" id="button-get-data">Enregistrer</button>
    </div>
</form>
<?php
require 'view_end.php'
    ?>