var grupo_padrao = null;
var pergunta_padrao = null;
var numero_pergunta = 0;
var numero_grupo = 0;
var novo_questionario = {
		titulo: '',
		descricao: ''
	};
var novos_grupos = {};
var novas_perguntas = {};
var funcao_pesquisa = 'cadastrar';

$(document).ready(function() {
	questionarios = arrayToObject(questionarios, 'id_questionario');
	grupo_perguntas = arrayToObject(grupo_perguntas, 'id_grupo_perguntas');
	perguntas = agruparPerguntas(perguntas);

	grupo_padrao = $('.grupo').clone();
	pergunta_padrao = $('.pergunta').clone();

	$('.grupo, .pergunta').remove();
});

$(document).on('click', '#nova button', function() {
	$('#pesquisa').fadeIn().css({display: 'flex'});
	funcao_pesquisa = 'cadastrar';
});

$('#pesquisa .fechar').click(function() {
	$('#pesquisa').fadeOut(function() {
		$('.grupo .apagar-grupo').click();

		novo_questionario = {
			titulo: '',
			descricao: ''
		};
		novos_grupos = {};
		novas_perguntas = {};
		numero_pergunta = 0;
		numero_grupo = 0;
	});
});

$(document).on('click', '.nova button', function() {
	novo = grupo_padrao.clone();

	numero_grupo++;
	novo.attr('data-numero', numero_grupo);

	novos_grupos[numero_grupo] = {titulo: ''};

	$(this).closest('.nova').before(novo);
	novo.find('input').focus();

	return novo;
});

$(document).on('keyup', '.grupo input', function() {
	n = $(this).closest('.grupo').data('numero');
	novos_grupos[n].titulo = $(this).val();
});

$(document).on('click', '.grupo .nova-pergunta', function() {
	nova = pergunta_padrao.clone();

	numero_pergunta++;
	nova.attr('data-numero', numero_pergunta);
	// nova.find('.numero').text('#'+numero_pergunta);

	n = $(this).closest('.grupo').data('numero');

	novas_perguntas[numero_pergunta] = {
		enunciado: '',
		descricao: '',
		id_grupo: n
	}

	if ($(this).closest('.grupo').nextAll('.grupo').length>0) {	
		$($(this).closest('.grupo').nextAll('.grupo')[0]).before(nova);
	} else {
		$('.nova').before(nova);
	}

	nova.find('input').focus();
});

$(document).on('click', '.grupo .apagar-grupo', function() {
	n = $(this).closest('.grupo').data('numero');
	// console.log(n);
	//apagar perguntas
	$.each(novas_perguntas, function(i, value) {
		if (value.id_grupo == n) {
			$('.pergunta[data-numero='+i+']').remove();
			delete novas_perguntas[i];
		}
	});

	//apagar grupo
	delete novos_grupos[n];
	$('.grupo[data-numero='+n+']').remove();
});

$(document).on('keyup', '.pergunta input', function() {
	n = $(this).closest('.pergunta').data('numero');
	novas_perguntas[n].enunciado = $(this).val();
});

$(document).on('keyup', '.pergunta textarea', function() {
	n = $(this).closest('.pergunta').data('numero');
	novas_perguntas[n].descricao = $(this).val();
});

$(document).on('click', '.pergunta .apagar-pergunta', function() {
	n = $(this).closest('.pergunta').data('numero');
	// console.log(n);
	//apagar pergunta
	$('.pergunta[data-numero='+n+']').remove();
	delete novas_perguntas[n];
});

$(document).on('click', '.editar', function() {
	funcao_pesquisa = 'atualizar';

	questionario = questionarios[$(this).closest('.card').data('id')];

	$('#questionario-form [name=titulo]').val(questionario.titulo);
	$('#questionario-form [name=descricao]').val(questionario.descricao);
	$('#questionario-form [name=conclusao_1]').val(questionario.conclusao_1);
	$('#questionario-form [name=conclusao_2]').val(questionario.conclusao_2);
	$('#questionario-form [name=conclusao_3]').val(questionario.conclusao_3);

	$.each(grupo_perguntas, function(i, value) {
		if (value.id_questionario == questionario.id) {
			$('.nova button').click();
		}
	});
});

$(document).on('click', '.excluir', function() {
	data = {};
	data.funcao = 'excluir';
	data.id_questionario = $(this).closest('.card').data('id');
	
	if (questionarios[data.id_questionario].respondido>0) {
		chamarPopupInfo('Esse questionario ja foi respondido mais de uma vez, então não pode ser excluido!');
		return
	}

	$("#centro button").attr("disabled", true);
	chamarPopupLoading('Aguarde...');
	$.ajax({
		type: "post",
		url: "php/handler/pesquisaHandler.php",
		data: data,
		success: function(result) {
			result = JSON.parse(result);
			console.log(result);

			delete questionarios[data.id_questionario];

			atualizarQuestionarios();
			 
			if (result.estado==1) {
				chamarPopupConf(result.mensagem);
 
				$("#centro button").attr("disabled", false);
			} else {
				chamarPopupInfo(result.mensagem);
				$("#centro button").attr("disabled", false);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			chamarPopupErro("Desculpe, houve um erro, por favor atualize a página ou nos contate.");
			console.log(XMLHttpRequest);
			console.log(textStatus);
			console.log(errorThrown);
		},
		complete: function() {
			removerLoading();
		}
	});
});

function agruparPerguntas(perguntas) {
	temp = {};

	$.each(perguntas, function(index, value) {
		if (!(value.id_grupo in temp)) temp[value.id_grupo] = [];
		temp[value.id_grupo].push(value);
	});

	return temp;
}

function verificar() {
	teste = false;
	// verificar GRUPOS
	$('.grupo input').each(function(i, elem) {
		if ($(this).val().length==0) {
			teste = $(this);
			return false;
		}
		// n = $(this).data('numero');
		// novos_grupos[n] = $(this).val();
	});

	// verificar PERGUNTAS
	$('.pergunta input').each(function(i, elem) {
		if ($(this).val().length==0) {
			teste = $(this);
			return false;
		}
		// n = $(this).data('numero');
		// novas_perguntas[n].enunciado = $(this).find('input').val();
		// novas_perguntas[n].descricao = $(this).find('textarea').val();
	});
	$('.pergunta textarea').each(function(i, elem) {
		if ($(this).val().length==0) {
			teste = $(this);
			return false;
		}
	});

	return teste;
}

function salvarQuestionario() {
	if (Object.keys(novos_grupos).length==0) {
		chamarPopupInfo('Crie pelo menos um grupo de perguntas!');
		return;
	}

	if (Object.keys(novas_perguntas).length==0) {
		chamarPopupInfo('Crie pelo menos uma pergunta!');
		return;
	}

	grupos = [];

	$.each(novas_perguntas, function(i, value) {
		if (grupos.indexOf(value.id_grupo)==-1) grupos.push(value.id_grupo);
	});

	$.each(novos_grupos, function(i, value) {
		if (grupos.indexOf(Number(i))==-1) {
			chamarPopupInfo('Crie pelo menos uma pergunta neste grupo!');
			$('.grupo[data-numero='+i+'] input').focus().select();
			return false;
		}
	});

	novo_questionario = {
		titulo: $('#questionario-form [name=titulo]').val(),
		descricao: $('#questionario-form [name=descricao]').val(),
		conclusao_1: $('#questionario-form [name=conclusao_1]').val(),
		conclusao_2: $('#questionario-form [name=conclusao_2]').val(),
		conclusao_3: $('#questionario-form [name=conclusao_3]').val()
	}

	data = {
		funcao: funcao_pesquisa,
		questionario: novo_questionario,
		grupos: novos_grupos,
		perguntas: novas_perguntas
	};

	$("#pesquisa button").attr("disabled", true);
	chamarPopupLoading('Aguarde...');
	$.ajax({
		type: "post",
		url: "php/handler/pesquisaHandler.php",
		data: data,
		success: function(result) {
			result = JSON.parse(result);
			console.log(result);
			 
			if (result.estado==1) {
				chamarPopupConf(result.mensagem);

				novo = result.retorno.questionario;

				questionarios[novo.id_questionario] = novo;
 
				$('#pesquisa .fechar').click();

				atualizarQuestionarios();
 
				$("#pesquisa button").attr("disabled", false);
			} else {
				chamarPopupInfo(result.mensagem);
				$("#pesquisa button").attr("disabled", false);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			chamarPopupErro("Desculpe, houve um erro, por favor atualize a página ou nos contate.");
			console.log(XMLHttpRequest);
			console.log(textStatus);
			console.log(errorThrown);
		},
		complete: function() {
			removerLoading();
		}
	});
}

function atualizarQuestionarios() {
	$('#reload').load('adm/pesquisas #centro', questionarios, function() {
		$('#centro').find('.card').remove();
		$('#centro').append($(this).find('#centro').html());
		$('#reload').children().remove();
	});
}