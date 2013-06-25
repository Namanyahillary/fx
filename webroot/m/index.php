<?php
require "connection.php" ;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Forex Buerau</title>
	<script type="text/javascript" src="js/jquery1.7.2mini.js"></script>
	<script type="text/javascript" src="js/action_script.js"></script>	
</head>

<body>
	<div id="new-receipts">
		<br/>
		<center>
			<?php $name='';$location='';$q = mysql_query("select name,location from foxes limit 1")or die(mysql_error());if(mysql_num_rows($q)){while($row = mysql_fetch_assoc($q)){$name = $row['name'];$location = $row['location'];}}?>
			<b style="font-size:23px"><?php echo $name;?></b><br/>
			<?php echo $location;?>
			<table width="350px" style="margin-left:180px">
				<tr>
					<td>
						<b class="receipt_type">Sales receipt</b><br/>
					</td>
					<td>
						<div style="float:right">
						<b>Receipt No: <span class="receipt_number"></span></b>
						</div>
					</td>
				</tr>
			</table>
		</center><br/>
		<style>
			<!--
				.summary td{
					border:1px solid #999;
				}
				.summary .summary-middle td{
					height:30px;
				}
			-->
		</style>
		<center>
			<table width="90%" class="summary" style="border-spacing:0;" >
				<tr >
					<td ><b>Currency</b></td>
					<td class="receipt_instrument"><b>Instrument</b></td>
					<td><b>Amount</b></td>
					<td><b>Rate</b></td>
					<td><b>Amount in UShs.</b></td>
				</tr>
				<tr class="summary-middle">
					<td class="receipt_currency"></td>
					<td class="receipt_instrument receipt_instrument_data"></td>
					<td class="receipt_amount"></td>
					<td class="receipt_rate"></td>
					<td class="receipt_amount_ugx"></td>
				</tr>
			</table>
		</center><br/>
		
		<b>A.&nbsp;&nbsp;<u class="purpose-title">Purpose of purchase</u></b><br/>
		<span class="receipt_purpose"></span><br/><br/>
		
		<div >
			<b><u>Particulars of Buyer:</u></b><br/>To be completed for transactions of US$5,000= and above or its equivalent
			<br/><br/>
			<table width="100%" >
				<tr style="height:30px">
					<td width="25%" style="font-weight:bold">Name:</td><td ><span class="receipt_customer_name"></span></td> 
					<td width="30%" style="font-weight:bold">Address</td> <td ><span class="receipt_customer_address"></span></td>
				</tr>
				<tr style="height:30px">
					<td style="font-weight:bold">Nationality:</td><td ><span class="receipt_customer_nationality"></span></td> 
					<td style="font-weight:bold">Passport No/ID No.</td> <td ><span class="receipt_customer_passport_number"></span></td>
				</tr>
				<tr style="height:30px">
					<td style="font-weight:bold">Customer signature:</td>  <td ></td> 
					<td style="font-weight:bold">Dealer's signature &amp; stamp</td> <td ></td>
				</tr>
				<tr >
					<td colspan="4"><span style="float:right;font-weight:bold;font-size:80%;">Approved by Bank of Uganda</span></td>
				</tr>
				
			</table>
		</div>
		<br/><br/>
		
	</div>
</body>
</html>