function dashEquipmentStats(equipmentStats) {
    const ctx = document.getElementById('myChart').getContext('2d');
    let darker = document.documentElement.getAttribute('data-layout-mode');
    if(darker === 'default'){
        Chart.defaults.color = "#ffffff";
    }
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Interventions', 'Débours'],
            datasets: [{
                label: 'Qty',
                data: equipmentStats,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
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