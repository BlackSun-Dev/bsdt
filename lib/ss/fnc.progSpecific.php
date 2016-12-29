<?php
	function removeFriend($array) {
		foreach ($array as $key => $value) {
			if ($value['iffStatus'] == 'Friend') {
				unset($array[$key]);
			}
		}
		$array = array_values($array);
		return $array;
	}

	function removeNeutral($array) {
		foreach ($array as $key => $value) {
			if ($value['iffStatus'] == 'Neutral') {
				unset($array[$key]);
			}
		}
		$array = array_values($array);
		return $array;
	}

	function removeEnemy($array) {
		foreach ($array as $key => $value) {
			if ($value['iffStatus'] == 'Enemy') {
				unset($array[$key]);
			}
		}
		$array = array_values($array);
		return $array;
	}

function nameFix($dir) {
	$scanDir = $dir;
	$scanList = '';

	if ($handle = opendir($scanDir)) {
		while (false !== ($entry = readdir($handle))) {
			if ($entry == '.' || $entry == '..') {
			} else {
				$scanList .= $entry . '_';
			}
		}
		closedir($handle);
	}

// Removes last underscore from list of scans and converts list to an array
	$scanList = substr($scanList, 0, -1);
	$scanListArrayOrig = explode('_', $scanList);
	$scanListArrayNew = $scanListArrayOrig;

	foreach ($scanListArrayNew as $key => $value) {
		$tempArray = explode(' ', $value);

		if (strlen($tempArray[3]) == 2) {
			$tempArray[3] = '0' . $tempArray[3];
		}

		if (strlen($tempArray[4]) == 1) {
			$tempArray[4] = '0' . $tempArray[4];
		}

		$newTitle = '';

		foreach ($tempArray as $subValue) {
			$newTitle = $newTitle . $subValue . ' ';
		}

		$newTitle = substr($newTitle, 0, -1);

		$scanListArrayNew[$key] = $newTitle;
	}

	for ($a = 0; $a < count($scanListArrayOrig); $a++) {
		rename($scanDir . '/' . $scanListArrayOrig[$a], $scanDir . '/' . $scanListArrayNew[$a]);
	}
}

function orderBySort($first, $order) {
    $defaultOrder = array("`system`", "FIELD(`iffStatus`, 'Enemy', 'Neutral', 'Friend')", "`x`, `y`", "`typeName`", "`entityID`", "`ownerName`", "`name`");

    if ($first == '`x`, `y`') {
    	$return = ' `x` ' . $order . ', `y` ' . $order;
    } else {
    	$return = ' ' . $first . ' ' . $order;
    }

    foreach ($defaultOrder as $value) {
        if ($value != $first) {
            $return .= ', ' . $value;
        }
    }

    return $return;
}
?>