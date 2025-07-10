var ctx = document.getElementById('valueRange').getContext('2d');

const dataV =  {
    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
    datasets: [{
        label: 'R$0-100',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: '#228B22',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$101-300',
        data: [2, 10, 15, 10, 15, 10],
        backgroundColor: '#FFFF00',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$301-500',
        data: [10, 15, 10, 15, 10, 15],
        backgroundColor: '#FFA500',
        borderColor: 'black',
        borderWidth: 1,
      },
      {
        label: 'R$501-1000',
        data: [5, 10, 7, 9, 8, 6],
        backgroundColor: '#B22222',
        borderColor: 'black',
        borderWidth: 1,
      },]
}

const valueRange = new Chart(ctx, {
    type: 'bar',
    data: dataV,
    options: {
        resposive: true,
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
    }
})