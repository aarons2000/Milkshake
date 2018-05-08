<?php
include "scripts/inc_scripts.php";

//If user is not logged in, don’t allow them to use the page...
if (empty($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false){
	header("Location: " . ROOT_URL);
}
?>

<!DOCTYPE html>
<html>	
	<head>
		<!-- Include index stylesheet since it includes styles for 1/3 width columns -->
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/res/style/css/index.php" />
		
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/res/style/css/home.php" />
		
		<title>Milkshake</title>
	</head>
	
	<body>
		<?php include(ROOT_DIR . "/res/style/html/navbar.php"); ?>
		
		<section id="home-main-content">
			<!-- Print out user's name, welcomng them to the site -->
			<h1>Welcome to Milkshake, <?php echo($_SESSION['userData']['FirstName']); ?></h1>
			
			<?php
				//Get the type of account logged in, then display relevant info.
				switch ($_SESSION['userData']['type']){
					case TYPE_STUDENT:
					default:
						//For each type, include the relevant page containing the data to be
						//shown.
						include(ROOT_DIR . "/res/style/html/homepage/student.php");
						break;
					
					case TYPE_PARENT:
						include(ROOT_DIR . "/res/style/html/homepage/parent.php");
						break;
					
					case TYPE_TEACHER:
						include(ROOT_DIR . "/res/style/html/homepage/teacher.php");
						break;
				}
			?>
		</section>
	</body>
</html>