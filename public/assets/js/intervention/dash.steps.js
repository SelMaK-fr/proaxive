const one = document.querySelector(".one");
const two = document.querySelector(".two");
const three = document.querySelector(".three");
const four = document.querySelector(".four");
const five = document.querySelector(".five");
const interventionId = document.querySelector(".update-intervention").getAttribute('data-id-intervention');
const url = '/admin/interventions/ajax/update-steps/';
one.onclick = function() {
    postAjax(1);
    one.parentElement.classList.add("active");
    two.parentElement.classList.remove("active", "current");
    three.parentElement.classList.remove("active", "current");
    four.parentElement.classList.remove("active", "current");
    five.parentElement.classList.remove("active", "current");
}

two.onclick = function() {
    postAjax(2);
    one.parentElement.classList.add("active");
    two.parentElement.classList.add("current");
    three.parentElement.classList.remove("active", "current");
    four.parentElement.classList.remove("active", "current");
    five.parentElement.classList.remove("active", "current");
}
three.onclick = function() {
    postAjax(3);
    one.parentElement.classList.add("active");
    two.parentElement.classList.add("active");
    two.parentElement.classList.remove("current");
    three.parentElement.classList.add("current");
    four.parentElement.classList.remove("active", "current");
    five.parentElement.classList.remove("active", "current");
}
four.onclick = function() {
    postAjax(4);
    one.parentElement.classList.add("active");
    two.parentElement.classList.add("active");
    three.parentElement.classList.add("active");
    three.parentElement.classList.remove("current");
    four.parentElement.classList.add("current");
    five.parentElement.classList.remove("active", "current");
}
five.onclick = function() {
    postAjax(5);
    one.parentElement.classList.add("active");
    two.parentElement.classList.add("active");
    three.parentElement.classList.add("active");
    four.parentElement.classList.add("active");
    four.parentElement.classList.remove("current");
    five.parentElement.classList.add("active");
}

function postAjax(value)
{

    let ajax_request = new XMLHttpRequest()

    ajax_request.open('POST', url + interventionId, true);
    ajax_request.setRequestHeader('Content-Type', 'application/json');
    let data = {
        way_steps: value
    }
    ajax_request.onreadystatechange = function ()
    {
        if(ajax_request.readyState === 4 && ajax_request.status === 200)
        {
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
    ajax_request.send(JSON.stringify(data));
}
