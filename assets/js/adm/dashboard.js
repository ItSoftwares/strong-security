var meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
var opcoesGrafico = {
	type: 'bar',
	data: {
		// labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        datasets: [{
            label: 'Empresas Cadastradas',
            backgroundColor: '#2196F3',
            // borderColor: '#2196F3',
            // data: [0, 10, 5, 2, 20, 30, 45, 25, 69, 12, 13, 74],
            // lineTension: 0.5,
            borderWidth: 1
        },
        {
            label: 'Relatórios Gerados',
            backgroundColor: '#9068c7',
            // borderColor: '#9068c7',
            // data: [10, 5, 2, 20, 0, 45, 25, 65, 30,  13, 74, 19],
            // lineTension: 0.5,
            borderWidth: 1
        },
        ]
	},
	options: {
		title: {
			display: true,
			text: 'Resumo úlitmo ano',
			// fontFamily: 'encode sans',
			fontSize: 20
		},
		legend: {
			labels: {
				boxWidth: 12
				// fontColor: 'rgba(0,0,0,.9)',
				// fontFamily: 'encode sans'
			}
		},
		maintainAspectRatio: false,
		responsive: true,
		scales: {
            xAxes: [{ 
            	gridLines: {
                    zeroLineColor: 'transparent'
                }
                // color: 'rgba(0,0,0,0)'
            }],
            yAxes: [{
            	gridLines: {
                    zeroLineColor: 'transparent',
                    // drawTicks: false,
                    // display: false
                }
                // color: 'rgba(0,0,0,0)'
            }],
        }
	}
};
var ctx;
var grafico;

$(document).ready(function() {
	// ORGANIZAR MESES CORRETAMENTE
	mesAtual = new Date().getMonth();
	anoAtual = new Date().getFullYear();
	mesesOrdenados = [];
	for (var i = 0; i < 13; i++) {
		if (mesAtual<0) {
			mesAtual = 11;
			anoAtual--;
		}
		mesesOrdenados.push(meses[mesAtual]+'/'+anoAtual);
		mesAtual--;
	}

	mesesOrdenados.reverse();

	tempEmpresas = [0 , 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	$.each(empresas, function(i, value) {
		mes = value.mes;
		ano = value.ano;

		index = mesesOrdenados.indexOf(meses[mes-1]+'/'+ano);
		if (index!=-1) tempEmpresas[index]++;
		else return false;
	});

	tempRelatorios = [0 , 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	$.each(relatorios, function(i, value) {
		mes = value.mes;
		ano = value.ano;

		index = mesesOrdenados.indexOf(meses[mes-1]+'/'+ano);
		if (index!=-1) tempRelatorios[index]++;
		else return false;
	});

	Chart.defaults.global.defaultFontFamily = 'quicksand';
	var ctx = $('#empresas canvas')[0];
	ctx.height = 500;
	opcoesGrafico.data.labels = mesesOrdenados;
	opcoesGrafico.data.datasets[0].data = tempEmpresas;
	opcoesGrafico.data.datasets[1].data = tempRelatorios;
	grafico = new Chart(ctx, opcoesGrafico);
});

$('#pesquisar-empresa input').keyup(function(e) {
	code = e.keyCode;

	if (code==13) {
		texto = $(this).val();

		if (texto.length>0) {
			location.href = 'adm/empresas?nome='+texto;
		}
	}
});