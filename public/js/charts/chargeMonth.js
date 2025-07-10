var ctx = document.getElementById('chargeMonth').getContext('2d');

// Estrutura inicial do gráfico com dados vazios
const dataM = {
    labels: [],
    datasets: [
        {
            label: 'Pagas',
            data: [],
            backgroundColor: ['#228B22'],
            borderColor: 'black',
            borderWidth: 1,
        },
        {
            label: 'Pendentes',
            data: [],
            backgroundColor: ['#FFD700'],
            borderColor: 'black',
            borderWidth: 1,
        },
        {
            label: 'Vencidas',
            data: [],
            backgroundColor: ['#B22222'],
            borderColor: 'black',
            borderWidth: 1,
        }
    ]
};

const chargeMonth = new Chart(ctx, {
    type: 'bar',
    data: dataM,
    options: {
        responsive: true,
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
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
            }
        }
    }
});

// Busca os dados do backend e atualiza o gráfico
fetch('api/dashboard/dados-cobrancas')
    .then(response => response.json())
    .then(dados => {
        console.log(dados); // debug
        dataM.labels = dados.labels;
        dataM.datasets[0].data = dados.pagas;
        dataM.datasets[1].data = dados.pendentes;
        dataM.datasets[2].data = dados.vencidas;
        chargeMonth.update();
    })
    .catch(error => console.error('Erro ao buscar dados do gráfico:', error));
