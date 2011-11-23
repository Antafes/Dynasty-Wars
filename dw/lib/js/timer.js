//timer for the remaining build time
function timer(datetime, nowDatetime, target)
{
	var months = new Array(
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	);
    var end = new Date(datetime);
    var now = new Date(nowDatetime);
    var diff = Math.round((end - now) / 1000);
    if (diff <= 0)
        window.location.reload();

    var helper = diff % 60;
    var s = helper;
    var m = (diff - helper) / 60;
    helper = m % 60;
    var h = (m - helper) / 60;
	m = helper;
	helper = h % 24;
	var d = (h - helper) / 24;
	h = helper;

    $('#' + target).text((d > 0 ? d + 'd ' : '') + (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s));

	now.setSeconds(now.getSeconds() + 1);
	nowDatetime = months[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();

    setTimeout('timer(\'' + datetime + '\', \'' + nowDatetime + '\', \'' + target + '\')', 1000);
}