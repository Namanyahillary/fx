<?php
require "connection.php" ;
register_shutdown_function('handleShutdown');
ini_set('max_execution_time', 120);
?>

<?php
	$json_result['Receipts']=array();
	while(1){
		$sql1=mysql_query("SELECT * FROM sold_receipts WHERE status = 0")or die(mysql_error());
		
		if(mysql_num_rows($sql1)){
			$ids="";
			$counter=0;
			while($row=mysql_fetch_assoc($sql1)){
				if($counter==0){
					$ids.="'".$row['id']."'";
				}else{
					$ids.=",'".$row['id']."'";
				}
				$counter++;
				$row['reciept_type']='sold_receipts';
				array_push($json_result['Receipts'],$row);
			}
			$sql2=mysql_query("UPDATE sold_receipts SET status=1 WHERE id IN ($ids)")or die(mysql_error());
			break;
		}
		
		$sql2=mysql_query("SELECT * FROM purchased_receipts WHERE status = 0")or die(mysql_error());
		
		if(mysql_num_rows($sql2)){
			$ids="";
			$counter=0;
			while($row=mysql_fetch_assoc($sql2)){
				if($counter==0){
					$ids.="'".$row['id']."'";
				}else{
					$ids.=",'".$row['id']."'";
				}
				$counter++;
				$row['reciept_type']='purchased_receipts';
				array_push($json_result['Receipts'],$row);
			}
			$sql2=mysql_query("UPDATE purchased_receipts SET status=1 WHERE id IN ($ids)")or die(mysql_error());
			break;
		}
	}
	
	//$json_result="{'name':'Sally Smith'}";
	//echo json_encode($json_result['Receipts'][0]);
	
	function handleShutdown(){
		$res = $GLOBALS['json_result'];
		echo json_encode($res);
	}
?>