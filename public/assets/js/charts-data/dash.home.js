function dashHomeStatIntervention() {
    let xmlhttp = new XMLHttpRequest();
    let url = "/api/intervention/status";
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            data = JSON.parse(this.response);
            step00 = data[0]['count']
            step01 = data[1]['count']
            step02 = data[2]['count']
            step03 = data[3]['count']
            // Value for span HTML
            let countNotStart = document.getElementById('count_not_start');
            countNotStart.innerHTML = step00;
            let countWork = document.getElementById('count_work');
            countWork.innerHTML = step01;
            let countFinal = document.getElementById('count_final');
            countFinal.innerHTML = step02;
            let countExit = document.getElementById('count_exit');
            countExit.innerHTML = step03;
            let countTotal = document.getElementById('count_total');
            countTotal.innerHTML = step00 + step01 + step02 + step03;
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
                        data: [step00, step01, step02, step03],
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