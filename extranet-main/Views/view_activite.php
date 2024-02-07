<!-- Créer le bon de livraison pour qu'on puisse le remplir -->
<?php
require 'view_begin.php';
require 'view_header.php';
?>
    <div class="bdl-container">
        <div class="bdl__table">
            <table class="bdl-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th><?= $bdl['type_bdl'] ?></th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody id="joursTableBody">

                </tbody>
            </table>
        </div>
        <button type="button" onclick="getTableData()" id="button-get-data">Enregistrer</button>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        submitForm(); // Appeler la fonction submitForm une fois que le DOM est chargé
      });

      function submitForm() {
        let mois = <?= explode('-', $bdl['mois'])[1] ?>;
        let nbjour;

        if (mois == '01' || mois == '03' || mois == '05' || mois == '07' || mois == '08' || mois == '10' || mois == '12') {
          nbjour = 31;
        } else if (mois == '02') {
          nbjour = 28;
        } else {
          nbjour = 30;
        }

        // Récupérer la table du corps
        var joursTableBody = document.getElementById('joursTableBody');
        joursTableBody.innerHTML = ''; // Réinitialiser le contenu actuel

        // Ajouter dynamiquement une ligne pour chaque jour
        for (let jour = 1; jour <= nbjour; jour++) {
          var newRow = joursTableBody.insertRow();

            <?php $i = 0; ?>
            <?php $i = $i + 1; ?>

          for (let i = 0; i < 3; i++) {
            var cell = newRow.insertCell(i);

            // Si c'est la colonne "Date", affiche la valeur du jour
            if (i === 0) {
              cell.textContent = jour + '-' + mois + '-' + 2024;
            } else {
              // Sinon, crée un input de type texte dans la 2ème et 3ème colonne
              var input = document.createElement('input');
              input.type = 'text'; // Tu peux changer le type d'input selon tes besoins
              input.name = 'inputJour' + jour + '_' + i; // Nom unique pour chaque input
              input.classList.add('input-bdl-form');

              // Si c'est la colonne "Nombre d'Heures" (2ème colonne), définir la valeur par défaut à 0
              if (i === 1) {
                input.placeholder = '0';
              } else if (i === 2) {
                // Si c'est la colonne "Commentaire" (3ème colonne), définir un placeholder ou une valeur par défaut si nécessaire
                input.placeholder = 'Commentaire';
              }
              cell.appendChild(input);
            }
          }
        }
      }

      //--------------------------------

      function getTableData() {
        var tableData = [];

        // Récupérer la table du corps
        var joursTableBody = document.getElementById('joursTableBody');
        var rows = joursTableBody.getElementsByTagName('tr');

        // Parcourir chaque ligne du tableau
        for (var i = 0; i < rows.length; i++) {
          var rowData = [];

          // Récupérer les cellules de la ligne
          var cells = rows[i].getElementsByTagName('td');

          // Parcourir chaque cellule de la ligne
          for (var j = 0; j < cells.length; j++) {
            // Vérifier si la cellule contient un input
            var input = cells[j].querySelector('input');

            if (input) {
              // Ajouter la valeur de l'input au tableau
              rowData.push(input.value);
            } else {
              // Si ce n'est pas un input, ajouter le texte de la cellule au tableau
              rowData.push(cells[j].textContent);
            }
          }

          // Ajouter les données de la ligne au tableau principal
          tableData.push(rowData);
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '?controller=prestataire&action=completer_bdl&id=<?= $bdl['id_bdl'] ?>&type=<?= $bdl['type_bdl'] ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4) {
            if (xhr.status == 200) {
              console.log(xhr.responseText); // Afficher la réponse du serveur dans la console
              // Rediriger l'utilisateur vers une autre page ici
              window.location.href = '?controller=prestataire&action=afficher_bdl&id=<?= $bdl['id_bdl'] ?>';
            } else {
              console.error('Erreur lors de la requête AJAX');
            }
          }
        };
        xhr.send(JSON.stringify(tableData));
      }

    </script>

<?php
require 'view_end.php';
?>
