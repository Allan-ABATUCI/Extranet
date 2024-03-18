<?php
require 'view_begin.php';
require 'view_header.php'; ?>

<form action="">
    <?php
    if ($type == 'Créneau') {
        echo "
            <div class='creneau'>
                <label for='heure_arrivee'>Heure de début:</label>
                <input type='time' name='heure_arrivee'>
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
                        <th>heures supplémentaires</th>
                        <th>
                            <?= $type ?>
                        </th>
                        <th>Sélectionner</th>
                    </tr>
                </thead>
                <tbody id="joursTableBody">
                    <?php
                    $mois = $bdl[0]['mois'];
                    if ($mois == 1 || $mois == 3 || $mois == 5 || $mois == 7 || $mois == 8 || $mois == 10 || $mois == 12) {
                        $nbjour = 31;
                    } else if ($mois == 2) {
                        $nbjour = 28;
                    } else {
                        $nbjour = 30;
                    }

                    // Création d'un formateur de date avec la langue française
                    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                    for ($i = 1; $i <= $nbjour; $i++) {
                        // Obtient le timestamp pour la date
                        $timestamp = mktime(0, 0, 0, $mois, $i, $bdl[0]['annee']);
                        $date = new DateTime(date('Y-m-d', $timestamp));

                        echo '<tr>';
                        // Formate la date en français et l'affiche dans la colonne de date
                        echo '<td class="date">' . $formatter->format($date->getTimestamp()) . '</td>';

                        if ($type == 'Créneau') {
                            if (isset($data['heure_arrivee']) && isset($data['heure_depart'])) {
                                echo '<td></td>'; // Cellule vide pour les heures supplémentaires
                                echo '<td class="creneau">' . $data['heure_arrivee'] . " - " . $data['heure_depart'] . '</td>';
                            } else {
                                echo '<td></td>'; // Cellule vide pour les heures supplémentaires
                                echo '<td class="creneau"></td>'; // Cellule vide pour 'Créneau'
                                echo '<td><input type="checkbox" class="checkbox"></td>'; // Colonne pour la case à cocher
                            }
                        } else {
                            echo '<td></td>'; // Cellule vide pour la valeur 'Créneau' si le type n'est pas 'Créneau'
                            echo '<td></td>'; // Cellule vide pour les heures supplémentaires si le type n'est pas 'Créneau'
                        }

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <button type="submit" id="button-get-data">Enregistrer</button>
    </div>
</form>

<script src="Content/js/table.js"></script>

<?php require 'view_end.php'; ?>