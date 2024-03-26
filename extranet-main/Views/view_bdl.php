<?php
require 'view_begin.php';
require 'view_header.php';

// Determine the action URL based on the session role
$actionUrl = ($_SESSION['role'] != "prestataire") ? "?controller=admin&action=valider" : "?controller=prestataire&action=valider";

echo "<form action='{$actionUrl}' method='post'>";

// Button text and form opening based on session role
if ($_SESSION['role'] != "prestataire") {
    $buttonText = "Valider"; // Change le texte du bouton à "Valider"
} else {
    $buttonText = "Enregistrer"; // Garde le texte du bouton à "Enregistrer"
}

if ($type == 'Créneau') {
    // Affiche les champs de saisie pour heure_arrivee et heure_depart si le rôle est "prestataire"
    if ($_SESSION['role'] == "prestataire") {
        echo "
            <div class='creneau-inp'>
                <label for='heure_arrivee'>Heure de début:</label>
                <input type='time' name='heure_arrivee[]' value='" . (isset ($data['heure_arrivee']) ? $data['heure_arrivee'] : '') . "' $inputReadOnly> 
                <label for='heure_depart'>Heure de fin:</label>
                <input type='time' name='heure_depart[]' value='" . (isset ($data['heure_depart']) ? $data['heure_depart'] : '') . "' $inputReadOnly> 
            </div>";
    }
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
                    <?php
                    // Ajoute la colonne "Sélectionner" si le rôle est "prestataire" et si le type est "Créneau"
                    if ($_SESSION['role'] == "prestataire" && $type == "Créneau") {
                        echo '<th>Sélectionner</th>';
                    }
                    ?>
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

                // Crée un formateur de date avec la langue française
                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                for ($i = 1; $i <= $nbjour; $i++) {
                    // Obtient le timestamp pour la date
                    $timestamp = mktime(0, 0, 0, $mois, $i, $bdl[0]['annee']);
                    $date = new DateTime(date('Y-m-d', $timestamp));

                    echo '<tr>';
                    // Formate la date en français et l'affiche dans la colonne de date
                    echo '<td class="date">' . $formatter->format($date->getTimestamp()) . '</td>';

                    if ($type == 'Créneau') {
                        // Affiche les champs de saisie pour heure_arrivee et heure_depart si le rôle est "prestataire"
                        echo '<td class = "time"><inplut type="time" name="heure_arrivee[' . $i . ']" value="' . (isset ($data['heure_arrivee'][$i]) ? $data['heure_arrivee'][$i] : '') . '" ' . $inputReadOnly . '><input type="time" name="heure_depart[' . $i . ']" value="' . (isset ($data['heure_depart'][$i]) ? $data['heure_depart'][$i] : '') . '" ' . $inputReadOnly . '></td>';
                        // Affiche la case à cocher de sélection si le rôle est "prestataire"
                        if ($_SESSION['role'] == "prestataire") {
                            echo '<td><input type="time" name="heures_supp[' . $i . ']" value="' . (isset ($data['heures_supp'][$i]) ? $data['heures_supp'][$i] : '') . '" ' . $inputReadOnly . '></td>';
                            echo '<td><input type="checkbox" class="checkbox" data-original-heure-arrivee="' . $i . '" data-original-heure-depart="' . $i . '"></td>';
                        } elseif ($type == 'Journée') {

                        }
                    } else {
                        // Affiche les cases à cocher pour presence_matin et presence_apres_midi si le rôle est "prestataire"
                        if ($_SESSION['role'] == "prestataire") {
                            echo '<td><input type="checkbox" name="presence_matin[' . $i . ']"> Matin <input type="checkbox" name="presence_apres_midi[' . $i . ']"> Après-midi </td>';
                        }
                        echo '<td><input type="time" name="heures_supp[' . $i . ']" value="' . (isset ($data['heures_supp'][$i]) ? $data['heures_supp'][$i] : '') . '" ' . $inputReadOnly . '></td>';
                    }

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="center"><button class="button-primary " type="submit">
        <?= $buttonText ?>
    </button></div>


</form>

<script src="Content/js/table.js"></script>

<?php
require 'view_end.php';
?>