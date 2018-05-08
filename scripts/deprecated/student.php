<?php
namespace milkshake;

//Ensure the User class has been inported.
include_once "user.php";

class StudentUser extends User {
	public $yearGroup;
	public $form;
	
	/**
	 * Constructor of StudentUser class, can either create or get a user.
	 * 
	 * @param string $username (optional) User's username or ID.
	 * @param string $type (optional) Type of data given for $username.
	 */
	public function __construct($username=null, $type=TYPE_USERNAME){
		
		if (!empty($username) && $username !== null){
			$this->getUser($username, $type);
		}
	}
	
	/**
	 * Function to create a student user and add it to the database.
	 * 
	 * @param string $username Student's display / user name.
	 * @param string $email Student's e-mail address.
	 * @param string $password Student's PLAIN TEXT password.
	 * @param string $fname Student's forename.
	 * @param string $mname Student's middle name(s)
	 * @param string $sname Student's surname.
	 * @param string $gender Student's gender.
	 * @param string $form Student's form group.
	 * @param string $yearGroup Student's year group.
	 */
	public function createUser($username, $email, $password,
								$fname, $mname, $sname, $gender,
								$form, $yearGroup){
		$user = new User($username, $email, $password, $fname, $mname, $sname, $gender);
		
		$this->form = $form;
		$this->yearGroup = $yearGroup;
	}
	
	/**
	 * Function to retreive student details from database.
	 * 
	 * @param string $username User's identifying attribute, by default it is username.
	 * @param int $type Attribute type (eg; username, e-mail address or UID)
	 */
	 public function getUser($username, $type=TYPE_USERNAME){
	 	$sql = new SQL();
		
		switch ($type){
			case TYPE_USERNAME:
			default:
				$columnName = "Username";
			
			case TYPE_EMAIL:
				$columnName = "Email";
			
			case TYPE_ID:
				$columnName = "StudentID";
		}
		
		$sqlTxt = "SELECT * FROM Students WHERE :column=:value LIMIT 1";
		$sqlParams = array(
			'column' => $columnName,
			'value' => $username
		);
		
		$results = $sql->query($sqlTxt, $sqlParams);
		
		$this->id = $results['ID'];
		$this->username = $results['Username'];
		$this->email = $results['Email'];
		
		$this->fname = $results['FirstName'];
		$this->mname = $results['MiddleName'];
		$this->sname = $results['LastName'];
		
		$this->yearGroup = $results['YearGrp'];
		$this->form = $results['Form'];
	 }
}