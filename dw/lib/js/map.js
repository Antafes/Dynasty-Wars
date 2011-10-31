$(function() {
	$('#navi_top_left').click(function() {
		moveMap(-3, -3);
	});
	$('#navi_top').click(function() {
		moveMap(0, -3);
	});
	$('#navi_top_right').click(function() {
		moveMap(3, -3);
	});
	$('#navi_right').click(function() {
		moveMap(3, 0);
	});
	$('#navi_bottom_right').click(function() {
		moveMap(3, 3);
	});
	$('#navi_bottom').click(function() {
		moveMap(0, 3);
	});
	$('#navi_bottom_left').click(function() {
		moveMap(-3, 3);
	});
	$('#navi_left').click(function() {
		moveMap(-3, 0);
	});
	$('#position_change').submit(function(e) {
		e.preventDefault();
		var x = $(this).children('input[name=x]').val();
		var y = $(this).children('input[name=y]').val();
		moveMap(x - mapX, y - mapY);
//		$(this).children('input[name=x]').val(x);
//		$(this).children('input[name=y]').val(y);
	});
	$('#position_change').children('input[name=x]').val(mapX);
	$('#position_change').children('input[name=y]').val(mapY);
	for (var key in uidList)
	{
		var user = uidList[key];
		createMapToolTip($('.viewport').children('.y' + user.y).children('.x' + user.x).children().children(), user.uid);
	}
});

function moveMap(xAdd, yAdd)
{
	var currentY = 0;
	var absXAdd, absYAdd = 0;
	var currentRow, currentColumn;
	$.getJSON('lib/ajax/get_map_data.php', {'new_x': mapX + xAdd, 'new_y': mapY + yAdd, 'x': mapX, 'y': mapY}, function(response) {
		absXAdd = xAdd;
		if (absXAdd < 0)
			absXAdd *= -1;
		if (absXAdd > 17)
			absXAdd = 17;
		absYAdd = yAdd;
		if (absYAdd < 0)
			absYAdd *= -1;
		if (absYAdd > 17)
			absYAdd = 17;

		if (yAdd > 0)
		{
			for (var i = 0; i < absYAdd; i++)
				$('.viewport').children('.row:first').remove();
		}

		for (var key in response)
		{
			var row = response[key];
			delete currentRow, currentColumn;

			for (var rowKey in row)
			{
				var column = row[rowKey];

				if (!currentRow || currentY != column.map_y)
				{
					if (typeof currentRow != 'undefined')
					{
						currentRow.children('.clear').remove();
						currentRow.append('<div class="clear"></div>');
					}

					if (yAdd < 0)
					{
						if (!$('.y' + column.map_y).length)
						{
							if (!currentRow)
								$('.viewport').prepend('<div class="row y' + column.map_y + '"></div>');
							else
								currentRow.after('<div class="row y' + column.map_y + '"></div>');
						}
					}
					else
					{
						if (!$('.y' + column.map_y).length)
						{
							if (!currentRow)
								$('.viewport').append('<div class="row y' + column.map_y + '"></div>');
							else
								currentRow.after('<div class="row y' + column.map_y + '"></div>');
						}
					}

					currentRow = $('.y' + column.map_y);
					currentColumn = undefined;

					if (currentRow.children('.position').length >= 17 && xAdd < 0)
					{
						for (var i = 0; i < absXAdd; i++)
							currentRow.children('.position:last').remove();
					}
					else if (currentRow.children('.position').length >= 17 && xAdd >= 0)
					{
						for (var i = 0; i < absXAdd; i++)
							currentRow.children('.position:first').remove();
					}

					currentY = column.map_y;
				}

				if (!currentRow.children('.x' + column.map_x).length)
				{
					var currentPosition = '<div class="position x' + column.map_x + '" style="background-image: url(\'' + backgroundPath + column.image + '\');"></div>';
					if (currentRow.children('.position').length > 0)
					{
						if (typeof currentColumn == 'undefined')
						{
							if (xAdd < 0)
								currentRow.prepend(currentPosition);
							else
								currentRow.append(currentPosition);
						}
						else
							currentColumn.after(currentPosition);
					}
					else
						currentRow.append(currentPosition);

					currentColumn = currentRow.children('.x' + column.map_x);

					if (column.uid && !column.deactivated)
					{
						currentColumn.html('<a href="index.php?chose=usermap&amp;reguid=' + column.uid + '&amp;fromc=map"></a>');
						currentColumn.children().html('<img class="city i1" src="' + backgroundPath +  (column.uid == -1 ? 'harbour' : 'city' + (column.terrain == 4 ? '_mountain' : '')) + '.gif" />');
						createMapToolTip(currentColumn.children().children(), column.uid);
//						currentColumn.children().children().mouseover(function() {
//							ToolTip(column.map_x + ':' + column.map_y, column.city, column.nick, column.clanname, column.clantag);
//						});
//						currentColumn.children().children().mouseout(function() {
//							start('Details');
//						});
					}
				}
			}
		}

		if (yAdd < 0)
		{
			for (var i = 0; i < absYAdd; i++)
				$('.viewport').children('.row:last').remove();
		}

		mapX += xAdd;
		mapY += yAdd;

		$('#position_change').children('input[name=x]').val(mapX);
		$('#position_change').children('input[name=y]').val(mapY);
	});
}

function createMapToolTip(target, uid)
{
	target.qtip({
		content: {
			url: 'lib/ajax/get_user_data.php',
			data: {'uid': uid},
			method: 'get'
		},
		position: {
			corner: {
				target: 'topRight',
				tooltip: 'bottomLeft'
			},
			adjust: {
				screen: true
			}
		},
		style: {
			tip: {
				corner: 'bottomLeft',
				size: {
					x: 8,
					y: 8
				}
			},
			border: {
				width: 1,
				color: '#990000'
			},
			backgroundColor: '#FCDF7E',
			color: '#980E23'
		},
		show: 'mouseover',
		hide: 'mouseout'
	});
}