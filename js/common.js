function progressBar() {
	$(".progressBar").each(function() {
		$(this).progressbar({
			value: parseInt($(this).attr("perc"))
		});
	});
}

function smoothToTop() {
	$("html, body").animate({scrollTop: "0px"}, 500);
}

$(document).ready(function() {
	/*$(window).scroll(function () {
		if ($(this).scrollTop() <= 170) {
			var offset = "170px";
		} else {
			var offset = ($(this).scrollTop() + 10)+"px";
		}
	    $("#menuRight").animate({top:offset},{duration:500,queue:false});
	});*/

	$.ajaxSetup({
		beforeSend:function() {
			$('#reportContainer').show().html('<div class="center ajaxLoading"></div>');
		}
	});

	$(".buttonSet").buttonset();
});