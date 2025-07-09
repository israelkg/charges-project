var ctx = document.getElementById('chargeMonth').getContext('2d');

const dataM =  {
    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
    datasets: [{
        label: 'Pagas',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: ['#00FF00'],
        borderColor: 'black',
        borderWidth: 1,
    },{
        label: 'Pendentes',
        data: [2, 10, 15, 10, 15, 10],
        backgroundColor: ['#FFFF00'],
        borderColor: 'black',
        borderWidth: 1,
    },{
        label: 'Vencidas',
        data: [10, 15, 10, 15, 10, 15],
        backgroundColor: ['#FF4500'],
        borderColor: 'black',
        borderWidth: 1,
    }]
}

const chargeMonth = new Chart(ctx, {
    type: 'bar',
    data: dataM,
    options: {
        resposive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Status de Cobranças Mensais',
                font: {
                    size: 30,
                    family: 'Arial',
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
                        size: 15,
                        family: 'Arial',
                    },
                    color: 'black',
                    padding: 10,
                    boxWidth: 10,
                    boxHeight: 10,
                    usePointStyle: true,
                }
            }
        },
        // se quiser bar normal, tire essa parte de scales
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
            }
        }
    }
})