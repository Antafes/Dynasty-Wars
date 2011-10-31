$(function() {
	$('#create_offer').click(function() {
		$('#create_offer_container').show();
		$('#search_container').hide();
	});
	$('#search').click(function() {
		$('#create_offer_container').hide();
		$('#search_container').show();
	});
});