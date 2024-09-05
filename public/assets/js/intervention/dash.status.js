document.addEventListener('DOMContentLoaded', function() {
    // Sélectionnez l'élément <select>
    let selectElement = document.getElementById('form_intervention_update-status_id');
    const interventionId = document.querySelector(".update-intervention").getAttribute('data-id-intervention');
    let url = '/admin/interventions/ajax/update-status/'

    // Ecoute de l'événement au clique
    selectElement.addEventListener('change', function() {
        // Récupérez la valeur option
        let selectedValue = selectElement.value;
        // Vérifiez que la valeur sélectionnée n'est pas vide
        if (selectedValue) {
            // Création du tableau Json data
            let data = {
                status_id: selectedValue
            }
            // Requête XMLHttpRequest
            let xhr = new XMLHttpRequest();

            // Configurez la requête (type, URL, asynchrone)
            xhr.open('POST', url + interventionId, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            // Réponse
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let loader = document.getElementById("loader");
                    let body = document.getElementById("body-wrapper");
                    loader.style.display = 'block';
                    body.style.overflow = 'hidden';
                    setTimeout(function ()
                    {
                        window.location.reload();
                    }, 2500)
                }
            };
            // Envoyez la requête avec le tableau json
            xhr.send(JSON.stringify(data));
        }
    });
});