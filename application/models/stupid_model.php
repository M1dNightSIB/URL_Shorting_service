<?php

class Model_Main
{
	private $error = -1;
	private $hostname = 'localhost';
	private $name = 'users';
	private $password = 'userpass';
	private $databaseName = 'Short_URLs';
	private $table = 'MAIN_URL';
	private $fullUrlField = 'full_url';
	private $shortUrlField = 'short_url';
	private $mySqli = NULL;

	public function __construct()
	{
		$this->mySqli = new mysqli($this->hostname, $this->name,
									$this->password, $this->databaseName);
		if($this->mySqli->connect_errno){
			echo "<p>DataBase error</p><br>
			<p>$this->mySqli->connect_error</p>";
	  		exit();
		}

		$this->createEnvironment();
	}
	
	public function insertUrl($url, $key)// insert into DB URL and his KEY
	{
			$query = "INSERT INTO $this->table 
					($this->fullUrlField, $this->shortUrlField) VALUES (?, ?)";
			
			if ($stmt = $this->mySqli->prepare($query)) {
                $stmt->bind_param('ss', $url, $key);
				$stmt->execute();
				$stmt->close();
                return $key;
            } else {
				$error = mysqli_error($this->mySqli);
				$stmt->close();
                return $error;
            }
	}

	public function getUrl($key)// get URL on his key from DB 
	{	
		$query = "SELECT $this->fullUrlField
					FROM $this->table WHERE $this->shortUrlField = ?";
		
		if($stmt = $this->mySqli->prepare($query)){
			$stmt->bind_param('s', $key);
			$stmt->execute();
			$stmt->bind_result($fullUrl);
			$stmt->store_result();
			$stmt->fetch();
			if($fullUrl == false){
				$stmt->close();
				return false;
			}else{
				$stmt->close();
				return $fullUrl;
			}
		}else{
			$error = mysqli_error($this->mySqli);
			$stmt->close();
            return $error;
		}
	}

	public function getKey($url)
	{
		$query = "SELECT $this->shortUrlField 
					FROM $this->table WHERE $this->fullUrlField = ?";
		
		if($stmt = $this->mySqli->prepare($query)){
			$stmt->bind_param('s', $url);
			$stmt->execute();
			$stmt->bind_result($shortUrl);
			$stmt->store_result();
			$stmt->fetch();
			if($shortUrl == false){
				$stmt->close();
				return false;
			}else{
				$stmt->close();
				return $shortUrl;
			}
		}else{
			$error = mysqli_error($this->mySqli);
			$stmt->close();
            return $error;
		}
	}

	private function createEnvironment()
	{
		$query = "CREATE TABLE IF NOT EXISTS $this->table(
			id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
          	$this->fullUrlField varchar(1000) NOT NULL,
          	$this->shortUrlField varchar(45) NOT NULL 
		)";

		if(!$this->mySqli->query($query)){
			return $error;
		}
	}

	public function __destruct()
	{
	}
}
