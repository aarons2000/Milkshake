<?php
//Set namespace.
namespace milkshake;

class SQL{
	//Define class attributes.
	private $connection;
	
	private $hostname;
	private $port = 3306;
	
	private $username;
	private $password;
	
	private $charSet = "utf8";
	private $dbName = "Milkshake";
	
    /**
     * Builds the SQL class.
     * 
     * @param string $dbName Name of database, if database is not that defined in config.
     */
    public function __construct($dbName = null){
        //Constants should have been defined before inclusion.

        //Assign the parameters in the config files to the class attributes.
        $this->hostname = DATABASE['hostname'];
        
        //Take the first not-null value, since port has a default value.
        $this->port = DATABASE['port'] ?? $this->port;
        
        $this->username = DATABASE['username'];
        $this->password = DATABASE['password'];
        
        $this->charSet = DATABASE['charSet'] ?? $this->charSet;
        $this->dbName = DATABASE['dbName'] ?? $this->dbName;
        
        //Setup new connection with the attributes defines above.
        $this->connection = new \PDO("mysql:host={$this->hostname};
        port={$this->port};charset={$this->charSet};dbname={$this->dbName}",
                                   $this->username, $this->password);
        
        /*
         * Make PDO throw exceptions when it encounters an error, instead of
         * throwing warnings which are less easy to spot than exceptions.
         * 
         * PDO must be denoted with the root namespace character, since it is
         * a part of the milkshake namespace.
         */
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        
        /*
        * Emulate preperation of SQL statements, ensuring statements sent to the driver are always 
        * prepared.
        */
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        
        //Make PDO fetch associative arrays with values in, making it easy to get values from the server.
        $this->connection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }
	
    /**
     * Converts a string of an SQL statement to a PDO statement.
     * Also known as the bind function.
     *
     * @param string $sqlText SQL statement as a string.
     * @param array $parameters Parameters as an associative array.
     *
     * @return PDOStatement PDO Statement with bound parameters.
    */
    private function convertToStatement($sqlText, $parameters = null){
        $stmt = $this->connection->prepare($sqlText);
        
        if ($parameters !== null){
            //Using a while loop instead of a for loop.
            while (list($key, $value) = each($parameters)){
                $stmt->bindValue(":{$key}", $value);
            }
        }
        
        return $stmt;
    }

	/**
	 * Sends a query to the MySQL server with the given statement and parameters.
	 *
	 * @param string $sqlText SQL statement as a string.
	 * @param array $parameters Parameters as an associative array.
	 *
	 * @return array Associative array of values taken from the SQL statement.
	*/
	public function query($sqlText, $parameters = null){
		//Call to function to prepare a statement.
		$stmt = $this->convertToStatement($sqlText, $parameters);
		
		//Execute the statement with bound parameters.
		$stmt->execute();
		
		return $stmt->fetchAll();
	}
	
	/**
	 * Sends a query to the MySQL server, to insert a row to a table,
	 * with the given statement and parameters.
	 *
	 * @param string $sqlText SQL statement as a string.
	 * @param array $parameters Parameters as an associative array.
	*/
	public function insert($sqlText, $parameters = null){
		//Call to function to prepare a statement.
		$stmt = $this->convertToStatement($sqlText, $parameters);
		
		//Execute the statement with bound parameters.
		$stmt->execute();
	}
	
    /**
     * Gets last primary key that was inserted with this object.
     * 
     * @return int Primary key.
     */
     public function lastID(){
        return $this->connection->lastInsertId();
     }
}