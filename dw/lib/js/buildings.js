$(function() {
	$('.buildings').children('.buildplace').each(function(index, element) {
		$(element).children('a').click(function(e) {
			var link = $(this);
			var clone = $('#build_dialog').clone();
			clone.removeAttr('id');

			showAjaxLoading($('.buildings'));
			clone.dialog({
				autoOpen: false,
				appendTo: $('.buildings'),
				modal: true,
				draggable: false,
				resizable: false,
				width: 500,
				position: {
					of: $('.buildings'),
					within: $('.buildings')
				},
				create: function() {
					var dialog = $(this);
					var buildPlace = link.attr('id').substr(5);
					$('.ui-dialog-titlebar').remove();
					$.getJSON('lib/ajax/get_build_place_data.php', {city: $('#cityChange').val(), buildPlace: buildPlace}, function(response) {
						handleAjaxBuildingResponse(dialog, buildPlace, response);

						dialog.children('.close').children('a').click(function() {
							dialog.dialog('close');
						});

						hideAjaxLoading();
						dialog.dialog('open');
					});
				},
				close: function() {
					$(this).dialog('destroy');
				}
			});
		});
	});
});

function handleAjaxBuildingResponse(dialog, buildPlace, response)
{
	if (response.type === 'buildingsList')
	{
		if (response.data.length === 1)
		{
			fillBuildplace(dialog, buildPlace, response.data[0]);
			return;
		}

		var hiddenBuilding = $('<div class="hiddenBuilding" style="display: none;"></div>');
		hiddenBuilding.append(dialog.children().not('.close'));
		dialog.append(hiddenBuilding);
		dialog.prepend('<div class="buildingSelector"></div>');

		var buildingSelector = $('<select></select>');
		buildingSelector.append('<option selected="selected" disabled="disabled">-</option>');
		for (var i = 0; i < response.data.length; i++)
		{
			var option = $('<option></option>');
			option.val(response.data[i].kind);
			option.text(response.data[i].buildingName);
			option.data('buildingData', response.data[i]);
			buildingSelector.append(option);
		}

		buildingSelector.change(function() {
			fillBuildplace(dialog.children('.hiddenBuilding'), buildPlace, $(this).children(':selected').data('buildingData'));
			dialog.children('.hiddenBuilding').show();
		});

		dialog.children('.buildingSelector').text(response.newBuilding + ': ');
		dialog.children('.buildingSelector').append(buildingSelector);
	}
	else if (response.type === 'building')
		fillBuildplace(dialog, buildPlace, response.data);
}

function fillBuildplace(dialog, buildPlace, building)
{
	dialog.children('.heading').text(building.buildingName + (building.level ? ' ' + building.level : ''));
	dialog.children('.building_pic').html(building.buildingPicture);
	dialog.children('.description').text(building.buildingDescription);

	if (!building.notYetBuildable)
		dialog.children('.not_yet_buildable').hide();
	else
		dialog.children('.not_yet_buildable').show();

	var resources = dialog.children('form').children('.res.build').children('table').children('tbody');
	resources.children('tr:first').children('td.food').text(building.buildingResources.food);
	resources.children('tr:first').children('td.wood').text(building.buildingResources.wood);
	resources.children('tr:first').children('td.rock').text(building.buildingResources.rock);
	resources.children('tr:last').children('td.iron').text(building.buildingResources.iron);
	resources.children('tr:last').children('td.paper').text(building.buildingResources.paper);
	resources.children('tr:last').children('td.koku').text(building.buildingResources.koku);

	if (!building.canBuild || !building.freeBuildPosition)
		dialog.children('form').children('.res.build').children('.build_button').children('input').prop('disabled', true);
	else
		dialog.children('form').children('.res.build').children('.build_button').children('input').prop('disabled', false);

	dialog.children('form').children('.res.build').children('.build_time').text(building.buildTime);

	dialog.children('form').submit(function(e) {
		e.preventDefault();
	});

	var ajaxData = {
		type: 'build',
		buildPlace: buildPlace,
		city: $('#cityChange').val(),
		kind: building.kind
	};
	dialog.children('form').children('.res.build').children('.build_button').children('input').unbind('click');
	dialog.children('form').children('.res.build').children('.build_button').children('input').click(function() {
		showAjaxLoading(dialog);
		$.post('lib/ajax/build.php', ajaxData, function(response) {
			if (!response.error.length)
			{
				addBuildListItem(response.timer);
				$('.build_list').show();
				timer(response.timer.endTime, response.timer.now, 'b' + response.timer.bid);

				$('.build_dialog').not('#build_dialog').dialog('close');
			}

			hideAjaxLoading();
		});
	});

	if (!building.hasUpgrades)
		dialog.children('form').children('.res.upgrade').hide();
	else
	{
		var resources = dialog.children('form').children('.res.upgrade').children('table').children('tbody');
		resources.children('tr:first').children('td.food').text(building.buildingResources.food);
		resources.children('tr:first').children('td.wood').text(building.buildingResources.wood);
		resources.children('tr:first').children('td.rock').text(building.buildingResources.rock);
		resources.children('tr:last').children('td.iron').text(building.buildingResources.iron);
		resources.children('tr:last').children('td.paper').text(building.buildingResources.paper);
		resources.children('tr:last').children('td.koku').text(building.buildingResources.koku);


		if (!building.canUpgrade || !building.freeBuildPosition)
			dialog.children('form').children('.res.upgrade').children('.build_button').children('input').prop('disabled', true);
		else
			dialog.children('form').children('.res.upgrade').children('.build_button').children('input').prop('disabled', false);

		dialog.children('form').children('.res.upgrade').children('.build_time').text(building.upgradeTime);

		var ajaxData = {
			type: 'upgrade',
			buildPlace: buildPlace,
			city: $('#cityChange').val()
		};
		dialog.children('form').children('.res.upgrade').children('.build_button').children('input').unbind('click');
		dialog.children('form').children('.res.upgrade').children('.build_button').children('input').click(function() {
			showAjaxLoading(dialog);
			$.post('lib/ajax/build.php', ajaxData, function(response) {
				if (!response.error.length)
				{
					addBuildListItem(response.timer);
					$('.build_list').show();
					timer(response.timer.endTime, response.timer.now, 'b' + response.timer.bid);

					$('.build_dialog').dialog('close');
				}

				hideAjaxLoading();
			});
		});
		dialog.children('form').children('.res.upgrade').show();
	}

	if (building.showDefenseBuildings)
	{
		for (var i = 0; i < 3; i++)
		{
			var link = $('<a href="javascript:;"></a>');
			link.html(building.defense[i].image);
			link.data('building', building.defense[i]);
			link.click(function() {
				showAjaxLoading(dialog);
				var data = $(this).data('building');
				$.getJSON('lib/ajax/get_build_place_data.php', {city: $('#cityChange').val(), buildPlace: data.position}, function(response) {
					dialog.children('form').children('.res.build').children('.build_button').children('input').unbind('click');
					handleAjaxBuildingResponse(dialog, data.position, response);
					hideAjaxLoading();
				});
			});
			dialog.children('.defense_buildings').append(link);
		}
	}
	else
		dialog.children('.defense_buildings').remove();
}

function addBuildListItem(item)
{
	var buildListClone = $('#build_list_dummy').clone();
	buildListClone.removeAttr('id');

	if ((item.kind >= 1 && item.kind <= 6) || item.kind === 22)
		buildListClone.addClass('resource');
	else if (item.kind >= 7 && item.kind <= 21)
		buildListClone.addClass('standard');
	else
		buildListClone.addClass('defense');

	buildListClone.children('span').text(item.text + ':');
	buildListClone.children('strong').children().attr('id', 'b' + item.bid);
	$('.build_list').children('.content').append(buildListClone);
}