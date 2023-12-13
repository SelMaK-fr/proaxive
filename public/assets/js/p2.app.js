/*###########################
BY SELMAK
Create the 2023/11/05
 */

/***
 1 -
 ###########################*/

/**
 * Update field in Ajax
 * @param url
 * @param id
 * @param target
 * @param confirm
 */
function updatePostFieldData(url, id, target = null, confirm = null)
{
    let idName = '#' + id
    let formdata = document.querySelector(idName);
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        ajax = new XMLHttpRequest();
    } else {// code for IE6, IE5
        ajax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    ajax.open('POST', url,true);
    ajax.send(new FormData(formdata));
    ajax.onreadystatechange = function ()
    {
        if(ajax.readyState === 4 && ajax.status === 200)
        {
            if(target != null){
                document.getElementById(target).style.display = 'none';
            }
            if(confirm != null){
                document.getElementById(confirm).innerHTML = '<em>Valeur enregistrée</em>';
            }
            window.location.reload();
        }
    }
}

function ajaxUpdateDataUrl(url, id, target = null, confirm = null) {
    let formdata = document.getElementById(id);
    let form_data = new FormData(formdata);

    document.getElementById('submit').disabled = true;

    let ajax_request = new XMLHttpRequest()
    ajax_request.open('POST', url);
    ajax_request.send(form_data);
    console.log(form_data);

    ajax_request.onreadystatechange = function ()
    {
        if(ajax_request.readyState == 4 && ajax_request.status == 200)
        {
            document.getElementById('submit').disabled = false;
            document.getElementById(id).reset();
            let loader = document.getElementById("loader");
            let body = document.getElementById("body-wrapper");
            loader.style.display = 'block';
            body.style.overflow = 'hidden';
            setTimeout(function ()
            {
                window.location.reload();
            }, 2500)
        }
    }
}

/**
 * FileUpload Ajax
 * @returns {Promise<void>}
 */
async function uploadFile(url, name) {
    let formData = new FormData();
    let fileSelect = document.getElementById('avatar');
    let files = fileSelect.files;
    formData.append("avatar", files[0], files[0].name);
    await fetch(url, {
        method: "POST",
        body: formData
    });
    alert('le fichier a été téléversé avec succès.');
    window.location.reload();
}

/**
 *
 * @param url
 * @param formId
 * @param button
 * @returns {Promise<void>}
 */
async function ajaxPostModalData2(url, formId, button){
    let selector = "#" + formId
    let form = document.querySelector(selector)
    let b = document.getElementById(button)
    const request = new XMLHttpRequest();
    request.open("POST", url);
    request.send(new FormData(form));
    request.onreadystatechange = function ()
    {
        if(request.readyState === 4 && request.status === 200)
        {
            console.log(request.status);
            setTimeout(function ()
            {
               // window.location.reload();
            }, 3000)

        }
    }
}

async function ajaxPostModalData(url, formId) {
    let selector = "#" + formId
    let form = document.querySelector(selector)
    let formData = new FormData(form);
    await fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: formData
    })
        .then((response) => {
            if(response.ok){
                setTimeout(function ()
                {
                    window.location.reload();
                }, 3000)
            }
            return Promise.reject(response);
        })
}

(function () {
    const elems = document.getElementsByClassName('delete-confirm');
    let confirmIt = function (e) {
        if (!confirm('Are you sure?')) e.preventDefault();
    };
    for (let i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
})()