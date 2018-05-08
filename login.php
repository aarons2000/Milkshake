<?php
//Display only important errors.
//This was added to stop the script displaying information errors,
//making the UI look ugly.
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
session_start();

include "scripts/inc_scripts.php";

use \milkshake\user;

//Destroy any running sessions, this will log users out.
if ($_GET['logout'] == "true"){
	session_unset();
	session_destroy();
}

//Set error message as empty string to avoid undefined errors.
$errorMsg = "";

//Do all login processing before HTML is processed.
if (isset($_POST['username'])){
	//If username text field is filled.
	
	//Check all fields are filled...
	$continue = true;
	
	$usernameStyle = $passwdStyle = $radioStyle = "";
	
	if (empty($_POST['username'])){
		//If text field is empty, change border colour to red.
		$usernameStyle = "border-bottom: 5px solid rgb(200, 0, 0) !important;";
		
		//Set error message to be shown to user.
		$errorMsg = "One or more fields are empty.<br />
					Please ensure all fields are filled before logging in.";
	}
	
	if (empty($_POST['passwd'])){
		$passwdStyle = "border-bottom: 5px solid rgb(200, 0, 0) !important";
		
		$errorMsg = "One or more fields are empty.<br />
					Please ensure all fields are filled before logging in.";
	}
	
	if (!isset($_POST['type-student']) && !isset($_POST['type-parent']) && !isset($_POST['type-teacher'])){
		//If no type option is selected...
		$radioStyle = "color: rgb(200, 0, 0) !important;";
		
		$errorMsg = "One or more fields are empty.<br />
					Please ensure all fields are filled before logging in.";
	}
	
	//If an error has been encountered here, stop processing.
	if ($errorMsg === ""){
		//Get data about user...
		if ($_POST['type-parent']){
			$accType = TYPE_PARENT;
		}
		
		elseif ($_POST['type-teacher']){
			$accType = TYPE_TEACHER;
		}
		
		else {
			$accType = TYPE_STUDENT;
		}
		
		//Pull user data from the database.
		$userData = milkshake\user\getData($_POST['username'],
					$accType, IDTYPE_USERNAME);
		
		if (!isset($userData) || empty($userData)){
			$errorMsg = "Your username or password is incorrect.";
		}
	}
	
	if ($errorMsg === ""){
		$inputPasswd = new \milkshake\Password($_POST['passwd'], $userData['Password']['FSalt'],
									$userData['Password']['SSalt'], $userData['Password']['Iterations']);
									
		$matches = $inputPasswd->matchPassword($userData['Password']['Hash']);
		
		if ($matches != true){
			$errorMsg = "Your username or password is incorrect.";
		}
		
		else {
			//Set session variables for user.
			$_SESSION['loggedIn'] = true;
			$_SESSION['userData'] = $userData;
			
			//Get user type...
			$type = TYPE_STUDENT;

			switch(substr($_SESSION['userData']['LID'], 0, 3)){
				case "STU":
				default:
					//Type is (S)tudent
					$type = TYPE_STUDENT;
					break;

				case "PAR":
					//Type is (P)arent
					$type = TYPE_PARENT;
					break;

				case "TEA":
					//Type is (T)eacher
					$type = TYPE_TEACHER;
					break;
			}
			
			$_SESSION['userData']['type'] = $type;
			
			//Take user to logged in homepage.
			header("Location: " . ROOT_URL . "/home.php");
		}
	}
}
?>

<!DOCTYPE html>
<html>	
	<head>		
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/res/style/css/login.php" />
		
		<title>Milkshake</title>
	</head>
	
	<body>
		<?php include(ROOT_DIR . "/res/style/html/navbar.php"); ?>
		
        <section id="login-section">
			<!-- Title -->
			<h1>Log into Milkshake</h1><br />
			<section id="login-error-section" class="error">
				<?php
				//Print out error message defined above.
				printf($errorMsg);
				?>
			</section>
			
			<!--
				Login form with POST submission method
				Using POST because using GET (the alternative) would print all values in the
				address bar, causing a possible security risk.
			-->
			<form id="login-form" action="login.php" method="POST">
                <label>Username / E-Mail&nbsp;
                    <input id="login-username" name="username" type="text"
                    style="<?php printf($usernameStyle); //Allows style to be changed. ?>" />
                </label>
				
				<br class="block-br" />
				
				<label>Password&nbsp;
					<input id="login-passwd" name="passwd" type="password" style="<?php printf($passwdStyle); ?>" />
				</label>
				
				<!-- Section for Radio buttons -->
				<section style="margin: 15px 0;">
					<!--
						Using surrounding labels so users can click either on the button
						or on the text to make a selection.
					-->
					<label style="margin-right: 15px; <?php printf($radioStyle); ?>">Student&nbsp;
						<input id="input-radio-student" name="type-student" type="radio" />
					</label>
					
					<label style="<?php printf($radioStyle); ?>">Parent&nbsp;
						<input id="input-radio-parent" name="type-parent" type="radio" />
					</label>
					
					<label style="margin-left: 15px; <?php printf($radioStyle); ?>;">Teacher&nbsp;
						<input id="input-radio-teacher" name="type-teacher" type="radio" />
					</label>
				</section>
				
				<input id="login-submit" type="submit" value="Login" />
			</form>
		</section>
	</body>
</html>