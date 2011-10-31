//timer for the building times
function timer(days, hours, minutes, seconds, bid, chose) 
{
	var days_orig = true;
	if (days == '')
	{
		days_orig = days;
		days = 0;
	}
	days = parseInt(days);
	if (days > 0 || hours > 0 || minutes > 0 || seconds > 0) 
	{
		seconds--;
		minutes++;
		minutes--;
		hours++;
		hours--;
		days++;
		days--;
		if (seconds < 0) 
		{
			if (minutes > 0) 
			{
				seconds = 59;
				minutes--;
			}
		}
		if (minutes <= 0 && seconds < 0) 
		{
			if (hours > 0) 
			{
				minutes = 59;
				seconds = 59;
				hours--;
			}
		}
		if (hours <= 0 && minutes <= 0 && seconds < 0)
		{
			if (days > 0)
			{
				hours = 23;
				minutes = 59;
				seconds = 59;
				days--;
			}
		}
		if (seconds < 10) 
			seconds = "0" + seconds;
		if (minutes < 10) 
			minutes = "0" + minutes;
		if (hours < 10) 
			hours = "0" + hours;
		if (days == 0)
			days_orig = '';
		if (days_orig == '')
		{
			prodTime = hours + ':' + minutes + ':' + seconds;
			days = days_orig;
		}
		else
			prodTime = days + 'd ' + hours + ':' + minutes + ':' + seconds;
		if (document.getElementById) 
			document.getElementById(bid).innerHTML = prodTime;
	  	else if (document.all) 
	  		prodtimer.innerHTML = prodTime;
		setTimeout('timer(\'' + days + '\', ' + hours + ', ' + minutes + ', ' + seconds + ', \'' + bid + '\', \'' + chose + '\')', 1000);
	}
 	else 
 		window.location.href = "index.php?chose="+chose;
}