    // Ajouter un gestionnaire d'événements aux cases à cocher
    document.querySelectorAll('.checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var row = this.closest('tr'); // Obtenir la ligne la plus proche
            var creneauColumn = row.querySelector('.creneau'); // Obtenir la colonne pour la valeur 'Créneau'
            
            // Stocker la valeur d'origine de la cellule 'Créneau'
            var originalCreneauValue = creneauColumn.textContent;

            var heureArriveeInput = document.querySelector('[name="heure_arrivee"]');
            var heureDepartInput = document.querySelector('[name="heure_depart"]');

            // Vérifier si la case à cocher est cochée
            if (this.checked) {
                // Vérifier si les entrées heure_arrivee et heure_depart sont remplies
                if (heureArriveeInput.value.trim() !== '' && heureDepartInput.value.trim() !== '') {
                    // Définir le contenu de la colonne 'Créneau' avec les valeurs des entrées
                    creneauColumn.textContent = heureArriveeInput.value + ' - ' + heureDepartInput.value;
                } else {
                    alert("remplissez l'heure d'arrivée et l'heure de départ avant de sélectionner les lignes.");
                    // Décocher la case si les entrées ne sont pas remplies
                    this.checked = false;
                }
            } else {
                // Si la case à cocher est décochée, revenir à la valeur d'origine de la cellule 'Créneau'
                creneauColumn.textContent = originalCreneauValue;
            }
        });
    })
