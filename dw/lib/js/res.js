//aktive ressourcen berechnung
function r(food, wood, rock, iron, paper, koku, update_food, update_wood, update_rock, update_iron, update_paper, update_koku, storage) {
	var newfood = food + update_food;
	var newwood = wood + update_wood;
	var newrock = rock + update_rock;
	var newiron = iron + update_iron;
	var newpaper = paper + update_paper;
	var newkoku = koku + update_koku;
	var dispfood = Math.floor(newfood);
	var dispwood = Math.floor(newwood);
	var disprock = Math.floor(newrock);
	var dispiron = Math.floor(newiron);
	var disppaper = Math.floor(newpaper);
	var dispkoku = Math.floor(newkoku);
	if (newfood < 0)
		dispfood = 0;
	else if (newfood >= storage)
		newfood = dispfood = Math.floor(food);
	if (newwood < 0)
		dispwood = 0;
	else if (newwood >= storage)
		newwood = dispwood = Math.floor(wood);
	if (newrock < 0)
		disprock = 0;
	else if (newrock >= storage)
		newrock = disprock = Math.floor(rock);
	if (newiron < 0)
		dispiron = 0;
	else if (newiron >= storage)
		newiron = dispiron = Math.floor(iron);
	if (newpaper < 0)
		disppaper = 0;
	else if (newpaper >= storage)
		newpaper = disppaper = Math.floor(paper);
	if (newkoku < 0)
		dispkoku = 0;
	else if (newkoku >= storage)
		newkoku = dispkoku = Math.floor(koku);
	$('#dfood').text(dispfood);
	$('#dwood').text(dispwood);
	$('#drock').text(disprock);
	$('#diron').text(dispiron);
	$('#dpaper').text(disppaper);
	$('#dkoku').text(dispkoku);
	var string = newfood + ',' + newwood + ',' + newrock + ',' + newiron + ',' + newpaper + ',' + newkoku + ',' + update_food + ',' + update_wood + ',' + update_rock + ',' + update_iron + ',' + update_paper + ',' + update_koku + ',' + storage;
	setTimeout('r(' + string + ')', 1000);
}