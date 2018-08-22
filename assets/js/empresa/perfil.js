$(document).ready(function() {
	$.each(empresa, function(index, value) {
		if (index=='senha') return true;
		$('[name='+index+']').val(value).change().trigger('input');
	});
});

$('#editar').click(function() {
	$(this).hide();
	$('#salvar, #cancelar').show();
	$('form#perfil input, form#perfil select').attr('disabled', false);
	$('[name=email_login]').focus().select();
});

$('#cancelar').click(function() {
	$.each(empresa, function(index, value) {
		if (index=='senha') return true;
		$('[name='+index+']').val(value).change().trigger('input');
	});

	$('#editar').show();
	$('#salvar, #cancelar').hide();
	$('form#perfil input, form#perfil select').attr('disabled', true);
});

$('form#perfil').submit(function(e) {
	e.preventDefault();

	data = formToArray($(this).serializeArray());

	teste = verificarCampos();
	if (teste !== false) {
		teste.focus();
		return;
	}

	data.telefone_1 = $("[name=telefone_1]").cleanVal();
	data.telefone_2 = $("[name=telefone_2]").cleanVal();

	data.id_empresa = empresa.id_empresa;
	data.funcao = "atualizar";

	// console.log(data); return;

	$(this).find("button").attr("disabled", true);
	$.ajax({
		type: "post",
		url: "php/handler/empresaHandler.php",
		data: temp,
		success: function(result) {
			console.log(result);
			result = JSON.parse(result);

			if (result.estado == 1) {
				chamarPopupConf(result.mensagem);
				empresa = result.atualizado;
			} else {
				chamarPopupInfo(result.mensagem);
			}

			$('form#perfil').find("button").attr("disabled", false);
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