function dashUserStats(customerStats) {
    const ctx = document.getElementById('myChart').getContext('2d');
    let darker = document.documentElement.getAttribute('data-layout-mode');
    if(darker === 'default'){
        Chart.defaults.color = "#ffffff";
    }
    const myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: ['Interventions'],
            datasets: [{
                label: 'Qty',
                data: customerStats,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}