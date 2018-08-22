$('#toggle-menu').click(function() {
	$('#menu-lateral').toggleClass('aberto');

	if ($('#menu-lateral').hasClass('aberto')) $('#toggle-menu i').removeClass('fa-bars').addClass('fa-times');
	else $('#toggle-menu i').addClass('fa-bars').removeClass('fa-times');
});

$('#menu-lateral ul li').hover(function() {
	if (!$('body').hasClass('fechado')) return;
	$(this).find('span').show().css('display', 'table');
}, function() {
	if (!$('body').hasClass('fechado')) return;
	$(this).find('span').hide();
});

$('#configuracoes a').click(function(e) {
	e.preventDefault();
	$('#perfil').fadeIn().css('display', 'flex');
});

$('.fundo#perfil .fechar').click(function() {
	$(this).closest('.fundo').fadeOut();
});

$('#solicitar-voucher a').click(function(e) {
	e.preventDefault();

	if ($(this).closest('li').data('id')==2) return;

	$.ajax({
        type: "post",
        url: "php/handler/empresaHandler.php",
        data: {id_empresa: empresa.id_empresa, funcao: 'solicitarVoucher'},
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);
             
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);

                $('#solicitar-voucher span').text('Voucher Pendente');
            } else {
                chamarPopupInfo(result.mensagem);
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