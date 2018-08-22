var respostas = {};
var porcentagem = 0;

$(document).ready(function() {
	$.each(perguntas, function(i, value) {
		respostas[value.id_pergunta] = 0;
	});
});

$("input").change(function() {
	// $(this).attr('name')
	// $(this).val()
	pergunta = $(this).attr('name').replace('pergunta', '');
	respostas[pergunta] = Number($(this).val());

	porcentagem = 0;
	$.each(respostas, function(i, value) {
		if (value!=0) porcentagem++;
	});

	porcentagem = porcentagem / perguntas.length * 100;
	$('#progresso .barra').css('width', porcentagem+'%').find('span').text(Math.floor(porcentagem)+'%');
	// $('#progresso .barra span').text(Math.floor(porcentagem)+'%');
	if (porcentagem>=100) $('#gerar').attr('disabled', false)
});

function gerarRelatorio() {
	if (porcentagem<100) {
		chamarPopupInfo('Responda todas as perguntas!');
		return;
	}

	data = {};
	data.funcao = 'gerar';
	data.respostas = respostas;
	data.id_questionario = questionario.id_questionario;
	data.id_empresa = empresa.id_empresa;

	$('#centro').find("button").attr("disabled", true);
	$.ajax({
		type: "post",
		url: "php/handler/pesquisaHandler.php",
		data: data,
		success: function(result) {
			console.log(result);
			result = JSON.parse(result);

			if (result.estado == 1) {
				chamarPopupConf(result.mensagem);

				location.href = 'relatorio?id='+result.id;
			} else {
				chamarPopupInfo(result.mensagem);
			}

			$('#centro').find("button").attr("disabled", false);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			chamarPopupErro("Desculpe, houve um erro, por favor atualize a página ou nos contate.");
			console.log(XMLHttpRequest);
			console.log(textStatus);
			console.log(errorThrown);
		},
		beforeSend: function() {
			chamarPopupLoading("Aguarde...");
		},
		complete: function() {
			removerLoading();
		}
	});
}