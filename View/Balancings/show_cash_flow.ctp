<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div style="float:right;margin-right:2.3%;">	
	<span class="btn btn-small" onclick="do_print();"><i class="icon icon-print"></i> Print</span>
</div>
<?php
//pr($result);
?>
<div class="printable">
<style>
<!--
	.my_data table tr td{
		border:1px solid #eee;
	}
-->
</style>
	<div class="my_data">
		<table class="well">
			<tr>
				<td style="min-width:60px;"><b>Date</b></td>
				<?php $item_totals=array('Items'=>array());?>
				<?php foreach($items as $item): ?>
					<?php array_push($item_totals['Items'],0);?>
					<td><b><?php echo $item['Item']['name']; ?></b></td>
				<?php endforeach; ?>
				<td><b>Gross Profit<?php array_push($item_totals['Items'],0);?></b></td>
				<td><b>Total Expenses<?php array_push($item_totals['Items'],0);?></b></td>
				<td><b>Total Net Profit<?php array_push($item_totals['Items'],0);?></b></td>
			</tr>
			<?php foreach($result['Dates'] as $cfDate): ?>
				
				<tr>
					<td><?php echo $cfDate; ?></td>
					
					<?php $item_count=0; foreach($result['CashFlow'][''.$cfDate]['items'] as $item): ?>
						<td>
							<?php 
								$item_totals['Items'][$item_count]+=$item;
								echo $item; 
							?>
						</td>
					<?php $item_count++; endforeach; ?>
					
					<td>
						<?php 
							$item_totals['Items'][count($item_totals['Items'])-3]+=$result['CashFlow'][''.$cfDate]['others'][0];
							echo $result['CashFlow'][''.$cfDate]['others'][0]; 
						?>
					</td>
					<td>
						<?php 
							$item_totals['Items'][count($item_totals['Items'])-2]+=$result['CashFlow'][''.$cfDate]['others'][1];
							echo $result['CashFlow'][''.$cfDate]['others'][1]; 
						?>
					</td>
					<td>
						<?php $total_net_profit=($result['CashFlow'][''.$cfDate]['others'][0]-$result['CashFlow'][''.$cfDate]['others'][1]);
						
						$item_totals['Items'][count($item_totals['Items'])-1]+=$total_net_profit;
						
						echo $total_net_profit; 
						
						?>
					</td>
					
				</tr>
			<?php endforeach; ?>
				<tr>
					<td><b>Total (UGX)</b></td>
					<?php foreach($item_totals['Items'] as $overall_total):?>
						<td><b><span class="ln"><?php echo $overall_total; ?></span></b></td>
					<?php endforeach;?>
				</tr>
		</table>
	</div>
</div>
<script>
function do_print(){
	$('.non_printable').remove();
	var x=window.open("","");
	x.document.write($('.printable').html());
	x.window.print();
}
</script>