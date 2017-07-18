function loadingLine() {
	var i = 0;
	var interval = setInterval(function(){
		i = ++i % 4;
		$("#go").html("Loading"+Array(i+1).join("."));
	}, 500);
	return interval;
}

function getStatistic() {
	$('#go').attr("disabled", true);
	var interval = loadingLine();
	var query = $('#main').serialize();
	$.getJSON(document.URL + amp + query, function(response) {
		clearInterval(interval);
		$('#go').attr("disabled", false);
		$('#go').html("Submit");
		if(typeof(response.error) != "undefined") {
			$(".error").show().html(response.error);
			return false;
		}
		refreshCaptcha();
		$(".error").html('').hide();
		$('#ajax_response').html(response.html).show();
		return false;
	});
}

function refreshCaptcha() {
	$('#captcha_img').attr("src", base_url + 'captcha/captcha.php?' + Math.random());
	$("#captcha").val('');
}

$(document).ready(function(){
	var domain = $("#domain");
	domain.attr("autocomplete", "off");

	$('#captcha_img, .captcha-refresh').click(function(){
		refreshCaptcha();
		return false;
	});
	
	$('#go').click(function(){
		getStatistic();
	  return false;
	});
	
	$('#main input').keydown(function(e) {
		if (e.keyCode == 13) {
			getStatistic();
	  	return false;
		}
	});
});

