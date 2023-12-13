function dashHomeStatYear(yearDate, pathUrl) {
    let xmlhttp = new XMLHttpRequest();
    let url = "/api/i/c/current-year";
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    xmlhttp.onreadystatechange = function(){
        if(this.readyState === 4 && this.status === 200){
            data = JSON.parse(this.response);
            console.log(data)
            month_name = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre']

            num_count = data.interventions.map(function(elem){
                return elem.i_count;
            })
            num_customer = data.customers.map(function(elem){
                return elem.c_count;
            })
            const ctx = document.getElementById('canvas').getContext('2d');
            let darker = document.documentElement.getAttribute('data-layout-mode');
            if(darker === 'default'){
                Chart.defaults.color = "#ffffff";
            }
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: month_name,
                    datasets: [{
                        label: 'Interventions',
                        data: num_count,
                        borderColor: '#f73164',
                        backgroundColor: "#f73164"

                    },
                        {
                            label: 'Clients',
                            data: num_customer,
                            borderColor: '#31f0f7',
                            backgroundColor: "#31f0f7"

                        }
                    ]
                },
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: 'Statistique des interventions et clients en ' + yearDate
                        }
                    },
                    scales: {
                        y: {
                            stacked: false
                        }
                    }
                }
            });
        }

    }
}