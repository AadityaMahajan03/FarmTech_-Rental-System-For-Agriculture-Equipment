<?php
class Database
{
	
	private $con;
	public function connect(){
		$this->con = new Mysqli("switchyard.proxy.rlwy.net", "root", "iiWvcKjlQKaDmOdkrHZnqLfDolwnHyQS", "railway", 12601); 
		return $this->con;
	}
}

?>
