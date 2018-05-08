<?php
$classesSql = "SELECT * FROM Classes WHERE TeacherID=:tid";

$classesParams = array('tid' => $_SESSION['userData']['ID']);

$classes = $sql->query($classesSql, $classesParams);

//Set root URL as variable so it can be used in strings.
$rootUrl = ROOT_URL;
?>

<section id="index-content-columns" style="padding-top: 100px;">
    <div class="index-content-column">
        <h4>Incomplete Homework</h4>
        
        <?php
		foreach ($classes as $class){
			//Iterate through classes...
			
			//Get class name as a string
			//and print as section header...
			$className = $class['Name'];
			printf("<div class=\"home-list-seperator\">
			<h6 style=\"margin: 0\">{$className}</h6>");
			//Set class for list seperator and remove margin from header.
			
			//Get all homework for this class...
			$hEntrySql = "SELECT * FROM HomeworkEntries WHERE
				StudentID=(SELECT StudentID FROM ClassEntries WHERE ClassID=:cid)
				AND Status=\"I\"";
			
			$hEntryParams = array('cid' => $class['ClassID']);
			
			$hwkEntries = $sql->query($hEntrySql, $hEntryParams);
			
			printf("<ul style=\"margin-top: 0\">");
			//Remove any margin at top from unordered list.
			
			foreach($hwkEntries as $entry){
				$studentSql = "SELECT FirstName,LastName FROM Students WHERE ID=:id LIMIT 1";
				//Only select FirstName and LastName to conserve memory.
				
				$studentParams = array('id' => $entry['StudentID']);
				
				$studentData = $sql->query($studentSql, $studentParams)[0];
				
				$sName = $studentData['FirstName'] . " " . $studentData['LastName'];
				
				$taskSql = "SELECT Title FROM Homework WHERE ID=:hid LIMIT 1";
				//Again, select only the title and limit to 1 result to conserve memory.
				
				$taskParams = array('hid' => $entry['HomeworkID']);
				
				$taskName = $sql->query($taskSql, $taskParams)[0]['Title'];
				
				printf("<li>Task Name: <strong>{$taskName}</strong><br />
						Student Name: <strong>{$sName}</strong></li>");
			}
			
			printf("</ul><hr /></div>");
		}
		?>
    </div>
	
    <div class="index-content-column">
        <h3>User Info</h2>
        <ul style="list-style: none;">
            <li>Username: <?php echo($_SESSION['userData']['Username']); ?></li>
            <li>E-Mail Address: <?php echo($_SESSION['userData']['Email']); ?></li>
            <li>UID: <?php echo($_SESSION['userData']['LID']); ?></li>
        </ul>
    </div>
	
    <div class="index-content-column">
        <h4>Class Homework Due Today</h4>
        
        <?php
        foreach ($classes as $class){
            //Get class name as a string.
            $className = $class['Name'];
            
            //Get relevant hwk tasks...
            $hwkSql = "SELECT * FROM Homework
                WHERE ID=(SELECT HomeworkID FROM HomeworkForClasses WHERE ClassID=:cid)
                AND DueDate=CURDATE()";
            
            $hwkParams = array('cid' => $class['ClassID']);
            
            $hwkTasks = $sql->query($hwkSql, $hwkParams);
            
            //Divider to seperate class lists.
            //Also header for this list with no margin.
            //Also start an unordered list...
            printf("<div class=\"home-list-seperator\">
            <h6 style=\"margin: 0\">{$className}</h6>
            <ul style=\"margin-top: 0;\">");
            
            foreach ($hwkTasks as $hwk){
                $hwkName = $hwk['Title'];
                printf("<li>{$hwkName}</li>");
            }
            
            printf("</ul></div>");
        }
        ?>
    </div>
</section>