function randomize(from, to)
{
	from = typeof(from) != 'undefined' ? from : 0;
	to = typeof(to) != 'undefined' ? to : from + 1;
	return Math.round(from + Math.random()*(to - from));
}

function regenerate()
{
	var chars='', password='',
	password_length = Number($('#password_length').val()),
	hyphen_length = Number($('#hyphen_length').val())+1;

	if ($('#use_pronounceable').attr('checked'))
	{
		var consonants = passopts.consonants,
		vowels = passopts.volves;

		for (i = 1; i <= password_length; i++)
			if($('#use_hyphens').attr('checked') && i%hyphen_length==0)
				password += passopts.hyphen;
			else
				password += i%2==0?consonants.charAt(randomize(0,consonants.length-1)):vowels.charAt(randomize(0,vowels.length-1));
	}
	else if ($('#use_specified').attr('checked'))
	{
		var chars = $('#specified_chars').val();

		for (i=1; i<=password_length; i++)
		{
			if($('#use_hyphens').attr('checked') && i%hyphen_length==0)
				password += '-';
			else
				password += chars.charAt(randomize(0,chars.length-1));
		}
	}
	else
	{
		if($('#use_letters').attr('checked'))
			chars += $('#use_letters').val();
		if($('#use_numbers').attr('checked'))
			chars += $('#use_numbers').val();
		if($('#use_specs').attr('checked'))
			chars += $('#use_specs').val();

		for (i=1; i<=password_length; i++)
		{
			if($('#use_hyphens').attr('checked') && i%hyphen_length==0)
				password += passopts.hyphen;
			else
				password += chars.charAt(randomize(0,chars.length-1));
		}
	}
	$("input[id='password']").val(password);

}

$(document).ready(function() {
		$("input[id='regen']").click(function(){regenerate()});
		$("input[id='password_length']").change(function(){regenerate()});
		$("input[id='password_length']").keyup(function(){regenerate()});
		$("input[id='specified_chars']").keyup(function(){regenerate()});
		$("input[type='checkbox']").click(function(){regenerate()});

		$("input[id='use_pronounceable']").click(function(){

				if ($(this).attr('checked'))
				{
					$('#use_letters').attr('disabled', 'disabled');
					$('#use_numbers').attr('disabled', 'disabled');
					$('#use_specs').attr('disabled', 'disabled');
					$("input[id='use_specified']").attr('disabled', 'disabled');
					$("input[id='specified_chars']").attr('disabled', 'disabled');
				}
				else
				{
					$('#use_letters').removeAttr('disabled');
					$('#use_numbers').removeAttr('disabled');
					$('#use_specs').removeAttr('disabled');
					$("input[id='use_specified']").removeAttr('disabled');
				}

				regenerate();
		});

		$("input[id='use_specified']").click(function(){

				if ($(this).attr('checked'))
				{
					$('#use_letters').attr('disabled', 'disabled');
					$('#use_numbers').attr('disabled', 'disabled');
					$('#use_specs').attr('disabled', 'disabled');
					$("input[id='use_pronounceable']").attr('disabled', 'disabled');
					$("input[id='specified_chars']").removeAttr('disabled');
				}
				else
				{
					$('#use_letters').removeAttr('disabled');
					$('#use_numbers').removeAttr('disabled');
					$('#use_specs').removeAttr('disabled');
					$("input[id='use_pronounceable']").removeAttr('disabled');
					$("input[id='specified_chars']").attr('disabled', 'disabled');
				}

				regenerate();
		});

		$("input[id='use_hyphens']").click(function(){
				if ($(this).attr('checked'))
				{
					$('#hyphen_length').removeAttr('disabled');
				}
				else
				{
					$('#hyphen_length').attr('disabled', 'disabled');
				}
				regenerate();
		});
});