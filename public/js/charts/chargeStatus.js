var ctx = document.getElementById('chargeStatus').getContext('2d');

const dataS =  {
    labels: ['Pagas', 'Pendentes', 'Vencidas'],
  datasets: [{
    data: [35, 20, 15], // somatório das cobranças de cada status
    backgroundColor: ['#00FF00', '#FFFF00', '#FF4500'],
    borderColor: 'black',
    borderWidth: 1
  }]
}

const chargeStatus = new Chart(ctx, {
    type: 'doughnut',
    data: dataS,
    options: {
        resposive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Distribuição de status das cobranças (Total Geral)',
                font: {
                    size: 22,
                    family: 'Arial'
                },
                color: 'black',
                padding: {
                    top: 10,
                    bottom: 20
                }
            },
            legend: {
                labels: {
                    font: {
                        size: 14,
                        family: 'Arial'
                    },
                    color: 'black',
                    padding: 10,
                    usePointStyle: true
                }
            }
        }
    }
})