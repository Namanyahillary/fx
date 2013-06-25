<?php
require "connection.php" ;
register_shutdown_function('handleShutdown');
ini_set('max_execution_time', 60);
?>

<?php
	$result='Error:Error occured';
	if(isset($_REQUEST['company_id']) && !empty($_REQUEST['company_id'])){
		if(isset($_REQUEST['receipt_type'])){
			$receipt_type=(int)$_REQUEST['receipt_type'];//0-Sold Receipt, 1-Purchased Receipt
		
			$sql1	=	mysql_query(sprintf("SELECT * FROM receipt_tracks WHERE id = '%d' limit 1",
						mysql_real_escape_string($_REQUEST['company_id'])))
						or die(mysql_error());
			
			if(mysql_num_rows($sql1)){
				while($row=mysql_fetch_assoc($sql1)){
					$receipt_number=0;
					if(!$receipt_type){//if(Sold Receipt){
						$y=(String)$row['year'];
						$receipt_number=((String)(((int)$row['my_count_sold_receipts'])+1)).''.((String)0).''.(substr($y, 1,strlen($y)-1)).''.((String)$row['id']);
					}else{
						$y=(String)$row['year'];
						$receipt_number=((String)(((int)$row['my_count_purchased_receipts'])+1)).''.((String)1).''.(substr($y, 1,strlen($y)-1)).''.((String)$row['id']);
					}
					
					$result=$receipt_number;
				}
				
				if(!$receipt_type){//if(Sold Receipt)
					mysql_query(sprintf("UPDATE receipt_tracks SET my_count_sold_receipts=my_count_sold_receipts+1 WHERE id = '%d' limit 1",
							mysql_real_escape_string($_REQUEST['company_id'])))
							or die(mysql_error());
				}else{
					mysql_query(sprintf("UPDATE receipt_tracks SET my_count_purchased_receipts=my_count_purchased_receipts+1 WHERE id = '%d' limit 1",
							mysql_real_escape_string($_REQUEST['company_id'])))
							or die(mysql_error());
				}
				
			}else{
				$result='Error:Invalid Company ID provided';
			}
		}else{
			$result='Error:Unset receipt type';
		}
	}else{
		$result='Error:Company ID not found';
	}
	
	//$json_result="{'name':'Sally Smith'}";
	//echo json_encode($json_result['Receipts'][0]);
	
	function handleShutdown(){
		echo $GLOBALS['result'];
	}
?>