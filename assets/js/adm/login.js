$("#login").submit(function(e) {
	e.preventDefault();

	data = formToArray($(this).serializeArray());
	data.funcao = 'login';

	// console.log(data); return;
	$("#login button").attr("disabled", true);
	$.ajax({
        type: "post",
        url: "php/handler/admHandler.php",
        data: data,
        success: function(result) {
            result = JSON.parse(result);
            console.log(result);
             
            if (result.estado==1) {
                chamarPopupConf(result.mensagem);
 
                setTimeout(function() {
                    location.href = "adm/dashboard";
                }, 3000)
 
                $("#login button").attr("disabled", false);
            } else {
                chamarPopupInfo(result.mensagem);
                $("#login button").attr("disabled", false);
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