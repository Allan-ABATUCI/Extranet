<?php
require 'view_begin.php';
require 'view_header.php';
?>

<form action="" method="post">
    <?php
    if ($type == 'Créneau') {
        echo "
            <div class='creneau-inp'>
                <label for='heure_arrivee'>Heure de début:</label>
                <input type='time' name='heure_arrivee[]'> 
                <label for='heure_depart'>Heure de fin:</label>
                <input type='time' name='heure_depart[]'> 
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
                        <?php echo ($type == "Créneau") ? '<th>Sélectionner</th>' : ''; ?>
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

                        // Input fields for heure_arrivee and heure_depart
                    

                        if ($type == 'Créneau') {
                            echo '<td class = "time"><input type="time" name="heure_arrivee[' . $i . ']"><input type="time" name="heure_depart[' . $i . ']"></td>';
                            echo '<td><input type="time" name="heures_supp[' . $i . ']"></td>';
                            echo '<td><input type="checkbox" class="checkbox" data-original-heure-arrivee="' . $i . '" data-original-heure-depart="' . $i . '"></td>';
                        } else {
                            //Si les deux sont cochés insert dans journee sinon demi-journée
                            echo '<td><input type="checkbox" name="presence_matin[' . $i . ']"> Matin <input type="checkbox" name="presence_apres_midi[' . $i . ']"> Après-midi </td>';
                            echo '<td><input type="time" name="heures_supp[' . $i . ']"></td>';
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

<script src="Content/js/table.js">

</script>

<?php require 'view_end.php'; ?>