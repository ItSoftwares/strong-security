var idPopup = 1;
var popups = {};

function chamarPopupInfo(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-info'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-info').length);
        organizarPopups();
    });
    
    idPopup++;
}

function chamarPopupErro(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-erro'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-erro').length);
        organizarPopups();
    });
    
    idPopup++;
}

function chamarPopupConf(mensagem, tempo) {
    tempo = tempo || 10000;
    popupInfo = $("<div class='popup popup-conf'>").appendTo("body");
    popupInfo.html(mensagem).attr("data-id", idPopup);
    organizarPopups();
    
    popupInfo.delay(tempo).fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-conf').length);
        organizarPopups();
    });
    
    idPopup++;
}

function chamarPopupLoading(mensagem) {
    popupInfo = $("<div class='popup popup-loading'>").appendTo("body");
    popupInfo.html("<div class='img-loading'></div>"+mensagem).attr("data-id", idPopup);
    organizarPopups();
    idPopup++;
}

function removerLoading() {
    $(".popup-loading").fadeOut(function() {
        $(this).remove();
        // console.log($('.popup-loading').length);
        organizarPopups();
    });
}

function organizarPopups() {
    qtdPopups = $(".popup").length;
   // console.log(qtdPopups);
    altura = 10;
    
    $(".popup").each(function(i, elem) {
        $(elem).css({bottom: altura});
        altura+= $(elem).outerHeight()+10;
    });
}

function colocarZero(n) {
    if (n<10) return "0"+n;
    
    return n;
}

function getData(time) {
    time = new Date(time*1000);
    return colocarZero(time.getDate())+"/"+colocarZero(time.getMonth()+1)+"/"+time.getFullYear();
}

function getHora(time) {
    time = new Date(time*1000);
    return colocarZero(time.getHours())+":"+colocarZero(time.getMinutes());
}

function formToArray(serialized) {
    temp = {};
    
    $.each(serialized, function(i, value) {
        temp[value.name] = value.value;
    });
    
    return temp;
}

function timeToTimestamp(data) {
    if (typeof data=="number") return data;
    var t = data.split(/[- :]/);

    return new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])).getTime()/1000;
}

function arrayToObject(a, indice) {
	temp = {};

	$.each(a, function(i, value) {
		temp[value[indice]] = value;
	});

	return temp;
}

function colocarZero(n) {
	if (n<10) return "0"+n;

	return n;
}