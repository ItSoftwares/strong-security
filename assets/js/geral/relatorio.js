var opcoesGraficoRadar = {
	type: 'radar',
	data: {
		labels: [],
		datasets: [{
			label: 'Minha pontuação',
			backgroundColor: 'transparent',
			borderColor: '#2196F3',
			data: [],
			lineTension: 0.5,
			borderWidth: 2
		}, {
			label: 'Média por Segmento',
			backgroundColor: 'transparent',
			borderColor: '#9068c7',
			data: [],
			lineTension: 0.5,
			borderWidth: 2
		}
		]
	},
	options: {
		title: {
			display: true,
			text: 'Meus Resutlados x Mesmo Segmento',
			// fontFamily: 'encode sans',
			fontSize: 20
		},
		legend: {
			labels: {
				boxWidth: 12
				// fontColor: 'rgba(0,0,0,.9)',
				// fontFamily: 'encode sans'
			},
			position: 'left'
		},
		// maintainAspectRatio: false,
		responsive: true,
		animation: false
	}
};
var opcoesGraficoLinha = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: 'Minha pontuação',
			backgroundColor: 'transparent',
			borderColor: '#2196F3',
			data: [],
			lineTension: 0.5,
			borderWidth: 3
		}, {
			label: 'Média por Segmento',
			backgroundColor: 'transparent',
			borderColor: '#9068c7',
			data: [],
			lineTension: 0.5,
			borderWidth: 2
		}, {
			label: 'Média Geral',
			backgroundColor: 'transparent',
			borderColor: '#FF5722',
			data: [],
			lineTension: 0.5,
			borderWidth: 2
		}
		]
	},
	options: {
		title: {
			display: true,
			text: 'Média de Pontos',
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
		// maintainAspectRatio: false,
		responsive: true,
		animation: false
	}
};
var ctx;
var graficoRadar, graficoLinha;
var cores = ['#FF5722', '#00BCD4', '#8BC34A', '#FFC107', '#3f74b5'];
var CANVAS;
var doc;

$(document).ready(function() {
	Chart.defaults.global.defaultFontFamily = 'quicksand';
	Chart.plugins.register({
		beforeDraw: function(chartInstance) {
			var ctx = chartInstance.chart.ctx;
			ctx.fillStyle = "white";
			ctx.fillRect(0, 0, chartInstance.chart.width, chartInstance.chart.height);
		}
	});
	// GRAFICO RADAR
	for (var i = 1; i <= questoes; i++) {
		opcoesGraficoRadar.data.labels.push(i);
	}

	//organizar respostas
	temp = {};
	$.each(respostasRelAnteriores, function(i, value) {
		// console.log(!(value.id_relatorio) in temp);
		if (!(value.id_relatorio in temp)) temp[value.id_relatorio] = [];

		temp[value.id_relatorio].push(value.valor);
	});
	respostasRelAnteriores = temp;

	$.each(respostas, function(i, value) {
		opcoesGraficoRadar.data.datasets[0].data.push(value.valor);
	});
	opcoesGraficoRadar.data.datasets[0].borderWidth = 3;

	$.each(mesmoSegmento, function(i, value) {
		opcoesGraficoRadar.data.datasets[1].data.push(value.pontos);
	});

	cor = 0;
	$.each(respostasRelAnteriores, function(i, value) {
		opcoesGraficoRadar.data.datasets.push({
			label: 'Relatório #'+i,
			backgroundColor: 'transparent',
			borderColor: cores[cor],
			data: value,
			// lineTension: 0.5,
			borderWidth: 2
		});
		cor++;
	});

	var ctx = $('#grafico-radar')[0];
	ctx.height = 300;
	graficoRadar = new Chart(ctx, opcoesGraficoRadar);

	// GRAFICO DE LINHA
	for (var i = 1; i <= questoes; i++) {
		opcoesGraficoLinha.data.labels.push(i);
	}

	$.each(respostas, function(i, value) {
		opcoesGraficoLinha.data.datasets[0].data.push(value.valor);
	});

	$.each(mesmoSegmento, function(i, value) {
		opcoesGraficoLinha.data.datasets[1].data.push(value.pontos);
	});

	$.each(mediaGeral, function(i, value) {
		opcoesGraficoLinha.data.datasets[2].data.push(value.pontos);
	});

	var ctx2 = $('#grafico-linha-media-respostas')[0];
	ctx2.height = 50;
	graficoLinha = new Chart(ctx2, opcoesGraficoLinha);

	doc = new jsPDF('p', 'mm', [210, 297]);
	qtd = $('canvas').length;

	// return;

	$('canvas').each(function(i, elem) {
		if (canvasToImage(i, elem, qtd)) {
			gerarPaginas();
			atualizarMensagem('Gerando Páginas...');
		}
	});

	// gerarPaginas();
});

function canvasToImage(index, elem, qtd) {
	imgData = elem.toDataURL('image/jpeg', 1.0);
	img = $('<img />', {src: imgData, id: $(elem).attr('id')}).height($(elem).height());
	$(elem).replaceWith(img);

	return qtd-1==index?true:false;
}

function gerarPdf(index) {
	index = index || 1;
	console.log(index);

	qtd = $('.pagina').length;

	// $('.pagina').each(function(i, elem) {
		este = $(".pagina[data-index="+index+"]")[0];
		// este = elem;
		html2canvas(este, {
			useCORS: true,
			allowTaint: true,
			letterRendering: true,
			logging: false,
		    // scale: 2,
			dpi: 300
		}).then(canvas => {
			theCanvas = canvas;
			// theCanvas.setAttribute("data-teste", index);
			var ctx = theCanvas.getContext('2d');
			ctx.webkitImageSmoothingEnabled = false;
			ctx.mozImageSmoothingEnabled = false;
			ctx.imageSmoothingEnabled = false;
			$('body').append(theCanvas);
			$(este).hide();
			if (index>1) doc.addPage();
			imgData = theCanvas.toDataURL('image/jpeg', 1.0);
			doc.addImage(imgData, 'JPEG', 0, 0);
			if (index==qtd) {
				// window.open(doc.output('datauristring'));
				// doc.save('teste.pdf'); return;
				// string = doc.output('datauristring');
				salvarRelatorio(btoa(doc.output()));
				atualizarMensagem('Enviando por email...');
			} else if (index<=index) {
				gerarPdf(index+1);
			}
		});
	// });
}

function teste1() {
	var doc = new jsPDF('p');
	var specialElementHandlers = {
		'#editor': function (element, renderer) {
			return true;
		}
	};
	margins = {
		top: 80,
		bottom: 60,
		left: 40,
		width: 522
	};

	doc.fromHTML($('#container').html(), margins.left, margins.top, {
		'width': margins.width,
		'elementHandlers': specialElementHandlers
	},
	function (dispose) {
	   // dispose: object with X, Y of the last line add to the PDF
	   // this allow the insertion of new lines after html
		// doc.save('Mypdf.pdf');
	}, margins);
	
	window.open((doc.output('datauristring')));
	
	// doc.save('sample-file.pdf');
}

function gerarPaginas() {
	index = 1;
	padrao = $('<div class="pagina"/>').attr('data-index', index);
	ultima = padrao.clone();
	$('body').append(ultima);
	qtd = $('#container').children().length;
	$('#container').children().each(function(i, elem) {
		// console.log(i);
		if (ultima.outerHeight()+$(elem).outerHeight()>=1150) {
			index++;
			ultima = padrao.clone();
			ultima.attr('data-index', index);
			$('body').append(ultima);
			ultima.append(elem);
		} else ultima.append(elem);

		if (i+1==qtd) {
			$('.pagina').css({height: '297mm'});
			$('#container').hide();
			gerarPdf();
			atualizarMensagem('Gerando PDF...');
		}
	});
}

function salvarRelatorio(pdf) {
	data = {};
	data.pdf = pdf;
	data.nome = 'relatorio_'+relatorio.id_relatorio;
	data.id_empresa = relatorio.id_empresa;
	data.funcao = 'gerarPdf';

	$.ajax({
		type: 'post',
		url: 'php/handler/pesquisaHandler.php',
		data: data,
		success: function(result) {
			console.log(result);
			result = JSON.parse(result);

			if (result.estado == 1) {
				atualizarMensagem('Relatório enviado para o e-mail!', 'ok');
				// temp = '';
				// temp += '<object data="data:application/pdf;base64,'+escape(result.link)+'" type="application/pdf" width=750 height=600>';
				// temp += 'alt : <a href="data:application/pdf;base64,'+escape(result.link)+'">your.pdf</a>';
				// temp += '</object>';

				// $('#container').children().remove();
				// $('#container').append(temp);
				// window.open('data:application/pdf;base64,' + escape(result.link)); 
			} else {
				chamarPopupInfo(result.mensagem);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			chamarPopupErro('Desculpe, houve um erro, por favor atualize a página ou nos contate.');
			console.log(XMLHttpRequest);
			console.log(textStatus);
			console.log(errorThrown);
		},
		complete: function() {
			removerLoading();
		}
	});
}

function atualizarMensagem(msg, terminou) {
	$('#loading .mensagem').text(msg);
	terminou = terminou || false;

	if (terminou=='ok') $('#loading img').attr('src', 'assets/img/ok.png')
}