<?php
//Get homework due today...
$hwkSql = "SELECT * FROM Homework WHERE
	ID=(SELECT HomeworkID FROM HomeworkEntries WHERE StudentID=:sid AND Status=\"I\")
    AND DueDate=CURDATE();";

$hwkParams = array('sid' => $_SESSION['userData']['ID']);

$hwkToday = $sql->query($hwkSql, $hwkParams);

//Get most recent unread messages (LIMIT 3)
$msgSql = "SELECT * FROM Messages
			WHERE RecipientID=:rid AND RecipientType=:rtype AND IsRead=0
			ORDER BY SentDate DESC LIMIT 3";

//Set parameters for binding...
$msgParams = array(
				'rid' => $_SESSION['userData']['ID'],
				'rtype' => $_SESSION['userData']['type']
			);

$recentMsgs = $sql->query($msgSql, $msgParams);

//Set root URL as variable so it can be used in strings.
$rootUrl = ROOT_URL;
?>

<section id="index-content-columns" style="padding-top: 100px;">
	<div class="index-content-column">
		<h4>Homework Due Today</h4>
		
		<ul>
			<?php
            //Iterate through all tasks due today.
            foreach($hwkToday as $hwk){
                //Extract data from the given array, relevant to the current task.
                $taskName = $hwk['Title'];
                $taskId = $hwk['ID'];
                
                
                //Get data about teacher setting the homework.
                $teacherData = $sql->query("SELECT * FROM Teachers WHERE ID=:id LIMIT 1",
                                            array('id' => $hwk['TeacherID']))[0];
                
                //Use format name function to get the teacher's prefix.
                $teacherName = milkshake\formatTeacherName($teacherData);
                
                //Print out the retrieved data in a list, providing a link to the relevant homework page,
                //by using GET variables.
                printf("<li><a href=\"{$rootUrl}/homework.php?id={$taskId}\" class=\"url-no-change\">
                            <strong>{$taskName}</strong> for <strong>{$teacherName}</strong>.
                            </a></li>");
            }
			?>
		</ul>
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
        <h4>Unread Messages</h4>
        
        <!-- Using an unordered list to present messages -->
        <ul>
            <?php
            //Iterate through messages given by SQL query...
            foreach ($recentMsgs as $msg){
                $senderData = milkshake\user\getData($msg['SenderID'], $msg['SenderType'], IDTYPE_ID);
                
                $subject = $msg['Subject'];
                $id = $msg['ID'];
                
                if ($msg['SenderType'] == TYPE_TEACHER){
                    $senderName = milkshake\formatTeacherName($senderData);
                }
                
                else {
                    $senderName = $senderData['FirstName'] . " " . $senderData['LastName'];
                }
                
                printf("<li><a href=\"{$rootUrl}/messages.php?id={$id}\" class=\"url-no-change\">
                        <strong>{$subject}</strong> from <strong>{$senderName}</strong></a></li>");
            }
            ?>
        </ul>
    </div>
</section>