<?php
class Database{
	private $serverName;
	private $userName;
	private $password;
	private $database;

	protected function connect(){
		$this->serverName = "localhost";
		$this->userName = "root";
		$this->password = "";
		$this->database = "crud_opp";

		//Create Connection
		$con = new mysqli($this->serverName,$this->userName,$this->password,$this->database);

		//Check Connection
		if($con->connect_error){
			die("Error: ".$con->connect_error);
		}else{
			return $con;
		}
	}
}

class Operation extends Database{
	//Create
	public function insertData($table,$dataArr){

		foreach ($dataArr as $key => $value) {
			$fieldArr[] = $key;
			$valueArr[] = $value;
		}
		$field = implode(',', $fieldArr);
		$value = implode("','", $valueArr);
		$value = "'".$value."'";
		// die($value);
		$sql = "INSERT INTO $table ($field) VALUES($value)";
		
		// die($sql);
		try{
			if($this->connect()->query($sql) === TRUE){
				return "Data Inserted Successfully!";
			}
			$this->connect()->close();
		}catch(Exception $e){
			echo "Data Not Inserted! $e";
		}
		
	}

	//Read
	public function selectData($table,$field='*',$condition=[],$order_by_field='',$order_by='DESC',$limit=5,$offset=0){
		$sql = "SELECT $field FROM $table ";
		if(!empty($condition)){
			$sql.="WHERE ";
			$i=0;
			foreach ($condition as $key => $value) {
				if($i==1){
					$sql.="AND ";
				}
				$sql.="$key='$value' ";
				$i=1;
			}
		}

		if($order_by_field!==''){
			$sql.="ORDER BY $order_by_field ";
			if ($order_by!=='') {
				$sql.="$order_by ";
			}else{
				$sql.="DESC ";
			}
		}
		

		if ($limit!=5) {
			$sql.="LIMIT $limit ";
		}else{
			$sql.="LIMIT $limit ";
		}

		if ($offset!=5) {
			$sql.="OFFSET $offset";
		}else{
			$sql.="OFFSET $offset";
		}

		// die($sql);

		$result = $this->connect()->query($sql);
		if($result->num_rows>0){
			$arr = array();
			while($row = $result->fetch_assoc()){
				$arr[] = $row;
			}
			return $arr;
		}else{
			return 0;
		}
	}

	//Update
	public function updateData($table,$id,$condition){
		$sql = "UPDATE $table SET ";
		if(!empty($condition)){
			$i=0;
			foreach ($condition as $key => $value) {
				if($i==1){
					$sql.=",";
				}
				$sql.="$key='$value' ";
				$i=1;
			}
		}
		$sql.="WHERE id=$id";
		// die($sql);
		if($this->connect()->query($sql) === TRUE){
			return "Data Successfully Update";
		}else{
			echo "Error: ".$this->connect()->error;
		}
		$this->connect()->close();
	}

	//Delete
	public function deleteData($table, $id){
		$sql = "DELETE FROM $table WHERE id=$id";
		if($this->connect()->query($sql) === TRUE){
			return "Data Successfully Deleted!";
		}else{
			echo "Error: ".$this->connect()->error;
		}
	}

	//get safe string
	public function get_safe_str($str){
		if($str!=''){
			return mysqli_real_escape_string($this->connect(),$str);
		}
	}
}


?>