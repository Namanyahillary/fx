<?php
//var_dump($openings);
?>
<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>

<div class="well well-new">		
	<table class="well">
		<tr style="background: #2e335b;color:#ddd">
			<td><b>Cashier</b></td>
			<td><b>Opening (UGX)</b></td>
			<td><b>Closing Cash At Hand (UGX)</b></td>
			<td><b>Total Gross Profit</b></td>			
			<td><b>Total Expenses</b></td>
			<td><b>Total Net Profit</b></td>
			<td><b>Receivable Cash</b></td>
			<td><b>Withdrawal Cash</b></td>
			<td><b>Additional Profits</b></td>
			<td><b>Total Purchases Ugx</b></td>
			<td><b>Total Sales Ugx</b></td>
			<td><b>Total Foreign Cash At Bank</b></td>
			<td><b>Total Ugx At Bank</b></td>
			<td><b>Total Of Debtors</b></td>
			<td><b>Total Of Creditors</b></td>
			<td><b>Total Close Foreign(UGX)</b></td>
		</tr>
		<?php 
			$opening_ugx=0;
			$total_gross_profit=0;
			$total_expenses=0;
			$receivable_cash=0;
			$withdrawal_cash=0;
			$additional_profits=0;
			$total_purchases_ugx=0;			
			$total_sales_ugx=0;			
			$total_profit=0;			
			$total_cash_at_bank_foreign=0;			
			$total_cash_at_bank_ugx=0;			
			$total_debtors=0;			
			$total_creditors=0;		
			$total_close_ugx=0;	
			$total_cash_at_hand=0;	
			foreach($openings as $opening):
				$opening_ugx				+=	$opening['Opening']['opening_ugx'];
				$total_gross_profit			+=	$opening['Opening']['total_gross_profit'];
				$total_expenses				+=	$opening['Opening']['total_expenses'];
				$receivable_cash			+=	$opening['Opening']['receivable_cash'];
				$withdrawal_cash			+=	$opening['Opening']['withdrawal_cash'];
				$additional_profits			+=	$opening['Opening']['additional_profits'];
				$total_purchases_ugx		+=	$opening['Opening']['total_purchases_ugx'];
				$total_sales_ugx			+=	$opening['Opening']['total_sales_ugx'];
				$total_profit				+=	$opening['Opening']['total_profit'];				
				$total_cash_at_bank_foreign	+=	$opening['Opening']['cash_at_bank_foreign'];				
				$total_cash_at_bank_ugx		+=	$opening['Opening']['cash_at_bank_ugx'];				
				$total_debtors				+=	$opening['Opening']['debtors'];				
				$total_creditors			+=	$opening['Opening']['creditors'];	
				$total_close_ugx			+=	$opening['Opening']['close_ugx'];	
		?>
		<tr>
			<td class="ln"><?php echo $opening['User']['name'];?></td>
			<td class="ln"><?php echo $opening['Opening']['opening_ugx'];?></td>
			<?php 
				$cash_at_hand=0; 
				$cash_at_hand=((($opening['Opening']['total_sales_ugx'])-($opening['Opening']['total_expenses'])+($opening['Opening']['opening_ugx'])+($opening['Opening']['receivable_cash'])+($opening['Opening']['additional_profits']))-(($opening['Opening']['total_purchases_ugx'])+($opening['Opening']['withdrawal_cash'])));
		
				$cash_at_hand=($cash_at_hand)-(($opening['Opening']['cash_at_bank_foreign'])+($opening['Opening']['cash_at_bank_ugx']));
				$cash_at_hand_b=$cash_at_hand+$total_creditors;
				$cash_at_hand_e=$cash_at_hand_b-$total_debtors;
				$cash_at_hand_f=$cash_at_hand_e-$total_creditors;
				$cash_at_hand_g=$cash_at_hand_f+$total_debtors;
				
				$cash_at_hand=$cash_at_hand_g;
				$total_cash_at_hand+=$cash_at_hand;
			
			?>
			<td class="ln"><?php echo $cash_at_hand;?></td>
			<td class="ln"><?php echo $opening['Opening']['total_gross_profit'];?></td>
			<td class="ln"><?php echo $opening['Opening']['total_expenses'];?></td>
			<td class="ln"><?php echo $opening['Opening']['total_profit']-$opening['Opening']['total_expenses'];?></td>
			<td class="ln"><?php echo $opening['Opening']['receivable_cash'];?></td>
			<td class="ln"><?php echo $opening['Opening']['withdrawal_cash'];?></td>
			<td class="ln"><?php echo $opening['Opening']['additional_profits'];?></td>
			<td class="ln"><?php echo $opening['Opening']['total_purchases_ugx'];?></td>
			<td class="ln"><?php echo $opening['Opening']['total_sales_ugx'];?></td>
			<td class="ln"><?php echo $opening['Opening']['cash_at_bank_foreign'];?></td>
			<td class="ln"><?php echo $opening['Opening']['cash_at_bank_ugx'];?></td>
			<td class="ln"><?php echo $opening['Opening']['debtors'];?></td>
			<td class="ln"><?php echo $opening['Opening']['creditors'];?></td>
			<td class="ln"><?php echo $opening['Opening']['close_ugx'];?></td>			
		</tr>
		<?php endforeach; ?>
	
		<tr style="background: #2e335b;color:#ddd">
			<td>Total</td>
			<td><b><span class="ln"><?php echo $opening_ugx;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_cash_at_hand;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_gross_profit;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_expenses;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_profit-$total_expenses;?></span></b></td>
			<td><b><span class="ln"><?php echo $receivable_cash;?></span></b></td>
			<td><b><span class="ln"><?php echo $withdrawal_cash;?></span></b></td>
			<td><b><span class="ln"><?php echo $additional_profits;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_purchases_ugx;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_sales_ugx;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_cash_at_bank_foreign;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_cash_at_bank_ugx;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_debtors;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_creditors;?></span></b></td>
			<td><b><span class="ln"><?php echo $total_close_ugx;?></span></b></td>
		</tr>
	</table>

	<p>
		<div style="font-size:150%"><b>Total Cash at hand: </b><span class="ln"><?php echo $total_cash_at_hand; ?></span> UGX</div><hr/>
	</p>
	<br/><br/><br/>
	
	<div class="well">
		Currencies in Foreign for Major Currencies
	</div>
	<table class="well">
		<tr style="background: #2e335b;color:#ddd">
			<td><b>Cashier</b></td>
			<td><b>USD</b></td>
			<td><b>Euro</b></td>
			<td><b>GBP</b></td>
			<td><b>Kshs</b></td>			
			<td><b>Tzshs</b></td>
			<td><b>SAR</b></td>
			<td><b>SP</b></td>
			<td><b>Others</b></td>
		</tr>
		<?php 
			$c1a=0;
			$c2a=0;
			$c3a=0;
			$c4a=0;
			$c5a=0;
			$c6a=0;
			$c7a=0;			
			$c8a=0;	
			foreach($openings as $opening):
				$c1a			+=	$opening['Opening']['c1a'];
				$c2a			+=	$opening['Opening']['c2a'];
				$c3a			+=	$opening['Opening']['c3a'];
				$c4a			+=	$opening['Opening']['c4a'];
				$c5a			+=	$opening['Opening']['c5a'];
				$c6a			+=	$opening['Opening']['c6a'];
				$c7a			+=	$opening['Opening']['c7a'];
				$c8a			+=	$opening['Opening']['c8a'];
		?>
		<tr>
			<td class="ln"><?php echo $opening['User']['name'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c1a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c2a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c3a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c4a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c5a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c6a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c7a'];?></td>
			<td class="ln"><?php echo $opening['Opening']['c8a'];?></td>	
		</tr>
		<?php endforeach; ?>
	
		<tr style="background: #2e335b;color:#ddd">
			<td>Total</td>
			<td><b><span class="ln"><?php echo $c1a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c2a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c3a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c4a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c5a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c6a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c7a;?></span></b></td>
			<td><b><span class="ln"><?php echo $c8a;?></span></b></td>
		</tr>
	</table>
	
	
	
	
	
	
	
	
	
	
	
	
	
	<br/><br/><br/><br/><br/>
	<div class="well">
		Currencies in Foreign for Minor Currencies
	</div>
	<table class="well">
		<tr style="background: #2e335b;color:#ddd">
			<?php
				$totals=array();
				echo '<td >Cashier</td>';
				foreach($other_currencies as $other_currency){
					echo '<td>'.$other_currency['OtherCurrency']['id'].'</td>';
					$totals[''.$other_currency['OtherCurrency']['id']]=0;
				}
			?>
		</tr>
		<?php foreach($openings as $opening):	?>			
			<tr>
				<?php
					$_data=json_decode($opening['Opening']['other_currencies']);
					echo '<td>'.($opening['User']['name']).'</td>';
					foreach($_data as $_other_currencies){
						foreach($_other_currencies as $_other_currency){
							echo '<td><span class="ln">'.($_other_currency->CAMOUNT).'</span></td>';
							$totals[''.$_other_currency->CID]+=$_other_currency->CAMOUNT;
						}
					}
				?>
			</tr>
		
		<?php endforeach; ?>
		
		<tr style="background: #2e335b;color:#ddd">
			<?php
				echo '<td >Total</td>';
				foreach($other_currencies as $other_currency){
					echo '<td><b><span class="ln">'.($totals[''.$other_currency['OtherCurrency']['id']]).'</span></b></td>';
				}
			?>
		</tr>
	</table>
</div>
