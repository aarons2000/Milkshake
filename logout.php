<?php
include "scripts/inc_scripts.php";

header("Location: " . ROOT_URL);

if (isset($_SESSION['loggedIn'])){
	//Unset all variables and destroy session data...
	session_unset();
	session_destroy();
}