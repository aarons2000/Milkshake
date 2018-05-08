<?php
namespace milkshake;

define("STRING_ALPHA", 0, TRUE);
define("STRING_NUMERIC", 1, TRUE);
define("STRING_ALPHANUMERIC", 2, TRUE);
define("STRING_HEXADECIMAL", 3, TRUE);
define("STRING_HEX", 3, TRUE);

function randomString($length, $type=STRING_ALPHANUMERIC){
	$string = "";
	
	switch ($type){
		case STRING_ALPHANUMERIC:
		default:
			$charset = str_split("0123456789abcdefghijklmnopqrstuvwxyz");
		
		case STRING_NUMERIC:
			$charset = str_split("0123456789");
		
		case STRING_ALPHA:
			$charset = str_split("abcdefghijklmnopqrstuvwxyz");
		
		case STRING_HEXADECIMAL:
			$charset = str_split("0123456789abcdef");
	}
	
	for ($i=0; $i<$length; $i++){
		$pos = floor(mt_rand(0, count($charset)-1));
		
		$string .= $charset[$pos];
	}
	
	return $string;
}

/*
 * Formats a teacher's name in the form Mr/Mrs Name
 * 
 * @param string $teacherData Array of teacher data from database.
 *
 * @return string Formatted name.
 */
function formatTeacherName($teacherData){
	if ($teacherData['Gender'] == "M"){
		return "Mr. " . $teacherData['LastName'];
	}
	
	else {
		return "Mrs. " . $teacherData['LastName'];
	}
}