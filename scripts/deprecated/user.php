<?php
namespace milkshake;

//Define user constants
define("TYPE_USERNAME", 0, TRUE);
define("TYPE_EMAIL", 1, TRUE);
define("TYPE_ID", 2, TRUE);

define("USER_GET", 0, TRUE);
define("USER_MAKE", 1, TRUE);

class User {
	public $id;
	public $username;
	public $email;
	public $password;
	
	public $fname;
	public $mname;
	public $sname;
	
	public $gender;
	public $joined;
	
	/**
	 * Creates a new user ready to be added to a
	 * relevant table.
	 * 
	 * This should only be used by an extension of this class.
	 *
	 * @param int $id ID of the user in its respective table.
	 * @param string $username User's username.
	 * @param string $email User's e-mail address.
	 * @param string $password User's PLAIN TEXT password, will be hashed.
	 * @param string $fname User's forename.
	 * @param string $mname User's middle name(s).
	 * @param string $sname User's surname.
	 * @param string $gender User's gender.
	 */
	protected function __construct($id, $username, $email, $password,
						$fname, $mname, $sname, $gender, $joined=null){
		$this->id = id;
		$this->username = $username;
		$this->email = $email;
		$this->fname = $fname;
		$this->mname = $mname;
		$this->sname = $sname;
		$this->gender = $gender;
		
		//Get date and time in UTC for the joined variable.
		if ($joined !== null){
			$this->joined = $joined;
		}
		
		else {
			$this->joined = time();
		}
		
		//Hash password and get meta
		$password = new Password($password);
		
		$this->password = $password;
	}
}