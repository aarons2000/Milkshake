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
	case "get_tasks":
	default:
		if ($_POST['studentId'] == "SESSION"){
			//If request says to get ID from current session...
			$studentId = $_SESSION['userData']['ID'];
		}
		
		else {
			$studentId = $_POST['studentId'];
		}
		
		//If user is not allowed to access this info...
		if ($_SESSION['userData']['ID'] != $studentId){
			//Kill the script, giving a reason.
			die("No Permission");
		}
		
		$entriesSql = "SELECT * FROM HomeworkEntries " . 
			"WHERE StudentID=:sid AND Status<>'R'";
		
		$entriesParams = array('sid' => $studentId);
		
		//Query database for Homework Entries.
		$entries = $sql->query($entriesSql, $entriesParams);
		
		//Initialize array to store entry and task IDs.
		$entryIds = array();
		$hwkIds = array();
		
		//Iterate through entry results and add IDs to array.
		foreach ($entries as $entry){
			$entryIds[] = $entry['ID'];
			
			//Also get homework task IDs...
			$hwkIds[] = $entry['HomeworkID'];
		}
		
		$tasksSql = "SELECT * FROM Homework " . 
			"WHERE ID IN (:hids)";
		
		//Format ID array so it can be used by SQL.
		$tasksParams = array('hids' =>
			implode(',', array_map('intval', $hwkIds)));
		
		$tasks = $sql->query($tasksSql, $tasksParams);
		
		//Merge both result arrays into one.
		$returnData = array(
			'entries' => $entries,
			'tasks' => $tasks
		);
		
		//Encode result array in JSON and print.
		printf(json_encode($returnData));
		
		break;
		
    case "change_status":
        //CHANGE
        //Student ID is now taken from session.
        $studentId = $_SESSION['userData']['ID'];
        $newStatus = $_POST['status'];
        $entryId = $_POST['entryId'];
        
        //Removed permission check section since
        //only the logged in user's info can be
        //changed now due to the ID being taken
        //from the session.
        
        $changeSql = "UPDATE HomeworkEntries " . 
            "SET Status=:new WHERE ID=:id";
        
        $changeParams = array(
            'new' => $newStatus,
            'id' => $entryId
        );
        
        $sql->insert($changeSql, $changeParams);
        
            break;
}