function showAjaxLoading(target)
{
	if ($('#ajaxLoading').length > 0)
		$('#ajaxLoading').remove();

	var width = $(target).outerWidth();
	var height = $(target).outerHeight();
	var ajaxLoading = $('<div id="ajaxLoading"></div>');
	ajaxLoading.css({
		position: 'absolute',
		top: height / 2 + 16,
		left: width / 2 + 16,
		zIndex: 9000
	});
	ajaxLoading.append('<img src="pictures/ajax-loader.gif" />');
	target.prepend(ajaxLoading);
}

function hideAjaxLoading()
{
	$('#ajaxLoading').remove();
}