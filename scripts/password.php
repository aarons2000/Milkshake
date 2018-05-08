<?php
namespace milkshake;
define("HASH_ALGO1", 0, TRUE);

class Password {
	public $plainText = "";
	public $password;
	
	public $salt1;
	public $salt2;
	public $iterations;
	
	public $algo = HASH_ALGO1;
	
	public function __construct($plainText,
								$salt1=null, $salt2=null, $iterations=null,
								$algo=HASH_ALGO1){
		
		if ($salt1 == null && $salt2 == null && $iterations == null){
			$this->plainText = $plainText;
			$this->createPassword();
		}
		
		else {
			$this->plainText = $plainText;
			$this->salt1 = $salt1;
			$this->salt2 = $salt2;
			$this->iterations = $iterations;
			$this->algo = $algo;
			
			$this->createPassword();
		}
	}
	
	public static function hashPassword1($plainText, $salt1, $salt2, $iterations){		
		for ($i=0; $i<$iterations; $i++){
			$plainText = $salt1 . $plainText . $salt2;
			
			$plainText = hash('sha3-512', $plainText);
		}
		
		$passwd = password_hash($plainText, PASSWORD_BCRYPT, array('cost' => 12,
																	'salt' => $salt1));
		
		return array(
			'password' => $passwd,
			'salt1' => $salt1,
			'salt2' => $salt2,
			'iterations' => $iterations
		);
	}
	
	public function createPassword(){
		$plainText = $plainText ?: $this->plainText;
		
		$salt1 = $this->salt1 ?: randomString(floor(mt_rand(4096, 20480)), STRING_HEX);
		$salt2 = $this->salt2 ?: randomString(floor(mt_rand(2048, 10240)), STRING_HEX);
		
		$iterations = $this->iterations ?: (int) mt_rand(500, 1000);
		
		$algo = $this->algo;
		
		$passwd = array();
		
		switch ($algo){
			case HASH_ALGO1:
			default:
				$passwd = Password::hashPassword1($plainText, $salt1, $salt2, $iterations);
		}
		
		$this->password = $passwd['password'];
		$this->salt1 = $passwd['salt1'];
		$this->salt2 = $passwd['salt2'];
		$this->iterations = $passwd['iterations'];
		
		return $passwd;
	}
	
	public function matchPassword($hash){
		$truePasswd = $this->password;
		
		if ($truePasswd == $hash){
			return true;
		}
		
		return false;
	}
}