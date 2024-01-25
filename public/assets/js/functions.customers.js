/**
 * Permet de générer un ID unique
 */
function generateIdCustomer(){
    const generateRandomString = (num) => {
        const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result1= '';
        const charactersLength = characters.length;
        for ( let i = 0; i < num; i++ ) {
            result1 += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        return result1;
    }
    document.getElementById('form_customer-login_id').value= 'C-' + generateRandomString(9);
}

/**
 *
 * @param $id
 */
function updateNoteCustomer($id)
{
    let formdata = document.getElementById('sample_form');
    let form_data = new FormData(formdata);

    for(let count = 0; count < form_element.length; count++)
    {
        form_data.append(form_element[count].name, form_element[count].value);
    }
    document.getElementById('submit').disabled = true;

    let ajax_request = new XMLHttpRequest()
    ajax_request.open('POST', "/admin/customers/" + $id + "/ajax/note-update");
    ajax_request.send(form_data);
    console.log(form_data);

    ajax_request.onreadystatechange = function ()
    {
        if(ajax_request.readyState == 4 && ajax_request.status == 200)
        {
            document.getElementById('submit').disabled = false;
            document.getElementById('sample_form').reset();
            document.getElementById('message').innerHTML = 'Sauvegardé';
            let loader = document.getElementById("loader");
            let body = document.getElementById("body-wrapper");
            loader.style.display = 'block';
            body.style.overflow = 'hidden';
            setTimeout(function ()
            {
                document.getElementById('message').innerHTML = '';
                window.location.reload();
            }, 2500)
        }
    }
}

/**
 *
 * @param $id
 */
function updateTypeCustomer($id)
{
    let form_data = new FormData();
    let ajax_request = new XMLHttpRequest();
    ajax_request.open('POST', "/admin/customers/" + $id + "/ajax/type-update");
    ajax_request.send(form_data);
}

/** View Map **/
function viewMap(la, lo) {
    const btnDeployMap = document.getElementById('btnDeployMap');
    // On initialise la latitude et la longitude de Paris (centre de la carte)
    let lat = la;
    let lon = lo;
    let macarte = null;
    // Fonction d'initialisation de la carte
    function initMap() {
        // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
        macarte = L.map('map').setView([lat, lon], 11);
        // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
            // Il est toujours bien de laisser le lien vers la source des données
            attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
            minZoom: 1,
            maxZoom: 40
        }).addTo(macarte);
    }
    window.onload = function(){
        // Fonction d'initialisation qui s'exécute lorsque le DOM est chargé
        initMap();
        btnDeployMap.addEventListener('click', function(e){
            document.getElementById('container-map').classList.toggle("map-visible");
            setTimeout(function() {
                macarte.invalidateSize();
            }, 100);
        });
        // Nous ajoutons un marqueur
        let marker = L.marker([lat, lon]).addTo(macarte);
    };
}

// Deploy Map


// PROGRESS CUSTOMER (PROFIL)
// Inspired by https://codepen.io/davatron5000/pen/jzMmME
// Get all the Meters
const meters = document.querySelectorAll('svg[data-value] .meter');
if(meters){
    meters.forEach((path) => {
        // Get the length of the path
        let length = path.getTotalLength();

        // console.log(length);

        // Just need to set this once manually on the .meter element and then can be commented out
        // path.style.strokeDashoffset = length;
        // path.style.strokeDasharray = length;

        // Get the value of the meter
        let value = parseInt(path.parentNode.getAttribute('data-value'));
        // Calculate the percentage of the total length
        let to = length * ((100 - value) / 100);
        // Trigger Layout in Safari hack https://jakearchibald.com/2013/animated-line-drawing-svg/
        path.getBoundingClientRect();
        // Set the Offset
        path.style.strokeDashoffset = Math.max(0, to);   path.nextElementSibling.textContent = `${value}%`;
    });
}

