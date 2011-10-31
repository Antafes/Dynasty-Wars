function showUserInfo(element, target, date, position, point, clanname){	
	$('#' + target).dialog({
		modal: true,
		autoOpen: false,
		open: function()
		{					
			$('#register').text(date);
			$('#pos').text(position);
			$('#points').text(point);
			$('#clan').text(clanname);
		}
	
		
	});
	$('#' + target).dialog('open');	
	
}