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
		
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/res/style/css/messages.php" />
		
		<script type="text/javascript">
			/*Get variables from PHP.*/
			var uid = <?php echo($_SESSION['userData']['ID']); ?>;
			var userType = "<?php echo($_SESSION['userData']['type']); ?>";
		</script>
		
		<script type="text/javascript" src="<?php echo ROOT_URL; ?>/scripts/js/messages.js"></script>
		
		<title>Milkshake</title>
	</head>
	
	<body id="messages-body">
		<!-- Section for the reply box to appear,
			 will be hidden to begin with. -->
		<section id="messages-reply">
			<div>
				<!-- No typing allowed in here, this field will
				 be set by JavaScript. -->
				<label>Reply To</label>

				<label>Subject</label>

				<label>Content</label>
			</div>

			<div>
				<form id="messages-reply-form" name="reply-form" method="POST">
					<input type="text" id="messages-reply-to" name="reply-to" disabled />

					<input type="text" id="messages-reply-subject" name="reply-subject" disabled />

					<textarea id="messages-reply-content" name="reply-content" style="height: 10em;"></textarea>

					<input type="button" id="messages-reply-submit" name="reply-submit" value="Send" />
				</form>
			</div>
		</section>
		
		
		<section id="messages-navbar">
			<?php include(ROOT_DIR . "/res/style/html/navbar.php"); ?>
		</section>
		
		<!-- These will be generated by JavaScript with custom 'data-*' attributes,
			to ID the messages. -->
		<section id="messages-main-content">
			<div id="messages-preview-pane">
				<ul id="messages-preview-list">
					<!-- Messages will be added here by JavaScript -->
				</ul>
			</div>
			
			<div id="messages-content-pane">
				
			</div>
		</section>
		
		<button id="messages-reply-btn">Reply</button>
	</body>
</html>