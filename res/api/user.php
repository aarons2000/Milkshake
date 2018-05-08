<?php
//Include relevant files...
//Stop include echoing any HTML
ob_start();
include "../../scripts/inc_scripts.php";
ob_end_clean();

//Define SQL variable...
$sql = new \milkshake\SQL();

//Variable to store the action requested...
$action = $_POST['action'];

switch ($action){
	case "get_basic_user":
	default:
		//get_basic_user returns the name only.
		$id = $_POST['uid'];
		$type = $_POST['type'];
		
		//Get table...
		switch ($type){
			case TYPE_STUDENT:
			default:
				$table = "Students";
				break;
			
			case TYPE_PARENT:
				$table = "Parents";
				break;
			
			case TYPE_TEACHER:
				$table = "Teachers";
				break;
		}
		
        //Select one user's basic info from the relevant table...
        $sqlTxt = "SELECT Username,FirstName,MiddleName,LastName FROM {$table} 
                    WHERE ID=:id LIMIT 1";

        //Set parameters...
        $sqlParams = array(
            'id' => $id
        );

        //Query the DB...
        $userResult = $sql->query($sqlTxt, $sqlParams);

        //Print the result in JSON format.
        printf(json_encode($userResult[0]));
}