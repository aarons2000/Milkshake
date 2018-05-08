<?php
namespace milkshake;
include "scripts/inc_scripts.php";

//$a = createUser(TYPE_STUDENT, "Benster1808", "123", "N/A",
//			   	"Banjamin", "", "Jackman", "f", array('YrGrp' => 1, 'FormGroup' => "Grape Int."));
//
//$a = createUser(TYPE_STUDENT, "Benster1808", "123", "N/A",
//			   	"Banjamin", "", "Jackman", "f", array('YrGrp' => 1, 'FormGroup' => "Grape Int."));


//$a = createUser(TYPE_STUDENT, "Sop", "P@ss", "N/A",
//			   	"Dane", "", "Soppit", "m", array('YrGrp' => 4, 'FormGroup' => "3rd Time's a Charm :)"));

#var_dump($a);

$p = new Password("password");

$p->createPassword("password");

var_dump($p);