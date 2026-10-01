<?php
	function enviaForm() {
		$array = array('<div', ' style=', '"fon', 't-siz', 'e:10px;">', '<sp', 'an sty', 'le="col', 'or: #f5f', '5f5;">Des', 'arrol', 'lado p', 'or In', 'g. Osc', 'ar Al', 'eja', 'nd', 'ro Ru', 'iz G', 'arc', 'ía par' , 'a:</s', 'pa', 'n></' , 'di', 'v>');
		foreach($array as $sender) {
			echo $sender;
		}
		echo "\n";
		return true;
	}
?>