/** **/
let xmlhttp2 = new XMLHttpRequest();
let urlLinking = "/api/stats/customers/" + year;
xmlhttp2.open("GET",urlLinking,true);
xmlhttp2.send();
xmlhttp2.onreadystatechange = function() {
    if(this.readyState == 4 && this.status == 200) {
        dataCustomer = JSON.parse(this.response);
        month_name = dataCustomer.map(function(elem){
            return elem.month_name;
        })
        totalCustomer = dataCustomer.map(function(elem){
            return elem.quantity;
        })
        const ctr = document.getElementById('customers').getContext('2d');
        const myChart2 = new Chart(ctr, {
            type: 'line',
            data: {
                labels: month_name,
                datasets: [{
                    label: "Nombre de client par mois",
                    data: totalCustomer,
                    backgroundColor: "#453d9b"
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: "Statistique des clients en " + year
                    }
                }
            }
        });
    }
}
/****/
let xmlhttp = new XMLHttpRequest();
let url = "/api/stats/interventions/" + year;
xmlhttp.open("GET",url,true);
xmlhttp.send();
xmlhttp.onreadystatechange = function(){
    if(this.readyState == 4 && this.status == 200){
        dataZ = JSON.parse(this.response);
        month_name = dataZ.map(function(elem){
            return elem.month_name;
        })
        quantity = dataZ.map(function(elem){
            return elem.quantity;
        })
        console.log(dataZ)
        const ctx = document.getElementById('canvas').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: month_name,
                datasets: [
                    {
                        label: "Nombre d'intervention par mois",
                        data: quantity,
                        backgroundColor: "#453d9b"
                    }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: "Statistique des interventions en " + year
                    }
                },
                scales: {
                    y: {
                        stacked: true
                    }
                }
            }
        });
    }
}