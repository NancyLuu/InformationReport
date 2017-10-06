<?php
	require("dbinfo.php");
	$ID = $_GET["ID"];
	$turn_in = $_GET["turn_in"];
	$turn_in = $turn_in+1;

	$conn = new mysqli($host, $username, $password, $database);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	mysqli_query($conn,"SET NAMES ‘UTF8′");

	$illegal = 0;
	if($turn_in<=5){
		$sql = "UPDATE  `f74036124`.`irp_report_table` SET  `turn_in` =  '".$turn_in."' WHERE  `irp_report_table`.`ID` ='".$ID."';";
	}
	else{
		$sql = "DELETE  FROM `f74036124`.`irp_report_table` WHERE  `irp_report_table`.`ID` ='".$ID."'";
	}
	$conn->query($sql);
	$conn->close();
	echo '<script type="text/javascript">
	           alert("檢舉成功");
	      	</script>';
			echo '<script type="text/javascript">
	           window.location = "index_3.html"
	      	</script>';
?>