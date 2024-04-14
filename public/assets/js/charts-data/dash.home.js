function dashHomeStatIntervention() {
    let xmlhttp = new XMLHttpRequest();
    let url = "/api/intervention/status";
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            data = JSON.parse(this.response);
            // Value for span HTML
            let countNotStart = document.getElementById('count_not_start');
            countNotStart.innerHTML = data[0]['count'];
            let countWork = document.getElementById('count_work');
            countWork.innerHTML = data[1]['count'];
            let countFinal = document.getElementById('count_final');
            countFinal.innerHTML = data[2]['count'];
            let countExit = document.getElementById('count_exit');
            countExit.innerHTML = data[3]['count'];
            let countTotal = document.getElementById('count_total');
            countTotal.innerHTML =
                data[0]['count'] +
                data[1]['count'] +
                data[2]['count'] +
                data[3]['count']
            ;
            //
            const ctx = document.getElementById('myChart').getContext('2d');
            let darker = document.documentElement.getAttribute('data-layout-mode');
            if(darker === 'default'){
                Chart.defaults.color = "#ffffff";
            }
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Qty',
                        data: [data[0]['count'], data[1]['count'], data[2]['count'], data[3]['count']],
                        backgroundColor: [
                            '#f73164',
                            '#48A3D7',
                            '#c95e9e',
                            '#fa896b'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                        }
                    }
                }
            });
        }
    }
}