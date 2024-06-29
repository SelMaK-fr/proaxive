function dashHomeStatIntervention() {
    let xmlhttp = new XMLHttpRequest();
    let url = "/api/v1/interventions/stats";
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            data = JSON.parse(this.response);
            console.log(data);
            // Value for span HTML
            let countNotStart = document.getElementById('count_not_start');
            countNotStart.innerHTML = data['not_started'];
            let countWork = document.getElementById('count_work');
            countWork.innerHTML = data['in_workshop'];
            let countFinal = document.getElementById('count_final');
            countFinal.innerHTML = data['final_test'];
            let countExit = document.getElementById('count_exit');
            countExit.innerHTML = data['exit_waiting'];
            let countTotal = document.getElementById('count_total');
            let calc = parseInt(data['not_started']) + parseInt(data['in_workshop']) + parseInt(data['final_test']) + parseInt(data['exit_waiting']);
            countTotal.innerHTML = calc;
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
                        data: [data['not_started'], data['in_workshop'], data['final_test'], data['exit_waiting']],
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