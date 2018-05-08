<?php
//Set root URL as variable so it can be used in strings.
$rootUrl = ROOT_URL;

$hwkSql = "SELECT * FROM HomeworkEntries WHERE StudentID=
(SELECT StudentID FROM ParentRelationships WHERE ParentID=:pid)
LIMIT 5";

$hwkParams = array('pid' => $_SESSION['userData']['ID']);

$incompleteHwkEntries = $sql->query($hwkSql, $hwkParams);
?>

<section id="index-content-columns" style="padding-top: 100px;">
	<div class="index-content-column">
		<h4>Children Overview</h4>
		
		<!-- Again, using an unordered list to present data -->
		<ul>
			<?php
			foreach ($relatedStudents as $stu){
				/*
				 * Iterate through students related to the parent,
				 * using the variable $relatedStudents defined
				 * in the navbar script.
				 */
				 
				 //Set variable for student's ID
				 $id = $stu['ID'];
				 
				 //Set vars for student's names
				 $fname = $stu['FirstName'];
				 $sname = $stu['LastName'];
				 
				 printf("<li><a href=\"{$rootUrl}/viewchild.php/?id={$id}\"
				 				class=\"url-no-change\">
							{$fname} {$sname}</a></li>");
			}
			?>
		</ul>
	</div>
	
	<div class="index-content-column">
		<h3>User Info</h2>
		<ul style="list-style: none;">
			<!-- Identical to the students' version, pull each element from the
				 session array and display it in an unordered list. -->
			<li>Username: <?php echo($_SESSION['userData']['Username']); ?></li>
			<li>E-Mail Address: <?php echo($_SESSION['userData']['Email']); ?></li>
			<li>UID: <?php echo($_SESSION['userData']['LID']); ?></li>
		</ul>
	</div>
	
    <div class="index-content-column">
        <h4>Children's Incomplete Homework</h4>
        
        <ul>
            <?php
            foreach($incompleteHwkEntries as $hwkEnt){
                $taskData = $sql->query(
                    "SELECT * FROM Homework WHERE ID=:hid LIMIT 1",
                    array('hid' => $hwkEnt['HomeworkID'])
                )[0];
                //Select ONE homework task from the Homework table using the ID
                //from the homework entries result.
                
                $studentData = $sql->query(
                    "SELECT * FROM Students WHERE ID=:sid LIMIT 1",
                    array('sid' => $hwkEnt['StudentID'])
                )[0];
                //Same as above but for students.
                
                $studentName = $studentData['FirstName'];
                //Only use first name since most students will have the same surname
                //as their parents.
                
                $taskName = $taskData['Title'];
                //Get the task's name / title.
				
                //A better way to do this would be to use 2 variables...
                $dueDateObj = date_create($taskData['DueDate']);
                //Create DateTime object from due date stored in DB.
                //Use date_create() to detect format automatically.

                $dueDate = $dueDateObj->format("Y/m/d");
                //Then format it in dd/mm/yyyy form for display.

                //Changed today's date to match what I did above...
                $dateTodayObj = new DateTime();
                //Get today's date.
                //This method is OK for today's date since it is
                //being generated, not imported from somewhere else.

                $dateToday = $dateTodayObj->format("Y/m/d");
                //Format today's date.
                
                if ($dueDate == $dateToday){
                    $dueDate = "Today";
                    //Set due date to "Today" if the hwk is due today.
                }
                
                /*
                 * The array of tasks could be sorted by date here, however a lot
                 * of processing has already been done in this loop, so it would
                 * be inefficient to do any more, also it would use more memory
                 * since ALL tasks would need to be fetched from the database
                 * rather than just 5 by using LIMIT 5.
                 */
                
                printf("<li><strong>{$studentName}</strong> - 
                        <strong>{$taskName}</strong>, <br />
                        Due: <strong>{$dueDate}</strong>.<br />");
                //Show the information about the parent's children's homework,
                //making all important information bold.
            }
            ?>
        </ul>
    </div>
</section>