<?php
//Destroy any running sessions, this will log users out.
if (session_id() != 0){
	session_unset();
	session_destroy();
}
?>

<!DOCTYPE html>
<html>	
	<head>
		<?php
			include "scripts/inc_scripts.php";
		?>
		
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_URL; ?>/res/style/css/index.php" />
		
		<title>Milkshake</title>
	</head>
	
	<body>
		<?php include(ROOT_DIR . "/res/style/html/navbar.php"); ?>
		
        <section id="index-main-content">
            <h1>Welcome to Milkshake Student Planner</h1>
            
            <!-- Seperate the following into three columns. -->
            <div id="index-content-columns">
                <div class="index-content-column" width="33%">
                    <h3 class="index-column-header">For Students</h3>

                    <div>
                        <!-- Image is in container to allow its size to be changed. -->
                        <img src="<?php echo ROOT_URL; ?>/res/img/circle-logo.gif" alt="Students Feature Icon" width="50%" style="position: relative;" />
                    </div>
                    
                    <ul>
                        <li>Students can easily check homework set by teachers.</li>
                        <li>Powerful 2-way messagging allows students to reply to teachers' messages.</li>
                        <li>Students can manage their work easily with simple management tools.</li>
                    </ul>
                </div>

                <div class="index-content-column" width="33%">
                    <h3 class="index-column-header">For Parents</h3>
                    
                    <div>
                        <img src="<?php echo ROOT_URL; ?>/res/img/circle-logo.gif" alt="Students Feature Icon" width="50%" style="position: relative;" />
                    </div>
                    
                    <ul>
                        <li>Parents can view childrens' homework and reports.</li>
                        <li>Parents can message students' teachers on their behalf to set up meeting etc.</li>
                    </ul>
                </div>

                <div class="index-content-column" width="33%">
                    <h3 class="index-column-header">For Schools</h3>
                    
                    <div>
                        <img src="<?php echo ROOT_URL; ?>/res/img/circle-logo.gif" alt="Students Feature Icon" width="50%" style="position: relative;" />
                    </div>
                    
                    <ul>
                        <li>Powerful management tools to allow schools to ensure all students have acces to their Milkshake account.</li>
                        <li>Teachers can easily monitor which tasks students have completed and handed in and which ones they haven't.</li>
                        <li>Calendar allows schools to notify students and parents of upcoming events and activities taking place at the school.</li>
                    </ul>
                </div>
            </div>
        </section>
	</body>
</html>