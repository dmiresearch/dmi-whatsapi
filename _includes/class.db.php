<?php

	// Class database connection
	
	class Db
	{
		var $DBNAME;
		var $DBUSER;
		var $DBHOST;
		var $DBPASS;
		var $dbConn;
		
		function __construct()
		{
			$this->DBNAME = "msgapp";
			$this->DBUSER = "root";
			$this->DBHOST = "localhost";
			$this->DBPASS = "";
		}
		function setDBCon()
		{
		
			$con = new mysqli($this->DBHOST, $this->DBUSER, $this->DBPASS, $this->DBNAME);
			if($con->connect_errno>0)
			{
				echo "Unable to connect to MySQL". $con->connect_error;
				exit;
			}
			else
			{
				echo "Conn ok";
				$this->dbConn = $con; 
			}
		}
		function getDBCon()
		{
			return $this->dbConn;
		}
		
	}
	$test = new Db();
	//echo "Unable to connect to MySQL";
	$test->setDBCon();
	
?>