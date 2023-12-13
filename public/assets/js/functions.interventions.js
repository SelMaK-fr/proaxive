/*###########################
BY SELMAK
Create the 2023/11/05
 */

/***
 1 - Update name Equipment (if modified in Equipement edition)
 2 - Start Intervention (add date of day)
###########################/

/**
 * 1-Update name Equipment
 * @param $id
 * @param $eId
 */
function updateNameEquipment($id, $eId)
{

    document.getElementById('submit').disabled = true;

    let ajax_request = new XMLHttpRequest()

    ajax_request.open('POST', "/admin/interventions/" + $id + "/ajax/equipment-update/" + $eId);
    ajax_request.send();

    ajax_request.onreadystatechange = function ()
    {
        if(ajax_request.readyState == 4 && ajax_request.status == 200)
        {
            document.getElementById('submit').style.display = 'none';
            document.getElementById('sample_form').reset();
            document.getElementById('message').innerHTML = "Nouveau nom d'équipment enregistré.";
            setTimeout(function ()
            {
                document.getElementById('alert-ajax').style.display = 'none';
            }, 3000)
        }
    }
}


