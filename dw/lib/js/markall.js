//auswahl aller botschaften
function all()
{
	var the_form = $('.del');
	if (the_form.cbuttonAll.checked == true)
	{
		for (var i = 0; i < the_form.elements.length; i++)
			if (the_form.elements[i].name != 'cbuttonAll' && the_form.elements[i].type == 'checkbox')
				the_form.elements[i].checked = true;
	}
	else
		for (var i = 0; i < the_form.elements.length; i++)
			the_form.elements[i].checked = false;
}
//auswahl einer einzelnen botschaft
function selectThis() {
 var the_form = document.del;
 if (the_form.cbuttonAll.checked == true) {
  for (var i = 0; i < the_form.elements.length; i++) {
   if(the_form.elements[i].checked == false) {
    the_form.cbuttonAll.checked == false;
   }
  }
 }
}