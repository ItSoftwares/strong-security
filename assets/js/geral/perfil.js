var original;
var imagem = {w:0, h:0};

$(document).ready(function() {
	$.each(usuario, function(index, value) {
		if (index=='senha' || index=="pin") return true;
		$("form [name="+index+"]").val(value).change().trigger('input');
	});
});

$("[name=e_noivo]").change(function() {
	if ($(this).val()==1) $("[name=data-casamento]").attr('required', true);
	else $("[name=data-casamento]").attr('required', false);
});

$("#foto input[type=file]").change(function() {
    input = this;
    
    if (input.files && input.files[0]) {
        if (input.files[0].size>2*1024*1024) {
            chamarPopupInfo("A imagem deve ter até 2Mb");
            limparImagemPerfil();
            return;
        }
        
        var reader = new FileReader();
        var img = new Image();
        
        img.onload = function() {
            if (img.width<200 || img.height<200) {
                chamarPopupInfo("A imagem deve ter pelo menos 200 Pixels");
                limparImagemPerfil();
                return;
            }
            
            proporcaoHeight = 200*img.height/img.width;
            
            if (proporcaoHeight<200) {
                chamarPopupInfo("Proporções inválidas. Escolha uma imagem com um altura válida!");
                limparImagemPerfil();
                return;
            }
            
            
            $("#foto img").attr("src", img.src);
            
            imagem.w = img.width;
            imagem.h = img.height;
        }

        reader.onload = function (e) {
            img.src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}); 

$("#form-perfil form").submit(function(e) {
    e.preventDefault();

    // data = formToArray($(this).serializeArray());
    data = {};
    temp = new FormData(this);

    teste = verificarCampos();

    if (teste !== false) {
        teste.focus();
        return;
    }

    if ($("[name=data_casamento]").length>0) data.data_casamento = $("[name=data_casamento]").cleanVal();
    if ($("[name=telefone]").length>0) data.telefone = $("[name=telefone]").cleanVal();
    if ($("[name=cnpj]").length>0) data.cnpj = $("[name=cnpj]").cleanVal();
    if ($("[name=cep]").length>0) data.cep = $("[name=cep]").cleanVal();
    data.id = usuario.id;
    data.tipoUsuario = tipoUsuario;
    data.eu = true;
    data.funcao = "atualizar";

    if (imagem.w>0) {
    	data.alturaImagem = imagem.h;
    	data.larguraImagem = imagem.w;
    }

    // temp = new FormData();
    $.each(data, function(index, val) {
    	if (index=="pin" && val.length==0) return true;
    	temp.append(index, val);
    });

    // console.log(data); return;

    $(this).find("button").attr("disabled", true);
    chamarPopupLoading("Aguarde...");
    $.ajax({
        type: "post",
        url: "../php/handler/usuarioHandler.php",
        data: temp,
        success: function(result) {
            console.log(result);
            result = JSON.parse(result);

            if (result.estado == 1) {
            	chamarPopupConf(result.mensagem);
            } else {
                chamarPopupInfo(result.mensagem);
            }

            $("#form-perfil form").find("button").attr("disabled", false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            chamarPopupErro("Desculpe, houve um erro, por favor atualize a página ou nos contate.");
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function() {
            removerLoading();
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

function verificarCampos() {
    teste = false;
    $("#form-perfil input:not([type=file])").each(function(i, elem) {
        if ($(elem).val() == "" && $(elem).attr('name')!='senha' && $(elem).attr('id')!="repetir_senha" && $(elem).attr('name')!="pin") {
            teste = $(elem);
            // console.log(elem);
            return false;
        }

        if ($(elem).attr('name') == 'telefone' && $(elem).val().length < 14) {
            chamarPopupInfo("Preencha o telefone corretamente!");
            teste = $(elem);
            return false;
        } else if ($(elem).attr('name') == 'senha' && $(elem).val().length > 0) {
            if ($(elem).val().length < 8) {
                chamarPopupInfo("A senha deve ter no mínimo 8 dígitos!");
                teste = $(elem);
                return false;
            } else if ($(elem).val() != $("#repetir-senha").val()) {
                chamarPopupInfo("Repita a senha corretamente!");
                teste = $(elem);
                return false;
            }
        } else if ($(elem).attr('name') == 'data_casamento' && $(elem).val().length < 10) {
            chamarPopupInfo("Preencha a data do casamento corretamente!");
            teste = $(elem);
            return false;
        } else if ($(elem).attr('name') == 'pin' && $(elem).val().length >0) {
        	if ($(elem).val().length < 4) {
        		chamarPopupInfo("O PIN deve ter 4 números!");
	            teste = $(elem);
	            return false;
        	} else if (Number($(elem).val())>9999) {
        		chamarPopupInfo("O PIN deve ter 4 NÚMEROS DE 0000 à 9999!");
	            teste = $(elem);
	            return false;
        	}
        }
    });

    if (teste === false) {
        $("#form-perfil select").each(function(i, elem) {
            if ($(elem).val() == 0) {
                chamarPopupInfo("Esolha uma opção!");
                teste = $(elem);
                return false;
            }
        });
    }

    return teste;
}

function limparImagemPerfil() {
    $("#foto img").attr("src", original);
    $("#foto input[type=file]").val("");
    
    imagem.w = 0;
    imagem.h = 0;
}