<?php
	function console( $data ) {
	    $output = $data;
	    if ( is_array( $output ) )
	        $output = implode( ',', $output);

	    echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
	}
?>
		<?php

		require("dbinfo.php");
		require("upload.php");
		date_default_timezone_set("Asia/Taipei");
		$date_time = date("Y-m-d H:i:s");

		$conn = new mysqli($host, $username, $password, $database);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		mysqli_query($conn,"SET NAMES ‘UTF8′");
		$filename=$_FILES['image']['name'];
	    $tmpname=$_FILES['image']['tmp_name'];
	    $filetype=$_FILES['image']['type'];
	    $filesize=$_FILES['image']['size'];    
	    $file=NULL;
	    if(isset($_FILES['image']['error'])){    
			if($_FILES['image']['error']==0){                                    
				$instr = fopen($tmpname,"rb" );
				$file = addslashes(fread($instr,filesize($tmpname)));        
			}
	    }
	    $url = NULL;
	    $result=NULL;
		if($file!=NULL)
		{
			$client_id="5a91d4506695f5e";
			$handle = fopen($tmpname, "r");
			$data = fread($handle, filesize($tmpname));
			$pvars = array('image' => base64_encode($data));
			$timeout = 30;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID '.$client_id));
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$out = curl_exec($curl);
			curl_close ($curl);
			$pms = json_decode($out,true);
			$url=$pms['data']['link'];
			/*if($url!=""){
				//console($url);
				//$result =  analyze_image($url);
			}
			else{
				//echo "<h2>There's a Problem</h2>";
				//echo $pms['data']['error'];  
			}
		*/
		}
		//檢查非法輸入
		$illegal = 0;
		$sql = "SELECT banned_word FROM `f74036124`.`IRP_bannedword_table`";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//echo $row["banned_word"];
				//echo "<br>";
				if(!strcmp($_POST['location'], $row["banned_word"]) || !strcmp($_POST['location'], "")){
					$illegal = 1;
					break;					
				}
				if(!strcmp($_POST['event'], $row["banned_word"]) || !strcmp($_POST['event'], "")){
					$illegal = 1;
					break;					
				}
				if(!strcmp($_POST['Lat'], "")){
					$illegal = 1;
					break;					
				}
				if(!strcmp($_POST['Lng'], "")){
					$illegal = 1;
					break;					
				}
			}
		}
		if($illegal == 1){
			echo '<script type="text/javascript">
		           alert("內容錯誤!\n1.請檢查是否空白<br>2.請勿使用不理性字眼");
		      	</script>';
		    echo '<script type="text/javascript">
		           window.location = "index_3.html"
		      	</script>';
		}
		//,'".$_POST['category']"
		//,`category`
		else{
			$sql = "INSERT INTO `f74036124`.`irp_report_table` (`ID`, `time`, `location`, `event`, `Lat`, `Lng`,`URL`, `turn_in`,`category`) VALUES ('', '".$date_time."', '".$_POST['location']."', '".$_POST['event']."', '".$_POST['Lat']."', '".$_POST['Lng']."', '".$url."' ,'0', '".$_POST['location']."');";
			if($conn->query($sql) === TRUE){
				$conn->close();
				echo '<script type="text/javascript">
		           alert("新增資料成功");
		      	</script>';
		 		echo '<script type="text/javascript">
		           window.location = "index_3.html"
		      	</script>';     	

				/*
				$sql = "SELECT ID, time, location, event, Lat, Lng, turn_in FROM `f74036124`.`IRP_report_table`";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						echo "".$row["ID"]."	".$row["time"]."	".$row["location"]."	".$row["event"]."	".$row["Lat"]."	".$row["Lng"]."	".$row["turn_in"]."<br>";
					}
				} else {
					echo "0 results";
				}
				*/
			}
			else{
				echo '<script type="text/javascript">
		           alert("error");
		      	</script>';
				$conn->close();
			}									
		}
		
?>