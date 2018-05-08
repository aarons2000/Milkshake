<!-- Define navbar section -->
<section id="navbar">
	<!-- Add logo to navbar -->
	<a href="<?php ROOT_URL; ?>">
		<img id="navbar-logo" src="<?php echo ROOT_URL; ?>/res/img/logo.svg" alt="Milkshake Logo" href="<?php echo ROOT_URL; ?>" />
	</a>
	
    <?php
    //What is displayed next depends whether or not the user is logged in.
    $sql = new milkshake\SQL();

    /*
     * To check if the user is logged in, I use the $_SESSION array.
     * 
     * The following is set when the user logs in, and is unset when they log out.
     *
     * To access session variables, the session must be initialised, which is also what happens below...
     */

    //Using isset() to avoid undefined index errors.
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
        //Determine what type of user...
        $type = "S";

        switch(substr($_SESSION['userData']['LID'], 0, 3)){
            case "STU":
            default:
                //Type is (S)tudent
                $type = "S";
                break;

            case "PAR":
                //Type is (P)arent
                $type = "P";
                break;

            case "TEA":
                //Type is (T)eacher
                $type = "T";
                break;
        }
        
        
        if ($type == "S"){
            //If user is a student...
            printf("<div class=\"navbar-menu\">				
                <a href=\"" . ROOT_URL . "/homework.php\" class=\"navbar-menu-btn\">HOMEWORK</a>
                
                <a href=\"" . ROOT_URL . "/messages.php\" class=\"navbar-menu-btn\">MESSAGES</a>
                
                <a href=\"" . ROOT_URL . "/settings.php\" class=\"navbar-menu-btn\">SETTINGS</a>
                
                <a href=\"" . ROOT_URL . "/logout.php\" class=\"navbar-menu-btn\">LOGOUT</a>
            </div>");
        }
        
        elseif ($type == "P"){
            //If user is a parent...
            //Get relationships to students...
            $sqlTxt = "SELECT * FROM Students WHERE
            ID=(SELECT StudentID FROM ParentRelationships WHERE ParentID=:id)";
            
            $sqlParams = array(
                'id' => $_SESSION['userData']['ID']
            );
            
            $relatedStudents = $sql->query($sqlTxt, $sqlParams);
            
            $dropdownContent = "";
            
            foreach ($relatedStudents as $student){
                //Iterate through related students and generate content for dropdown...
                $dropdownContent .= "<a href=\"" . ROOT_URL . "/viewchild.php?
                id=" . $student['ID'] . "\">" . $student['FirstName'] . "</a>";
                
                //Page for parents viewing children will be "milk-shake.uk/viewchild.php
                //using GET parameters to set IDs.
            }
            
            printf("<div class=\"navbar-menu\">
                        <div class=\"navbar-dropdown\">
                            <a href=\"#\" class=\"navbar-dropdown-btn navbar-menu-btn\">CHILDREN</a>

                            <div class=\"navbar-dropdown-content\">
                                {$dropdownContent}
                            </div>
                        </div>
                        
                        <a href=\"" . ROOT_URL . "/settings.php\" class=\"navbar-menu-btn\">SETTINGS</a>

                        <a href=\"" . ROOT_URL . "/logout.php\" class=\"navbar-menu-btn\">LOGOUT</a>
                    </div>");
        }
        
        else {
            //If user is a teacher...
            
            //Get classes related to teacher...
            $sqlTxt = "SELECT * FROM Classes WHERE TeacherID=:tid";
            
            $sqlParams = array('tid' => $_SESSION['userData']['ID']);
            
            $classes = $sql->query($sqlTxt, $sqlParams);
            
            $dropdownContent = "";
            
            foreach($classes as $class){
                //'class' is a reserved word but '$class' isn't so I can use it as a variable name.
                $dropdownContent .= "<a href=\"" . ROOT_URL . "/class.php?id=" . $class['ClassID'] . "\">" .
                                    $class['Name'] . "</a>";
            }
            
            printf("<div class=\"navbar-menu\">
                        <div class=\"navbar-dropdown\">
                            <a href=\"#\" class=\"navbar-dropdown-btn navbar-menu-btn\">CLASSES</a>

                            <div class=\"navbar-dropdown-content\">
                                {$dropdownContent}
                            </div>
                        </div>
                        
                        <a href=\"" . ROOT_URL . "/settings.php\" class=\"navbar-menu-btn\">SETTINGS</a>

                        <a href=\"" . ROOT_URL . "/logout.php\" class=\"navbar-menu-btn\">LOGOUT</a>
                    </div>");
        }
    }

    else {
        //Print menu with login button.
        printf(sprintf("<div class=\"navbar-menu\">
            <a href=\"%s/login.php\" class=\"navbar-menu-btn\">LOGIN</a>
        </div>", ROOT_URL));
        
        //%s is a symbol for a string, which is filled with the parameters given at the end.
        //I need to use printf() and sprintf() because sprintf() just returns a formatted string, it doesn't print it.
    }
    ?>
</section>