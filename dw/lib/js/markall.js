$(function() {
	$('#markall').click(function() {
		var table = $('.messageList').children('form').children('table');

		if ($(this).attr('checked'))
			table.children('tbody').children('tr').children('td.delete').children('input').attr('checked', true);
		else
			table.children('tbody').children('tr').children('td.delete').children('input').removeAttr('checked');
	});
});