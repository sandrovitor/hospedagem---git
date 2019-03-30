// PEH por cidade
var ctx1 = $('#canvas1');
var chart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: labelsCtx1,
        datasets: [{
            label: 'PEH (Pedidos de Hospedagem)',
            data: dataCtx1,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});


// FAC por cidade
var ctx2 = $('#canvas2');
var chart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: labelsCtx2,
        datasets: [{
            label: 'FAC (Formulários de Acomodação)',
            data: dataCtx2,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});


// IDADE dos visitantes
var ctx3 = $('#canvas3');
var chart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: labelsCtx3,
        datasets: [{
            label: 'Idade dos Visitante',
            data: dataCtx3,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(0, 102, 0, 1)'
            ],
            
            borderWidth: 1
        }]
    },
    options: ''
});