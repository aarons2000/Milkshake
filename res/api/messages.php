<?php
///**
// * Takes all original messages and all replies and
// * forms conversations.
// * 
// * @param mixed $originals Array of all original messages.
// * @param mixed $replies Array of all replies.
// * 
// * @return mixed Associative array of all conversations.
// */
//function create_conversation($originals, $replies){
//	/*
//	 * STRUCTURE OF COVNERSATION ARRAY...
//	 * array(
//	 *	[ID] => array(
//	 *		Subject => string,
//	 *		Content => string,
//	 *		Sender => int,
//	 *		SenderType => char,
//	 *		SendDate => timestamp
//	 *		Reply => array(...)
//	 *	)
//	 * )
//	 */
//	 
//	 $convos = array();
//	 
//	 foreach ($originals as $o){
//	 	$convos[$o['ID']] = array(
//			'Subject' => $o['Subject'],
//			'Content' => $o['Content'],
//			'Sender' => $o['SenderID'],
//			'SenderType' => $o['SenderType']
//		);
//	 }
//}

//Include relevant files...
//Stop include echoing any HTML
ob_start();
include "../../scripts/inc_scripts.php";
ob_end_clean();

//Define SQL variable...
$sql = new \milkshake\SQL();

//Variable to store the action requested...
$action = $_POST['action'];

////Set session data to allow testing with Postman.
//$_SESSION['userData'] = milkshake\user\getData(21, TYPE_STUDENT, IDTYPE_ID);
//$_SESSION['userData']['type'] = TYPE_STUDENT;

switch ($action){
	//Switch through potential actions...
	case "get_messages":
	default:
		//If the request is to retreive messages...
		$uid = $_POST['uid'];
		$type = $_POST['type'];
		
		//Check user is allowed to access...
		if (isset($_SESSION['userData']) &&
			$uid == $_SESSION['userData']['ID'] &&
			$type == $_SESSION['userData']['type']){
			
			//Get relevant messages addressed to the current user...
			$msgSql = "SELECT * FROM Messages WHERE 
						RecipientID=:rid AND
						RecipientType=:rt";
			
			$msgParams = array(
				'rid' => $uid,
				'rt' => $type
			);
			
			$msgs = $sql->query($msgSql, $msgParams);
			
			//Print messages, encoded in JSON
			printf(json_encode($msgs));
		}
		
		else {
			//If user does not have permission, give error...
			printf("You do not have permission to view these messages.");
		}
		
		break;
	
	//If case is to send reply...
	case "send_reply":
		//Store the SQL statement to send a reply.
		$msgSql = "INSERT INTO Messages " . 
				"(Subject, Content, SenderID, SenderType, RecipientID, RecipientType, ReplyTo) " .
				"VALUES(:sub, :cont, :sid, :st, :rid, :rt, :replyto)";
		
		//Find the sender and recipient data given the original message's ID.
		$findSql = "SELECT SenderID, SenderType, RecipientID, RecipientType
					FROM Messages WHERE ID=:replyto LIMIT 1";
		
		//Define paremeters for the above SQL.
		//Firstly for $findSql.
		$sqlParams = array(
			'replyto' => $_POST['replyTo']
		);
		
		$found = $sql->query($findSql, $sqlParams)[0];
		
		//Only continue if the user logged in is allowed to.
		if ($found['RecipientID'] != $_SESSION['userData']['ID']){
			//Kill script with reason.
			die("No permission");
		}
		
        //Now add the found parameters to the above array.
        //Also add htmlspecialchars() to escape any HTML tags
        //to prevent XSS attacks (malicious code injection).
        $sqlParams['sub'] = htmlspecialchars($_POST['subject']);
        $sqlParams['cont'] = htmlspecialchars($_POST['content']);
		
		//Using recipient because senders and recipients are
		//reversed for a reply.
		$sqlParams['sid'] = $found['RecipientID'];
		$sqlParams['st'] = $found['RecipientType'];
		
		$sqlParams['rid'] = $found['SenderID'];
		$sqlParams['rt'] = $found['SenderType'];
		
		//Now send the reply query...
		$sql->insert($msgSql, $sqlParams);
}