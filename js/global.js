addLoadEvent(prepareInternalnav);

function showSection(id) {
	var articles = document.getElementById("showHide");
	var sections = articles.getElementsByTagName("section");
	for (var i=0; i<sections.length; i++ ) {
		if (sections[i].getAttribute("id") != id) {
			sections[i].style.display = "none";
		}else{
			sections[i].style.display = "block";
		}
	}
}

function prepareInternalnav() {
	if (!document.getElementsByTagName) return false;
	if (!document.getElementById) return false;
	var articles = document.getElementById("showHide");
	if (!articles) return false;
	var navs = articles.getElementsByTagName("nav");
	if (navs.length == 0) return false;
	var nav = navs[0];
	var links = nav.getElementsByTagName("a");
	for (var i=0; i<links.length; i++ ) {
		var sectionId = links[i].getAttribute("href").split("#")[1];
		if (!document.getElementById(sectionId)) continue;
		document.getElementById(sectionId).style.display = "none";
		links[i].destination = sectionId;
		links[i].onclick = function() {
			showSection(this.destination);
			return false;
		}
	}
}

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
		} else {
		window.onload = function() {
		  oldonload();
		  func();
		}
	}
}

$(function() {
	if ( $("#aside-left") == null && $("#aside-right") == null || ("section[class|=col]") == null) return false; 
	var viewWidth = $( window ).width();
	if ( viewWidth >= 1024 ) {
		return;
	} else if ( viewWidth <= 767 ) {
		var sidebar1 = $("#aside-left").detach();
		var sidebar2 = $("#aside-right").detach();
		$("section[class|=col]").append(sidebar1).append(sidebar2);
		sidebar1 = null;
		sidebar2 = null;
	} else if ( 768 <= viewWidth <= 1023 ) {
		var sidebar1 = $("#aside-left").removeClass("col-1-4").addClass("col-1-3");
		var sidebar2 = $("#aside-right").removeClass("col-1-4").removeClass("reverse").detach();
		$(sidebar1).append(sidebar2);
		$("section[class|=col]").removeClass("col-1-2").addClass("col-2-3");
		sidebar1 = null;
		sidebar2 = null;
	}
});


$(function(){
	$('.main-menu').slicknav();
});