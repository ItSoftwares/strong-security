$(document).ready(function() {
	empresas = arrayToObject(empresas, 'id_empresa');
	relatorios = arrayToObject(relatorios, 'id_relatorio');
});


$(document).on('click', '.ver', function() {
	id = $(this).closest('tr').data('id');

	$.each(empresas[id], function(i, value) {
		$('#detalhes [name='+i+']').val(value).trigger('input');
	});

	$('#detalhes tr:not(:first-child)').remove();
	$.each(relatorios, function(i, value) {
		if (value.id_empresa!=id) return true;
		
		temp = '';
		temp += '<tr data-id='+value.id_relatorio+'>';
		temp += '<td>'+value.titulo+'</td>';
		temp += '<td>'+getData(timeToTimestamp(value.data_criacao))+'</td>';
		temp += '<td>';
		temp += '<a class="botao icon reenviar-relatorio" href="relatorio?id='+value.id_relatorio+'" target="_BLANK"><i class="fa fa-envelope" title="Reenviar Relatório"></i></a>';
		temp += '</td>';
		temp += '</tr>';

		$('#detalhes table').append(temp);
	});

	$('#detalhes').fadeIn().css({display: 'flex'});
});

$('#detalhes .fechar').click(function() {
	$('#detalhes').fadeOut();
});

$('#pesquisar input').keyup(function(e) {
	code = e.keyCode;

	if (code==13) {
		texto = $(this).val();
		console.log(texto);
		if (texto!=nome) {
			pesquisar(texto, filtro, segmento, pagina);
		}
	}
});

$('#filtro').change(function() {
	valor = $(this).val();
	if (valor!=filtro) {
		pesquisar(nome, valor, segmento, pagina);
	}
});

$('#segmento').change(function() {
	valor = $(this).val();
	if (valor!=segmento) {
		console.log(valor);
		pesquisar(nome, filtro, valor, pagina);
	}
});

$('#paginas li').click(function() {
	valor = $(this).data('pagina');
	if (valor!=pagina) {
		pesquisar(nome, filtro, segmento, valor);
	}
});

function pesquisar(t, f, s, p) {
	t = t || '';// t = texto
	f = f || 0; // f = segmento
	s = s || 0; // s = segmento
	p = p || 1; // p = pagina

	link = 'views/adm/conteudo/tabela.php';
	extra = {};
	if (t!='') extra.nome = t;
	if (f!=0) extra.filtro = f;
	if (s!=0) extra.segmento = s;
	if (p!=1) extra.pagina = p;

	extra = objToUrl(extra);
	link += extra;
	$('#tabela').load(link, function() {
		// console.log($(this));

		// $('#tabela').children().remove();
		// $('#tabela').append($(this).children().html());
		// $('#reload').children().remove();
		
		console.log(extra);
		link = 'adm/empresas'+extra;
		window.history.pushState('', '', link);
	});
}

function objToUrl(obj) {
	temp = '';
	if (Object.keys(obj).length>0) {
		temp = '?';
		for (key in obj) {
			if (temp!='?') temp += '&';
			temp += key+'='+obj[key];
		}
	}
	return temp;
}

function atualizarVoucher(estado, id) {
	data = {funcao: 'atualizarVoucher', id_empresa: id, estado: estado};
	// console.log(data); return;

	$('#tabela button').attr('disabled', true);
	$.ajax({
        type: 'post',
        url: 'php/handler/admHandler.php',
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);
             
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);

                pesquisar(nome, filtro, segmento, pagina);
 
                $('#tabela button').attr('disabled', false);
            } else {
                chamarPopupInfo(result.mensagem);
                $('#tabela button').attr('disabled', false);
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