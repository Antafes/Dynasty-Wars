function showEditingDialog(element, target, path, saveButton, cancelButton, width)
{
	var submitButton;
	
	if (!width)
		width = 300;

	$('#' + target).dialog({
		bgiframe: true,
		modal: true,
		autoOpen: false,
		width: width,
		buttons: {
			'save': function()
			{
				var itemList = $('#' + target).children('form').serializeArray();
				$.get(path, {items: JSON.stringify(itemList)}, function(data) 
				{
					data = JSON.parse(data);
					if (data['status'] == 'ok')
					{
						$(element).parent().before(data['html']);
						if (data['remove'].length > 0)
						{
							for (var i = 0; i < data['remove'].length; i++)
								$(data['remove'][i]).remove();
						}
						$('#' + target).dialog('close');
					}
				});
			},
			'cancel': function()
			{
				$(this).dialog('close');
			}
		},
		close: function()
		{
			$(this).dialog('destroy');
		},
		open: function()
		{
			var buttons = $('.ui-dialog-buttonpane').children();
			for (var i = 0; i < buttons.length; i++)
			{
				if ($(buttons[i]).text() == 'save')
				{
					$(buttons[i]).text(saveButton);
					submitButton = $(buttons[i]);
				}
				else if ($(buttons[i]).text() == 'cancel')
					$(buttons[i]).text(cancelButton);
			}
		}
	});
	$('#' + target).children('form').submit(function() {
		submitButton.click();
		return false;
	})
	$('#' + target).dialog('open');
}

function argumentApproval(path, state, aid)
{
	var itemList = new Array();
	itemList[0] = new Object();
	itemList[0]['name'] = 'state';
	itemList[0]['value'] = state;
	itemList[1] = new Object();
	itemList[1]['name'] = 'aid';
	itemList[1]['value'] = aid;
	$.get(path, {items: JSON.stringify(itemList)}, function(data) 
	{
		data = JSON.parse(data);
		if (data['status'] == 'ok')
		{
			$('#argument_approved' + aid).text(data['approvalState']);
			$('#approval_links' + aid).text('');
			$('#approval_links' + aid).append(data['approvalLinks']);
		}
	});
}

function showDecisionDialog(element, target, path, saveButton, cancelButton, width)
{
	var submitButton;
	
	if (!width)
		width = 300;

	$('#' + target).dialog({
		bgiframe: true,
		modal: true,
		autoOpen: false,
		width: width,
		buttons: {
			'save': function() 
			{
				var itemList = $('#' + target).children('form').serializeArray();
				var err = false;
				for (var i = 0; i < itemList.length; i++)
				{
					if (itemList[i]['name'] == 'reason')
					{
						if (itemList[i]['value'] == '')
						{
							$('textarea[name="' + itemList[i]['name'] + '"]').attr('class', 'ui-state-error');
							err = true;
						}
					}
				}
				if (err == false)
				{
					$.get(path, {items: JSON.stringify(itemList)}, function(data) 
					{
						data = JSON.parse(data);
						if (data['status'] == 'ok')
						{
							$(element).parent().parent().prev().children('.right').children().remove();
							$(element).remove();
							$('#new_argument').parent().parent().prev().before(data['html']);
							if (data['remove'].length > 0)
							{
								for (var i = 0; i < data['remove'].length; i++)
									$(data['remove'][i]).remove();
							}
							$('#' + target).dialog('close');
						}
					});
				}
			},
			'cancel': function()
			{
				$(this).dialog('close');
			}
		},
		close: function()
		{
			$(this).dialog('destroy');
		},
		open: function()
		{
			var buttons = $('.ui-dialog-buttonpane').children();
			for (var i = 0; i < buttons.length; i++)
			{
				if ($(buttons[i]).text() == 'save')
				{
					$(buttons[i]).text(saveButton);
					submitButton = $(buttons[i]);
				}
				else if ($(buttons[i]).text() == 'cancel')
					$(buttons[i]).text(cancelButton);
			}
		}
	});
	$('#' + target).children('form').submit(function() {
		submitButton.click();
		return false;
	})
	$('#' + target).dialog('open');
}

function blockComments(element, path, state, tid)
{
	var itemList = new Array();
	itemList[0] = new Object();
	itemList[0]['name'] = 'block';
	itemList[0]['value'] = state;
	itemList[1] = new Object();
	itemList[1]['name'] = 'tid';
	itemList[1]['value'] = tid;
	$.get(path, {items: JSON.stringify(itemList)}, function(data) 
	{
		data = JSON.parse(data);
		if (data['status'] == 'ok')
		{
			$(element).parent().append(data['html']);
			$(element).remove();
		}
	});
}

function showCommentList(target, tid)
{
	var itemList = new Array();
	itemList[0] = new Object();
	itemList[0]['name'] = 'tid';
	itemList[0]['value'] = tid;
	$.get('lib/ajax/comment_system.php', {items: JSON.stringify(itemList)}, function (data)
	{
		data = JSON.parse(data);
		if (data['status'] == 'ok')
		{
			$('#' + target).append(data['html']);
		}
	});
}

function deleteComment(element, tcoid)
{
	var itemList = new Array();
	itemList[0] = new Object();
	itemList[0]['name'] = 'tcoid';
	itemList[0]['value'] = tcoid;
	$.get('lib/ajax/delete_comment.php', {items: JSON.stringify(itemList)}, function (data)
	{
		data = JSON.parse(data);
		if (data['status'] == 'ok')
		{
			$(element).parent().parent().remove();
		}
	});
}

function editComment(element, target, tcoid, path, saveButton, cancelButton, width)
{
var submitButton;
	
	if (!width)
		width = 300;

	$('#' + target).dialog({
		bgiframe: true,
		modal: true,
		autoOpen: false,
		width: width,
		buttons: {
			'save': function()
			{
				var itemList = $('#' + target).children('form').serializeArray();
				$.get(path, {items: JSON.stringify(itemList)}, function(data) 
				{
					data = JSON.parse(data);
					if (data['status'] == 'ok')
					{
						$(element).parent().parent().find('.comment_content').remove();
						if ($(element).parent().parent().find('.comment_changed').length > 0)
							$(element).parent().parent().find('.comment_changed').remove();
						$(element).parent().before(data['html']);
						$('#' + target).dialog('close');
					}
				});
			},
			'cancel': function()
			{
				$(this).dialog('close');
			}
		},
		close: function()
		{
			$(this).dialog('destroy');
		},
		open: function()
		{
			var hidden_fields = $('#' + target).find('input[type=hidden]');
			for (var i = 0; i < hidden_fields.length; i++)
			{
				if ($(hidden_fields[i]).attr('name') == 'ajax_id')
				{
					$(hidden_fields[i]).val(tcoid);
				}
			}
			
			var array = new Array();
			array[0] = new Object();
			array[0]['name'] = 'tcoid';
			array[0]['value'] = tcoid;
			
			var form_elements = $('#' + target).find(':input[type!=hidden]');
			$.get('lib/ajax/get_comment.php', {items: JSON.stringify(array)}, function (data)
			{
				data = JSON.parse(data);
				if (data['status'] == 'ok')
				{
					for (var i = 0; i < form_elements.length; i++)
					{
						if ($(form_elements[i]).attr('name') == 'comment_text')
						{
							$(form_elements[i]).val(data['comment']);
						}
					}
				}
			});
			
			var buttons = $('.ui-dialog-buttonpane').children();
			for (var i = 0; i < buttons.length; i++)
			{
				if ($(buttons[i]).text() == 'save')
				{
					$(buttons[i]).text(saveButton);
					submitButton = $(buttons[i]);
				}
				else if ($(buttons[i]).text() == 'cancel')
					$(buttons[i]).text(cancelButton);
			}
		}
	});
	$('#' + target).children('form').submit(function() {
		submitButton.click();
		return false;
	})
	$('#' + target).dialog('open');
}