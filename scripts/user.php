<?php
namespace milkshake\user;

define("TYPE_STUDENT", "S", TRUE);
define("TYPE_PARENT", "P", TRUE);
define("TYPE_TEACHER", "T", TRUE);

define("IDTYPE_ID", 0, TRUE);
define("IDTYPE_USERNAME", 1, TRUE);
define("IDTYPE_EMAIL", 2, TRUE);

function createID($type){
	while (true){
		$id = \milkshake\randomString(6, \STRING_ALPHANUMERIC);
			
		switch($type){
			case TYPE_STUDENT:
			default:
				$id = "STU" . $id;
				
				$sqlParams = array(
					//'table' => 'Students',
					'id' => $id
				);
				
				$table = "Students";
				
				break;
				
			case TYPE_PARENT:
				$id = "PAR" . $id;
				
				$sqlParams = array(
					'id' => $id
				);
				
				$table = "Parents";
				
				break;
				
			case TYPE_TEACHER:
				$id = "TEA" . $id;

				$sqlParams = array(
					'id' => $id
				);
				
				$table = "Teachers";
				
				break;
		}
		
		$sqlTxt = "SELECT * FROM {$table} WHERE LID=:id";
		
		$sql = new \milkshake\SQL();
		
		$result = $sql->query($sqlTxt, $sqlParams);
		
		//If there are no rows with this ID, then return the ID.
		if (count($result) === 0){
			return $id;
		}
	}
}

function createUser($type, $username, $password, $email,
				   	$fname, $mname, $sname, $gender, $special){
	
	//Hash password, and get the array with data
	$passwd = (new \milkshake\Password($password))->createPassword();
	
	//Get timestamp of user joining then format it.
	//The following will format date and time like this "YYYY-MM-DD HH:MM:SS"
	$joined = date('Y-m-d G:i:s');
	
	//Format gender for storage...
	$gender = strtolower($gender);
	if ($gender == "male" || $gender == "m"){
		$gender = "M";
	}
	
	else {
		$gender = "F";
	}
	
	//This is more efficient than typing this string out three times.
	$commonColumns = "Username, Email, FirstName, MiddleName, LastName, Gender, Joined";
	$commonValues = ":username, :email, :fname, :mname, :sname, :gender, :joined";
	
	//Set common parameters
	$sqlParams = array(
		'username' => $username,
		'email' => $email,
		'fname' => $fname,
		'mname' => $mname,
		'sname' => $sname,
		'gender' => $gender,
		'joined' => $joined
	);
	
	var_dump($sqlParams);
	
	switch ($type){
		case TYPE_STUDENT:
		default:
			/*
			 * In this case, special data will be formatted like this...
			 * array(
			 *   'YrGrp' => x,
			 * 	 'FormGroup' => y
			 * )
			 */
			
			$yearGrp = $special['YrGrp'];
			$form = $special['FormGroup'];
			
			$id = createID(TYPE_STUDENT);
			
			//All validation will have been carried out before calling
			//this function.
			
			//Create an SQL statement and its parameters.
			$sqlText = "INSERT INTO Students " .
					"({$commonColumns}, FormGroup, YrGrp, LID) " .
					"VALUES({$commonValues}, :form, :year, :sid);";
			
			$sqlParams['form'] = $form;
			$sqlParams['year'] = $yearGrp;
			$sqlParams['sid'] = $id;
			
			
			break;
			
		case TYPE_PARENT:
			$id = createID(TYPE_PARENT);
			
			$sqlText = "INSERT INTO Parents " . 
					"({$commonColumns}, LID) " .
					"VALUES({$commonValues}, :pid);";
			
			$sqlParams['pid'] = $id;
			
			break;
		
		case TYPE_TEACHER:
			$isAdmin = $special['IsAdmin'];
			
			$id = createID(TYPE_TEACHER);
			
			$sqlText = "INSERT INTO Teachers " .
					"({$commonColumns}, IsAdmin, LID) " .
					"VALUES({$commonValues}, :ia, :tid);";
			
			$sqlParams['ia'] = $isAdmin;
			$sqlParams['tid'] = $id;
			
			break;
	}
	
	$sql = new \milkshake\SQL();
	
	$userResult = $sql->insert($sqlText, $sqlParams);
	
	$passwdObj = new \milkshake\Password($password);
	
	$accID = $sql->lastId();
	$algo = $passwdObj->algo;
	$passwdHash = $passwdObj->password;
	$iterations = $passwdObj->iterations;
	$salt1 = $passwdObj->salt1;
	$salt2 = $passwdObj->salt2;
	
	printf($iterations . "<br /><br />");
	
	//Get account type as string.
	switch ($type){
		case TYPE_STUDENT:
		default:
			$accType = "STU";
			break;
		
		case TYPE_PARENT:
			$accType = "PAR";
			break;
		
		case TYPE_TEACHER:
			$accType = "TEA";
			break;
	}
	
	$passwdSqlText = "INSERT INTO Passwords " . 
					"(AccountType, AccountID, Algorithm, Iterations, Hash, FSalt, SSalt, IsInUse) " .
					"VALUES(:acctype, :accid, :algo, :iter, :hash, :salt1, :salt2, 1);";
	
	$passwdSqlParams = array(
		'acctype' => $accType,
		'accid' => $accID,
		'algo' => $algo,
		'hash' => $passwdHash,
		'iter' => $iterations,
		'salt1' => $salt1,
		'salt2' => $salt2
	);
	
	$sql->insert($passwdSqlText, $passwdSqlParams);
}

/**
 * This function gets data from the database 
 * and stores it in the session array.
 * 
 * @param string $identifier Identifying parameter for user.
 * @param int $accType Type of account.
 * @param int $idType Identifier type.
 * 
 * @return array Array containing the relevant data.
 */
function getData($identifier, $accType, $idType){
	//Switch through various account types.
	//Values set here will be used in the SQL statement.
	switch ($accType){
		case TYPE_STUDENT:
		default:
			$table = "Students";
			break;
		
		case TYPE_PARENT:
			$table = "Parents";
			break;
		
		case TYPE_TEACHER;
			$table = "Teachers";
			break;
	}
	
	//Switch through identifier types.
	switch ($idType){
		case IDTYPE_ID:
			//This is the short ID (primary key), not
			//the long ID which is used on the front-end.
			$idColumn = "ID";
			break;
		
		case IDTYPE_USERNAME:
		default:
			$idColumn = "Username";
			break;
		
		case IDTYPE_EMAIL:
			$idColumn = "Email";
			break;
	}
	
	//Select only ONE entry from the relevant table.
	$sqlText = "SELECT * FROM {$table} " .
				"WHERE {$idColumn}=:identifier LIMIT 1";

	$sqlParams = array('identifier' => $identifier);

	$sql = new \milkshake\SQL();

	//Query the table to return the relevant data.
	$userResult = $sql->query($sqlText, $sqlParams);

	$accType = \substr($userResult[0]['LID'], 0, 3);

	//Query the Passwords table for the user's password.
	$passwdSqlText = "SELECT * FROM Passwords " . 
					"WHERE AccountType=:acctype AND AccountID=:accid AND IsInUse=1 LIMIT 1";

	$passwdSqlParams = array(
		'acctype' => $accType,
		'accid' => $userResult[0]['ID']
	);

	$passwdResult = $sql->query($passwdSqlText, $passwdSqlParams);

	//Join arrays together to make one overall result.
	try {
		$passwdResult = array(
			'Password' => $passwdResult[0]
		);
		
		$result = \array_merge($userResult[0], $passwdResult);
	}

	catch (\Exception $e) {
		//Return false if arrays cannot be merged correctly.
		return false;
	}

	//For debugging.
//	printf("<pre>");
//	var_dump($result);
//	printf("</pre>");
	
	return $result;
}