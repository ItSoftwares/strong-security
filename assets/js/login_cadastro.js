$(document).ready(function() {
	if ($('#cadastro [name=nome]').val().length>0) {
		$('#ir_cadastro').click();
		$('#cadastro [name=nome]').focus().select();
	}
});

$('#ir_cadastro').click(function() {
	$('#login').fadeOut(function() {
		$('#cadastro').fadeIn().css({display: 'flex'});
		$('#cadastro [name=email_login]').focus().select();
	});
});

$('#ir_login').click(function() {
	$('#cadastro').fadeOut(function() {
		$('#login').fadeIn().css({display: 'flex'});
		$('#login [name=email_login]').focus().select();
	});
});

$('#login').submit(function(e) {
	e.preventDefault();
	// console.log(data); return;

	data = formToArray($(this).serializeArray());
	data.funcao = 'login';

	$(this).find('button').attr('disabled', true);
	chamarPopupLoading('Aguarde enquanto criamos sua conta!');
	$.ajax({
		type: 'post',
		url: 'php/handler/empresaHandler.php',
		data: data,
		success: function(result) {
			console.log(result);
			result = JSON.parse(result);

			if (result.estado == 1) {
				chamarPopupConf(result.mensagem);
				setTimeout(function() {
					location.href = 'pesquisas';
				}, 5000);

				$('#login')[0].reset();
			} else {
				chamarPopupInfo(result.mensagem);
				$('#login').find('button').attr('disabled', false);
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
});

$('#cadastro').submit(function(e) {
	e.preventDefault();

	data = formToArray($(this).serializeArray());
	data.funcao = 'cadastro';

	teste = verificarCampos();

	if (teste !== false) {
		teste.focus();
		return;
	}

	data.telefone_1 = $('[name=telefone_1]').cleanVal();
	data.telefone_2 = $('[name=telefone_2]').cleanVal();

	// console.log(data); return;

	$(this).find('button').attr('disabled', true);
	chamarPopupLoading('Aguarde enquanto criamos sua conta!');
	$.ajax({
		type: 'post',
		url: 'php/handler/empresaHandler.php',
		data: data,
		success: function(result) {
			console.log(result);
			result = JSON.parse(result);

			if (result.estado == 1) {
				chamarPopupConf('Cadastro realizado com sucesso, faça LOGIN para continuar!');
				$('#ir_login').click();
				// setTimeout(function() {
				// 	location.href = 'pesquisas';
				// }, 5000);

				$('#cadastro')[0].reset();
			} else {
				chamarPopupInfo(result.mensagem);
				$('#cadastro').find('button').attr('disabled', false);
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
});

function verificarCampos() {
	teste = false;
	$('#cadastro input').each(function(i, elem) {
		if ($(elem).val() == '') {
			teste = $(elem);
			return false;
		}

		repetirSenha = $('#cadastro #repetir_senha').val();

		if ($(elem).attr('name') == 'telefone_1' && $(elem).val().length < 14) {
			chamarPopupInfo('Preencha o telefone corretamente!');
			teste = $(elem);
			return false;
		} else if ($(elem).attr('name') == 'telefone_2' && $(elem).val().length < 15) {
			chamarPopupInfo('Preencha o celular corretamente!');
			teste = $(elem);
			return false;
		} else if ($(elem).attr('name') == 'senha') {
			if ($(elem).val().length < 8) {
				chamarPopupInfo('A senha deve ter no mínimo 8 dígitos!');
				teste = $(elem);
				return false;
			} else if ($(elem).val() != repetirSenha) {
				chamarPopupInfo('Repita a senha corretamente!');
				teste = $(elem);
				return false;
			}

		}
	});

	if (teste === false) {
		$('#cadastro select').each(function(i, elem) {
			if ($(elem).val() == 0) {
				chamarPopupInfo('Esolha uma opção!');
				teste = $(elem);
				return false;
			}
		});
	}

	return teste;
}