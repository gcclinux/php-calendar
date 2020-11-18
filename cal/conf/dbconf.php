<?php
ini_set('display_errors','true');
error_reporting(E_ALL);

class Database
{
	public $conn;
	public function dbConnection()
	{
		$config = include('config.php');
	    	$this->conn = null;
	        try
		{
			$this->conn = new PDO($config['dbtype'].":host=".$config['dbhost'].";port=".$config['dbport'].";dbname=".$config['dbname'],$config['dbuser'],$config['dbpass']);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $exception) {
			       echo "Connection error: " . $exception->getMessage();
		}
	        return $this->conn;
	}
}
?>
