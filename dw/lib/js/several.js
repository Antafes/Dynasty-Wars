i01 = new Image();
i01.src = "pictures/map/arrow_down_h.png";
i02 = new Image();
i02.src = "pictures/map/arrow_down.png";
i03 = new Image();
i03.src = "pictures/map/arrow_down_c.png";
i04 = new Image();
i04.src = "pictures/map/arrow_leftdown_h.png";
i05 = new Image();
i05.src = "pictures/map/arrow_leftdown.png";
i06 = new Image();
i06.src = "pictures/map/arrow_leftdown_c.png";
i07 = new Image();
i07.src = "pictures/map/arrow_left_h.png";
i08 = new Image();
i08.src = "pictures/map/arrow_left.png";
i09 = new Image();
i09.src = "pictures/map/arrow_left_c.png";
i10 = new Image();
i10.src = "pictures/map/arrow_leftup_h.png";
i11 = new Image();
i11.src = "pictures/map/arrow_leftup.png";
i12 = new Image();
i12.src = "pictures/map/arrow_leftup_c.png";
i13 = new Image();
i13.src = "pictures/map/arrow_up_h.png";
i14 = new Image();
i14.src = "pictures/map/arrow_up.png";
i15 = new Image();
i15.src = "pictures/map/arrow_up_c.png";
i16 = new Image();
i16.src = "pictures/map/arrow_rightup_h.png";
i17 = new Image();
i17.src = "pictures/map/arrow_rightup.png";
i18 = new Image();
i18.src = "pictures/map/arrow_rightup_c.png";
i19 = new Image();
i19.src = "pictures/map/arrow_right_h.png";
i20 = new Image();
i20.src = "pictures/map/arrow_right.png";
i21 = new Image();
i21.src = "pictures/map/arrow_right_c.png";
i22 = new Image();
i22.src = "pictures/map/arrow_rightdown_h.png";
i23 = new Image();
i23.src = "pictures/map/arrow_rightdown.png";
i24 = new Image();
i24.src = "pictures/map/arrow_rightdown_c.png";
i25 = new Image();
i25.src = "pictures/send_mo.png";
i26 = new Image();
i26.src = "pictures/send.png";
i27 = new Image();
i27.src = "pictures/send_c.png";
i28 = new Image();
i28.src = "pictures/edit_mo.png";
i29 = new Image();
i29.src = "pictures/edit.png";
i30 = new Image();
i30.src = "pictures/edit_c.png";
i31 = new Image();
i31.src = "pictures/goback_mo.png";
i32 = new Image();
i32.src = "pictures/goback.png";
i33 = new Image();
i33.src = "pictures/goback_c.png";
i34 = new Image();
i34.src = "pictures/delete_mo.png";
i35 = new Image();
i35.src = "pictures/delete.png";
i36 = new Image();
i36.src = "pictures/delete_c.png";
i37 = new Image();
i37.src = "pictures/unload_mo.png";
i38 = new Image();
i38.src = "pictures/unload.png";
i39 = new Image();
i39.src = "pictures/unload_c.png";

function changePic(ImageName, ImageObjektName) {
	document.images[ImageName].src = eval(ImageObjektName + ".src")
}

function slideToggleView(element)
{
	$('#' + element).slideToggle('slow');
}

function cloneClause(element, text)
{
	var clone = $(element).prev().clone(true).insertBefore(element);
	var id = $(clone).attr('id');
	id = id.split('_');
	var div_id, clause;
	if (id[0] != 'clause')
	{
		var last_id = $(clone).children('textarea').attr('name').split('[');
		id[1] = parseInt(last_id[1].substr(0, last_id[1].length - 1));
	}
	var text_clause;
	if (id[0] == 'clause')
	{
		text_clause = $(clone).children('span').text();
		text_clause = text_clause.split(' ');
	}
	else
		text_clause = new Array(text);
	div_id = parseInt(id[1]) + 1;
	clause = parseInt(id[1]) + 2;
	$(clone).attr('id', 'clause_' + div_id);
	$(clone).children('span').text(text_clause[0] + ' ' + clause + ':');
	$(clone).children('textarea').text('');
	$(clone).children('textarea').attr('name', 'description[' + div_id + '][text]');
}

function cloneSubClause(element, text)
{
	var clone = $(element).prev().prev().clone(true).insertBefore($(element).prev());
	var id = $(clone).attr('id');
	id = id.split('_');
	var parent, subclause, div_id;
	if (id[0] != 'subclause')
	{
		parent = id[1];
		id[1] = 0;
		div_id = parseInt(id[1]);
		subclause = parseInt(id[1]) + 1;
	}
	else
	{
		var last_parent = $(clone).children('textarea').attr('name').split('[');
		parent = parseInt(last_parent[1].substr(0, last_parent[1].length - 1));
		div_id = parseInt(id[1]) + 1;
		subclause = parseInt(id[1]) + 2;
	}
	var text_subclause
	if (id[1] != 0)
	{
		text_subclause = $(clone).children('span').text();
		text_subclause = text_subclause.split(' ');
	}
	else
		text_subclause = new Array(text)
	$(clone).attr('id', 'subclause_' + div_id);
	$(clone).children('span').text(text_subclause[0] + ' ' + subclause + ':');
	$(clone).children('textarea').text('');
	$(clone).children('textarea').attr('name', 'description[' + parent +'][subclauses][][text]');
}

$(function()
{
	var height = $('.background').height();
	$('.left_border').height(height);
	$('.right_border').height(height);
});

function showDialog(element, width)
{
	if (!width)
		width = 300;

	$('#' + element).dialog({
		bgiframe: true,
		modal: true,
		autoOpen: false,
		width: width
	});
	$('#' + element).dialog('open');
}

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