<?php
namespace milkshake\user2;

define("TYPE_STUDENT2", 0, TRUE);
define("TYPE_PARENT2", 1, TRUE);
define("TYPE_TEACHER2", 2, TRUE);

function createID2($type){
	while (true){
		$id = \milkshake\randomString(6, \STRING_ALPHANUMERIC);
		
		switch($type){
			case TYPE_STUDENT:
			default:
				$id = "STU" . $id;

				$sqlParams = array(
					//'table' => 'Students',
					'column' => 'StudentID',
					'id' => $id
				);

				$table = "Students";

				break;

			case TYPE_PARENT:
				$id = "PAR" . $id;

				$sqlParams = array(
					'column' => 'ParentID',
					'id' => $id
				);

				$table = "Parents";

				break;

			case TYPE_TEACHER:
				$id = "TEA" . $id;

				$sqlParams = array(
					'column' => 'TeacherID',
					'id' => $id
				);

				$table = "Teachers";

				break;
		}

		$sqlTxt = "SELECT * FROM {$table} WHERE :column=:id";
		
		$sql = new \milkshake\SQL();
		
		$result = $sql->query($sqlTxt, $sqlParams);
		
		//If there are no rows with this ID, then return the ID.
		if (count($result) === 0){
			return $id;
		}
	}
}

function createUser2($type, $username, $password, $email,
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
	$commonValues = ":username, :email, :fname, :mname, :lname, :gender, :joined";
	
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
					"({$commonColumns}, FormGroup, YrGrp StudentID) " .
					"VALUES({$commonValues}, :form, :year, :sid);";
			
			$sqlParams['form'] = $form;
			$sqlParams['year'] = $yearGrp;
			$sqlParams['sid'] = $id;
			
			
			break;
		
		case TYPE_PARENT:
			$id = createID(TYPE_PARENT);
			
			$sqlText = "INSERT INTO Parents " . 
					"({$commonColumns}, ParentID) " .
					"VALUES({$commonValues}, :pid);";
			
			$sqlParams['pid'] = $id;
			
			break;
		
		case TYPE_TEACHER:
			$isAdmin = $special['IsAdmin'];
			
			$id = createID(TYPE_TEACHER);
			
			$sqlText = "INSERT INTO Teachers " .
					"({$commonColumns}, IsAdmin, TeacherID) " .
					"VALUES({$commonValues}, :ia, :tid);";
			
			$sqlParams['ia'] = $isAdmin;
			$sqlParams['tid'] = $id;
			
			break;
	}
	
	$sql = new \milkshake\SQL();
	
	$result = $sql->query($sqlText, $sqlParams);
	
	return $result;
}