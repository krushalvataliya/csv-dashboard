<?php 

class Model_core_Adapter{
	public $serverName="localhost";
	public $userName="root";
	public $password="";
	public $databaseName ="my-commerce";
  	public $connect = null;

	public function connect(){
      if($this->connect == null)
      {
		$this->connect = mysqli_connect($this->serverName, $this->userName, $this->password, $this->databaseName);
      }
      return $this->connect;
	  }  

   public function fetchAll($query){
   	$result =$this->connect()->query($query);
   	if(!$result){
   		return false;
   	}
   		return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function fetchRow($query){
   	$result =$this->connect()->query($query);
   	if(!$result){
   		return false;
   	}
   		return $result->fetch_assoc();
   }
   
   public function insert($query){
   	$connect =$this->connect();
      $result=$connect->query($query);
   	if(!$result){
   		return false;
   	}
   		return $connect->insert_id;
   }

   public function update($query){
   	$result =$this->connect()->query($query);
   	if(!$result){
   		return false;
   	}
   		return true;
   }

    function delete($query){
   	$result =$this->connect()->query($query);
   	if(!$result){
   		return false;
   	}
   		return true;
   }
   function query($query){
      $result =$this->connect()->query($query);
      if(!$result){
         return false;
      }
         return $result->fetch_assoc();
   }

   public function getdatabaseName()
   {
      return $this->databaseName;
   }

   public function setdatabaseName($databaseName)
   {
      $this->databaseName = $databaseName;
      return $this;
   }

}
?> 	