var ctx = document.getElementById('valueRange').getContext('2d');

const dataV =  {
    labels: [],
    datasets: [
      {
        label: 'R$0-100',
        data: [],
        backgroundColor: '#228B22',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$101-300',
        data: [],
        backgroundColor: '#FFFF00',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$301-500',
        data: [],
        backgroundColor: '#FFA500',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$501-1000',
        data: [],
        backgroundColor: '#D2691E',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$1001-2000',
        data: [],
        backgroundColor: '#EE82EE',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$2001-5000',
        data: [],
        backgroundColor: '#B22222',
        borderColor: 'black',
        borderWidth: 1,
      }
    ]
}

const valueRange = new Chart(ctx, {
    type: 'bar',
    data: dataV,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Distribuição de cobranças por faixa de valor',
                font: {
                    size: 22,
                    family: 'Arial',
                },
                color: 'black',
                padding: {
                    top: 10,
                    bottom: 20,
                },
            },
            legend: {
                labels: {
                    font: {
                        size: 14,
                        family: 'Arial',
                    },
                    color: 'black',
                    padding: 10,
                    boxWidth: 10,
                    usePointStyle: true,
                },
            },
            tooltip: {
                mode: 'index',
                intersect: false,
            },
        },
        scales: {
          x: {
            stacked: true
          },
          y: {
            stacked: true,
            beginAtZero: true
          }
        }
    }
})

fetch('api/dashboard/dados-cobrancas')
  .then(response => response.json())
  .then(dados => {
    console.log('Dados faixa valor:', dados);

    dataV.labels = dados.faixas.labels;
    dataV.datasets[0].data = dados.faixas["0-100"];
    dataV.datasets[1].data = dados.faixas["101-300"];
    dataV.datasets[2].data = dados.faixas["301-500"];
    dataV.datasets[3].data = dados.faixas["501-1000"];
    dataV.datasets[4].data = dados.faixas["1001-2000"];
    dataV.datasets[5].data = dados.faixas["2001-5000"];

    valueRange.update();
  })
  .catch(error => console.error('Erro ao buscar dados faixa valor:', error));
