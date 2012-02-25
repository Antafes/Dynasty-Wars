<?php
namespace bl\wikiParser;

class WikiParser {

	var $preg_replacements;

	var $normal_replacements = array();

	var $preg_user_replacements = array(array(),array());
	var $normal_user_replacements = array();

	var $predefined_callbacks = array(
		'#\[code\](.*?)\[\/code\]#si' => 'return \'<div class="codebox">\'.preg_replace(\'#&lt;\?php&nbsp;\s<br />#\',\'\',highlight_string(\'<?php \'.$matches[1], true)).\'</div>\';',
		'#\[html\](.*?)\[\/html\]#si' => 'return $matches[1];',
		"/(?<=\n)(\*+.*\n)+/" => 'return "<ul>\n$matches[0]</ul>";',
		"#(?<=\n)\*.*?(?:\n|\r\n)#" => 'global $stars;
		unset($new_text);
		$layer = $matches[0];
		$length = strlen($layer);
		for ($i = 0; $i <= $length; $i++)
		{
			if (substr($layer, $i+1, 1) != \'*\' && $stars > $i)
			{
				while ($stars >= $i+1)
				{
					$new_text .= "</ul>\n";
					$stars--;
				}
			}
			if (substr($layer, $i, 1) == \'*\' && $stars < $i)
			{
				$stars++;
				$new_text .= "<ul>\n";
			}
			if ($i == $stars && substr($layer, $i+1, 1) != \'*\')
				$new_text .= "<li>";
			if (substr($layer, $i+1, 1) != \'*\')
			{
				$new_text .= substr($layer, $i+2, $length).\'</li>\';
				break;
			}
		}
		return $new_text;'
	);

	var $callbacks;

	function __construct() {
		$this->preg_replacements = array( //Die Ersetzungen, die eh da sind
			'/===\s*(.*?)\s*===/' => "<h3>$1</h3>\n",										//Überschrift 3. Ordnung
			'/==\s*(.*?)\s*==/' => "<h2>$1</h2>\n",											//Überschrift 2. Ordnung
			'/\[\[Bild\:(.*?)\|(.*?)\]\]/' => "<div class=\"image\">\n<img src=\"$1\" alt=\"$2\"><br />\n$2</div>\n",																					//Bild mit Beschreibung
			'/\[\[Bild\:(.*?)\]\]/' => '<img src="$1" alt="$1">',							//Bild ohne Beschreibung
			'/\[\[(.[^\|]*?)\]\]/' => '<a href="$1">$1</a>',						//Interne Links
			'/\[\[(.[^\|]*?)\|(.*?)\]\]/' => '<a href="$1">$2</a>',			//Interne Links mit Namen
			'/\'\'\'(.*?)\'\'\'/' => '<strong>$1</strong>',											//Fett
			'/\'\'(.*?)\'\'/' => '<em>$1</em>',												//Kursiv
		);
		$this->code_preg_replacements = array(
			'/(?<=\<br \/\>)&nbsp;&nbsp;&nbsp;&nbsp;/' => '<span style="white-space: pre;">&#9;',
			'/&nbsp;&nbsp;&nbsp;&nbsp;/' => '&#9;',
			'/(&#9;)(?!&#9;)/' => '$1</span>'
		);
	}

	//prepare_reg_array
	//Umwandlung vom assoziativen Array in einen, den preg_replace versteht

	//Erwartete Argumente:
	// $array = array($pattern1 => $replace1, $pattern2 => $replace2, ...)
	//Rückgabewerte: array(array($pattern1,$pattern2,...),array($replace1,$replace2,...))
	function prepareRegArray($array) {
		$retval = array(array(),array());
		foreach($array as $key => $val) {
			$retval[0][]=$key;
			$retval[1][]=$val;
		}
		return $retval;
	}

	//set_array
	//Für benutzerdefinierte Ersetzungsregeln, $isReg steuert ob die Ersetzung ein regulärer Ausdruck ist

	//Erwartete Argumente:
	// Wenn $isReg==true:
	//  $array = array($pattern1 => $replace1, $pattern2 => $replace2, ...)
	// Ansonsten:
	//  $array = array($from1 => $to1, $from2 => $to2, ...)
	// $isReg = boolean
	//Rückgabewerte: keine
	function setArray($array,$isReg=false) {
		if($isReg) {
			$array = $this->prepare_reg_array($array);
			$this->preg_user_replacements[0] = array_merge($this->preg_user_replacements[0], $array[0]);
			$this->preg_user_replacements[1] = array_merge($this->preg_user_replacements[1], $array[1]);
		}
		else
			$this->normal_user_replacements = array_merge($this->normal_user_replacements, $array);
	}

	//clear_array
	//Benutzerdefinierte Ersetzungsregeln löschen, $isReg steuert ob die regulären Ausdrücke oder die normalen gelöscht werden

	//Erwartete Argumente:
	// $isReg = boolean
	//Rückgabewerte: keine
	function clearArray($isReg=false) {
		if($isReg)
			$this->preg_user_replacements = array(array(),array());
		else
			$this->normal_user_replacements = array();
	}

	//create_callback
	//Benutzerdefinierte Callbacks über preg_replace_callback

	//Auf die Treffer des regulären Ausdrucks kann mit $matches[n] zugegriffen werden
	//Erwartete Argumente:
	// $array = array($pattern1 => $function1, $pattern2 => $function2, ...)
	//Rückgabewerte: keine
	function createCallback($array) {
		foreach($array as $key=>$val) {
			$this->callbacks .= '$string = preg_replace_callback(\''.$key.'\', create_function(\'$matches\', \''.strtr($val,array('\''=>'\\\'')).'\'), $string);';
		}
	}

	//parseIt
	//Ausführung des ganzen Geparse

	//Erst werden die Callback-Funktionen durchgeführt (sofern vorhanden), dann kommen die benutzerdefinierten
	//Ersetzungsmuster und zum Schluss die vordefinierten Muster.
	//Erwartete Argumente:
	// $string = string
	//Rückgabewerte: Der geparste String $string
	function parseIt($string)
	{
		$stars = 0;
		$htmlentities = preg_split('#(\[code\].*?\[/code\]|\[html\].*?\[/html\])#si', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
		unset($string);

		foreach ($htmlentities as $part)
		{
			if (strlen($part) < 6)
				$length = strlen($part);
			else
				$length = 6;

			if ($length > 0)
			{
				if (substr_compare($part, '[code]', 0, $length, true) != 0 && substr_compare($part, '[html]', 0, $length, true) != 0)
					$part = htmlentities($part);

				$string .= $part;
			}
		}

		$string .= "\n";
		eval($this->callbacks);

		if(count($this->preg_user_replacements[1]))
			$string = preg_replace($this->preg_user_replacements[0],$this->preg_user_replacements[1],$string);

		if(count($this->normal_user_replacements))
			$string = strtr($string, $this->normal_user_replacements);

		unset($this->callbacks);
		$this->createCallback($this->predefined_callbacks);
		eval($this->callbacks);
		$string = preg_replace('#\[\[(</span><span style=".*?">)(.*?)(</span><span style=".*?">)(.*?)(</span><span style=".*?">)(.*?)(</span><span style=".*?">)\]\]#', '[[\2\4\6]]', $string);
		eval($this->callbacks);
		$prepared_array = $this->prepareRegArray($this->preg_replacements);
		$code_prepared_array = $this->prepareRegArray($this->code_preg_replacements);

		if ($string) {
			$code = preg_split('#(<div class="codebox"><code>.*?</code></div>)#s',$string, -1, PREG_SPLIT_DELIM_CAPTURE);
			unset($string);
			foreach ($code as $code_part)
			{
				if (strlen($code_part) < 21)
					$maxlen = strlen($code_part);
				else
					$maxlen = 21;

				if ($maxlen > 0)
				{
					if (substr_compare($code_part,'<div class="codebox">',0,$maxlen,true)==0)
					{
						$code_part = preg_replace($code_prepared_array[0],$code_prepared_array[1],$code_part);
						$string .= '</p>'.$code_part.'<p>';
					}
					else
						$string .= preg_replace($prepared_array[0],$prepared_array[1],$code_part);
				}
			}
		}

		$string = strtr($string,$this->normal_replacements);
		return $string;
	}
}
?>