var ctx = document.getElementById('recipeAcumulate').getContext('2d');

const dataR =  {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
    datasets: [{
        label: 'Receita acumulada (R$)',
        data: [500, 1300, 2000, 2900, 3700, 4500, 4900], // valores simulados
        backgroundColor: 'rgba(75, 192, 192, 0.2)', // preenchimento (área)
        borderColor: 'rgba(75, 192, 192, 1)',       // linha
        borderWidth: 2,
        fill: true,
        tension: 0.3,
        pointRadius: 5,
        pointHoverRadius: 7,
    }]
}

const recipeAcumulate = new Chart(ctx, {
    type: 'line', //testar radar
    data: dataR,
    options: {
        resposive: true,
        maintainAspectRatio: false,
        plugins: {
            filler: {
                propagate: true
            },
            title: {
                display: true,
                text: 'Receita Anual Acumulada',
                font: {
                    size: 22,
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
                        size: 14,
                        family: 'Arial',
                    },
                    color: 'black',
                    padding: 10,
                    usePointStyle: true,
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Valor (R$)',
                    font: {
                        size: 14
                    }
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Mês',
                    font: {
                        size: 14
                    }
                }
            }
        }
    }
})