$(function() {
	$('#movekind_select').change(function() {
		if ($(this).val() == 2)
			$('#transport_res').show();
		else
			$('#transport_res').hide();
	});
	$('#movekind_select').change();

	$('form[name=tsend]').submit(function(e) {
		var form_fields = $(e['current_target']).find('input[name=tx], input[name=ty]');
		var errors = new Object();
		var tx = ty = 0;

		for (var i = 0; i < form_fields.length; i++)
		{
			if ($(form_fields[i]).attr['name'] == 'tx' && $(form_fields[i]).val() == '')
				errors['pos'] = true;
			else if ($(form_fields[i]).attr['name'] == 'tx' && parseInt($(form_fields[i]).val()) > 0)
				tx = parseInt($(form_fields[i]).val());

			if ($(form_fields[i]).attr['name'] == 'ty' && $(form_fields[i]).val() == '')
				errors['pos'] = true;
			else if ($(form_fields[i]).attr['name'] == 'ty' && parseInt($(form_fields[i]).val()) > 0)
				ty = parseInt($(form_fields[i]).val());
		}

		if ($('#movekind_select').val() > 0)
		{
			if ($('#movekind_select').val() == 2)
				if ($('#ressource_select').val() == '' && parseInt($('#ressource_amount').val()) === 0)
					errors['ressource'] = true;
			else
				errors['movekind'] = true;
		}

		var error_text = '';
		var error_type = 0;
		var items = new Object();

		items['errors'] = errors;
		items['target'] = new Object();
		items['target']['tx'] = tx;
		items['target']['ty'] = ty;

		var t = $.getJSON('lib/ajax/check_user.php', {items: JSON.stringify(items)}, function(data) {
			if (data['ok'])
			{
				error_text = data['text'];
				error_type = data['type'];

				if (error_type == 1)
				{
					alert(error_text);
					return false;
				}
				else if (error_type == 2)
					return confirm(error_text);
			}
		});
		debugger;
	})
});