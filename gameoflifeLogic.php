<?php
	if(isset($_POST['x'])){
		updateDB();
	}
	if(isset($_POST['clear'])){
		setDBtoZero();
	}
	
	function newConnection(){	
		$server = 'localhost';      	// server name
		$username = 'root';				// username
		$password = 'the1';				// password
		$dbname = 'theAwesomeGame';		// DB Name

		$conn = mysqli_connect($server,$username,$password,$dbname);
		
		if (!$conn) {
			die('Could not connect: ' . mysqli_error($conn));
		}
		return $conn;
	}
	
	
	function updateDB() {
		$conn = newConnection();
		
		//mysqli_select_db($conn,"theAwesomeGame");				// "theAwesomeGame" is DB name	
		
		//$updateValue = ;
		$pointX = $_POST['x'];
		$pointY = $_POST['y'];
		
		$updateValue = 0;
		$selectsql = "SELECT x, y, value FROM dataPoints WHERE x=$pointX AND y=$pointY";
		$result = mysqli_query($conn, $selectsql);

		if (mysqli_num_rows($result) > 0) {
			// output data of each row
			while($row = mysqli_fetch_assoc($result)) {
				$oldVal = $row["value"];
			}
		}
		
		if($oldVal == 0){
			$updateValue = 1;
		}
		
		$sql = "UPDATE dataPoints SET value=$updateValue WHERE x=$pointX AND y=$pointY";
		
		if ($conn->query($sql) === TRUE) {
			//echo "Record updated successfully";
			$sql = "SELECT * FROM dataPoints";
			$myArray = array();
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$myArray[] = $row;
				}
				echo json_encode($myArray);
			}
			else{
				echo "Error pulling Db: " . $conn->error;
			}
			
		} else {
			echo "Error updating record: " . $conn->error;
		}
		
		mysqli_close($conn);
	}
	
	
	function setDBtoZero() {
		$conn = newConnection();
		
		$updateSQL = "";
		
		//mysqli_select_db($conn,"theAwesomeGame");				// "theAwesomeGame" is DB name
		for ($i = 0; $i < 33; $i++) {
			for ($j = 0; $j < 33; $j++) {
				$updateSQL .= "UPDATE dataPoints SET value = 0 WHERE x=$i AND y=$j;";
			}
		}
			
			if ($conn->multi_query($updateSQL) === TRUE) {
				echo "Success";
			}
				else{
					echo "Error pulling Db: " . $conn->error;
				}
			}
		mysqli_close($conn);
	}

?>