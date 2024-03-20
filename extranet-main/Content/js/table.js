 document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll('.checkbox');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const row = this.closest('tr');
                const heureArriveeInput = row.querySelector('input[name^="heure_arrivee"]');
                const heureDepartInput = row.querySelector('input[name^="heure_depart"]');

                if (this.checked) {
                    // Save original values
                    heureArriveeInput.dataset.originalValue = heureArriveeInput.value;
                    heureDepartInput.dataset.originalValue = heureDepartInput.value;

                    // Set values from creneau-inp
                    const creneauHeureArrivee = document.querySelector('.creneau-inp input[name="heure_arrivee[]"]').value;
                    const creneauHeureDepart = document.querySelector('.creneau-inp input[name="heure_depart[]"]').value;

                    heureArriveeInput.value = creneauHeureArrivee;
                    heureDepartInput.value = creneauHeureDepart;
                } else {
                    // Restore original values
                    heureArriveeInput.value = heureArriveeInput.dataset.originalValue;
                    heureDepartInput.value = heureDepartInput.dataset.originalValue;
                }
            });
        });
    });