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

let count = 1;
function new_line(type = null){
    count++;
    let e = document.createElement("tr");
    e.id = count, e.className = "customer";
    //getTableElement = document.querySelector('.item-table');
    // currentIndex = getTableElement.rows.length;
    let t = '<tr>'+
        '<th scope="row" class="fw-500 product-id">'+ count +'</th>'+
        '<td><div class="mb-2"><input type="text" id="productName-'+ count +'" class="form-control" placeholder="Titre du produit" required></div><textarea class="form-control d-block mt-2" name="" id="productDetail-'+ count +'" rows="2" placeholder="Description du produit"></textarea></td>'+
        '<td class="text-right qty"><input type="text" class="form-control product-price" id="productRate-'+ count +'" placeholder="Price"></td>'+
        '<td>'+
        '<div class="input-qty">'+
        '<button type="button" class="minus">–</button>'+
        '<input type="number" id="product-qty-'+ count +'" class="product-quantity" value="0" placeholder="Qte" readonly>'+
        '<button type="button" class="plus">+</button>'+
        '</div>'+
        ' </td>'+
        '<td class="text-right price"><div><input type="text" class="form-control product-line-price" id="productPrice-'+ count +'" name="" placeholder="€0.00" readonly></div></td>'+
        '<td class="text-center fs-22px delete-item-row"><button type="button" class="btn-sm btn-success delete-item">Retirer</button></td>'+
        '</tr>';
    e.innerHTML = document.getElementById("newForm").innerHTML + t, document.getElementById("newline").appendChild(e);
    remove()
}
remove();
/**
 * Call remove item product
 */
function remove() {
    Array.from(document.querySelectorAll(".delete-item")).forEach(function(e) {
        e.addEventListener("click", function(e) {
            removeItem(e)
        })
    })
}
/**
 * Remove product table
 */
function removeItem(e) {
    e.target.closest("tr").remove()
}

/** View Map **/
function viewMap(la, lo) {
    // On initialise la latitude et la longitude de Paris (centre de la carte)
    var lat = la;
    var lon = lo;
    var macarte = null;
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
        // Nous ajoutons un marqueur
        var marker = L.marker([lat, lon]).addTo(macarte);
    };
}

// Aside Mobile Device
const btnDeployMap = document.getElementById('btnDeployMap');
btnDeployMap.addEventListener('click', function(e){
    document.getElementById('container-map').classList.toggle("map-visible");
});