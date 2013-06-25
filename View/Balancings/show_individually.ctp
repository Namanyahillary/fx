<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<?php
	//pr($openings);
	//pr($currencies);
	//pr($purchases);
	
	if(isset($openings[0]['Opening']['opening_ugx'])){
		if(count($openings[0]['Opening']['opening_ugx'])){
			
		}else{
			echo 'No opening found for today';
			exit;
		}
	}else{
		echo 'No opening found for today';
		exit;
	}
	
?>
<div class="well well-new">
	<div class="well">
	<?php 
		if($openings[0]['Opening']['status']){
			echo '<div class="btn btn-danger"><b>This is a saved balance position of </b>'.date((' l jS F Y'),strtotime($openings[0]['Opening']['date'])).'</b></div>';
		}else{
			echo '<div class="btn btn-success"><b>This balance position is awaiting saving. This balancing is of '.date((' l jS F Y'),strtotime($date_today)).'</b></div>';
		}
		
	?>
	</div>
	<p>
		<b>Opening UGX</b>: <span class="ln"><?php echo $openings[0]['Opening']['opening_ugx']; ?></span>
		<?php if(!$openings[0]['Opening']['status'] and $super_admin): ?>
		<a style="color:#fff" href="<?php echo $this->webroot.'openings/edit/'.($openings[0]['Opening']['id']).'/'.($openings[0]['Opening']['user_id']);?>"><span style="float:right;" class="btn btn-primary"><i class="icon-white icon-edit"></i> Edit</span></a>
		<?php endif; ?>
	</p>
	
	<table class="well">
			<tr style="background: #2e335b;color:#ddd">
				<td>
					<b>Currency</b>
				</td>
				<td>
					<b>Amount</b>
				</td>
				<td>
					<b>Rate</b>
				</td>
				<td>
					<b>UGX</b>
				</td>

			</tr>
		<?php 
			$total_ugx=0;
			foreach($currencies as $currency):
		?>
			<?php if($currency['Currency']['id']=='c8'):?>
				<?php 
					$other_count=-1;
					$_data=json_decode($openings[0]['Opening']['other_currencies']);
					foreach($other_currencies as $other_currency):
						$other_count++;
						$_amount=$_rate=0;
						foreach($_data as $_other_currencies){
							foreach($_other_currencies as $_other_currency){	
								if(isset($_other_currency->CID)){
									if($_other_currency->CID==$other_currency['OtherCurrency']['id']){
										$_amount=$_other_currency->CAMOUNT;
										$_rate=$_other_currency->CRATE;
									}
								}
							}
						}
				?> 
					<tr>
						<td>
							<b><?php echo $other_currency['OtherCurrency']['name']; ?></b>
						</td>
						<td>
							<span class="ln"><?php echo $_amount; ?></span>
						</td>
						<td>
							<?php echo $_rate; ?>
						</td>
						<?php $ugx=($_amount*$_rate); ?>
						<td>
							<span class="ln"><?php echo $ugx; ?></span>
						</td>
						<?php $total_ugx+=$ugx; ?>
					</tr>
					
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td>
						<b><?php echo $currency['Currency']['description']; ?></b>
					</td>
					<td>
						<span class="ln"><?php echo $openings[0]['Opening'][$currency['Currency']['id'].'a']; ?></span>
					</td>
					<td>
						<?php echo $openings[0]['Opening'][$currency['Currency']['id'].'r']; ?>
					</td>
					<?php $ugx=($openings[0]['Opening'][$currency['Currency']['id'].'a'])*($openings[0]['Opening'][$currency['Currency']['id'].'r']); ?>
					<td>
						<span class="ln"><?php echo $ugx; ?></span>
					</td>
					<?php $total_ugx+=$ugx; ?>
				</tr>
			<?php endif; ?>
			
		<?php endforeach;?>
			<tr>
				<td></td><td></td><td></td>
				
				<td>
					<b>= <span class="ln"><?php echo $total_ugx; ?></span></b>
				</td>
			</tr>
	</table>
	<br/>
	<p>
		<?php $total_opening_stock=$total_ugx+$openings[0]['Opening']['opening_ugx'];?>
		<b>Total Opening Stock: </b> <?php echo $total_ugx; ?> + <?php echo $openings[0]['Opening']['opening_ugx']; ?> = <span class="ln"><?php echo $total_opening_stock ?></span>
	</p>
	<br/>
	
	<table class="well">
			<tr style="background: #2e335b;color:#ddd">
				<td>
					<b>Currency</b>
				</td>
				<td>
					<b>Purchases</b>
				</td>
				<td>
					<b>Purchases AVG rate</b>
				</td>
				<td>
					<b>Purchases UGX</b>
				</td>
				<td>
					<b>Sales</b>
				</td>
				
				<td>
					<b>Sales AVG rate</b>
				</td>
				
				<td>
					<b>Sales UGX</b>
				</td>
				
			</tr>
		<?php 
			$total_purchases_ugx=0;
			$total_purchases=0;
			$total_sales_ugx=0;
			$total_sales=0;
			$count=-1;
			foreach($currencies as $currency):
				$count++;
		?>
			<?php if($currency['Currency']['id']=='c8'):?>
				<?php 
					$other_count=-1;
					foreach($other_currencies as $other_currency):
						$other_count++;
					?>
					<tr>				
						<td>
							<b><?php echo $other_currency['OtherCurrency']['name']; ?></b>
						</td>
						<td style="background:lime">
							<span class="ln"><?php echo $other_currencies_purchases[$other_count]['total_amount']; ?></span>
						</td>
						<td style="background:lime">
							<span class="ln"><?php echo $other_currencies_purchases[$other_count]['av_rate']; ?></span>
						</td>
						<td style="background:lime">
							<span class="ln"><?php echo $other_currencies_purchases[$other_count]['total_amount']*$other_currencies_purchases[$other_count]['av_rate']; ?></span>
						</td>
						<td style="background:cyan">
							<span class="ln"><?php echo $other_currencies_sales[$other_count]['total_amount']; ?></span>
						</td>				
						<td style="background:cyan">
							<span class="ln"><?php echo $other_currencies_sales[$other_count]['av_rate']; ?></span>
						</td>				
						<td style="background:cyan">
							<span class="ln"><?php echo $other_currencies_sales[$other_count]['total_amount']*$other_currencies_sales[$other_count]['av_rate']; ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					
					<td>
						<b><?php echo $currency['Currency']['description']; ?></b>
					</td>
					<td style="background:lightgreen">
						<span class="ln"><?php echo $purchases[$count]['total_amount']; ?></span>
					</td>
					<td style="background:lightgreen">
						<span class="ln"><?php echo $purchases[$count]['av_rate']; ?></span>
					</td>
					<td style="background:lightgreen">
						<span class="ln"><?php echo $purchases[$count]['total_amount']*$purchases[$count]['av_rate']; ?></span>
					</td>
					<td style="background:skyblue">
						<span class="ln"><?php echo $sales[$count]['total_amount']; ?></span>
					</td>				
					<td style="background:skyblue">
						<span class="ln"><?php echo $sales[$count]['av_rate']; ?></span>
					</td>				
					<td style="background:skyblue">
						<span class="ln"><?php echo $sales[$count]['total_amount']*$sales[$count]['av_rate']; ?></span>
					</td>
				</tr>
			<?php endif;?>
			
			<?php $total_purchases+=$purchases[$count]['total_amount'];?>
			<?php $total_sales+=$sales[$count]['total_amount'];?>
			
			<?php $total_purchases_ugx+=$purchases[$count]['total_amount']*$purchases[$count]['av_rate'];?>
			<?php $total_sales_ugx+=$sales[$count]['total_amount']*$sales[$count]['av_rate'];?>
			
		<?php endforeach;?>			
			<tr>
				<td></td>
				<td><!--<b>=<span class="ln"><?php echo $total_purchases; ?></span></b>--></td>
				<td></td>
				<td><b>=<span class="ln"><?php echo $total_purchases_ugx; ?></span></b></td>
				<td><!--<b>=<span class="ln"><?php echo $total_sales; ?></span></b>--></td>
				<td></td>
				<td><b>=<span class="ln"><?php echo $total_sales_ugx; ?></span></b></td>
			</tr>
	</table>	
	<br/><br/>
	
	
	
	
	
	
	
	<table class="well">
			<tr style="background: #2e335b;color:#ddd">
				<td>
					<b>Currency</b>
				</td>
				<td>
					<b>Today&apos;s Closing Foreign</b>
				</td>
				<td>
					<b>Today&apos;s Average closing rate</b>
				</td>
				<td>
					<b>Today&apos;s Closing UGX</b>
				</td>
				<td>
					<b>Gross Profit</b>
				</td>				
				<!--<td>
					<b>Profit</b>
				</td>-->
			</tr>
		<?php 
			$total_purchases_ugx=0;
			$total_purchases=0;
			$total_sales_ugx=0;
			$total_sales=0;
			$total_profits=0;
			$total_gross_profits=0;
			$total_todays_close=0;
			$total_todays_close_ugx=0;
			
			if($openings[0]['Opening']['status']){//if its an old record get the preset values
				$receivable_cash=$openings[0]['Opening']['receivable_cash'];// (M)
				$withdrawal_cash=$openings[0]['Opening']['withdrawal_cash'];//(M)
				$additional_profits=$openings[0]['Opening']['additional_profits'];//(M)
			}
			
			//$receivable_cash=0;//(M)
			//$withdrawal_cash=0;//(M)
			//$additional_profits=0;//(M)
			$expenses=0;//(C)
			if(isset($total_expenses[0][0]['total_expenses']))
				$expenses=(double)$total_expenses[0][0]['total_expenses'];
			
			$count=-1;
			foreach($currencies as $currency):
				$count++;
		?>
			
			<?php if($currency['Currency']['id']=='c8'):?>
				<?php 
					$other_count=-1;
					$_data=json_decode($openings[0]['Opening']['other_currencies']);
					foreach($other_currencies as $other_currency):
						$other_count++;
						$_amount=$_rate=0;
						foreach($_data as $_other_currencies){
							foreach($_other_currencies as $_other_currency){	
								if(isset($_other_currency->CID)){
									if($_other_currency->CID==$other_currency['OtherCurrency']['id']){
										$_amount=$_other_currency->CAMOUNT;
										$_rate=$_other_currency->CRATE;
									}
								}
							}
						}
					?>
					<tr>
						<td>
							<b><?php echo $other_currency['OtherCurrency']['name']; ?></b>
						</td>
						<td>
							<span class="ln">
							<?php
								$todays_close=(($_amount)+($other_currencies_purchases[$other_count]['total_amount']))-($other_currencies_sales[$other_count]['total_amount']);
								echo $todays_close;
								$total_todays_close+=$todays_close;
							?>
							</span>
						</td>
						<td>
							<?php 
							
								$av_ugx=($other_currencies_purchases[$other_count]['total_amount']*$other_currencies_purchases[$other_count]['av_rate'])+(($_amount)*($_rate));
								$av_rate=($other_currencies_purchases[$other_count]['total_amount'])+($_amount);
								
								//New Av Closing rate
								$av_close_rate= ($av_rate!=0)?$av_ugx/$av_rate:0;
								echo $av_close_rate;
							?>
						</td>												
						<td>
							<span class="ln">
							<?php 
								$todays_close_ugx=$av_close_rate*$todays_close;
								echo $todays_close_ugx;
								$total_todays_close_ugx+=$todays_close_ugx;
							?>
							</span>
						</td>
						<td>
							<span class="ln">
							<?php 
								$GP = $other_currencies_sales[$other_count]['total_amount']*($other_currencies_sales[$other_count]['av_rate']-$av_close_rate);
								echo $other_currencies_sales[$other_count]['total_amount']; 
							?>
							</span>
						</td>
						<!--<td>
							<span class="ln">
							<?php						
								$NP=$GP;						
								echo $NP;
								$total_gross_profits+=$GP;
								$total_profits+=$NP;
							?>
							</span>
						</td>-->
					</tr>
					<?php $total_purchases+=$other_currencies_purchases[$other_count]['total_amount'];?>
					<?php $total_sales+=$other_currencies_sales[$other_count]['total_amount'];?>
					
					<?php $total_purchases_ugx+=$other_currencies_purchases[$other_count]['total_amount']*$other_currencies_purchases[$other_count]['av_rate'];?>
					<?php $total_sales_ugx+=$other_currencies_sales[$other_count]['total_amount']*$other_currencies_sales[$other_count]['av_rate'];?>
				<?php endforeach;?>	
			<?php else: ?>				
				<tr>
					<td>
						<b><?php echo $currency['Currency']['description']; ?></b>
					</td>
					<td>
						<span class="ln">
						<?php
							$todays_close=(($openings[0]['Opening'][$currency['Currency']['id'].'a'])+($purchases[$count]['total_amount']))-($sales[$count]['total_amount']);
							echo $todays_close;
							$total_todays_close+=$todays_close;
						?>
						</span>
					</td>
					<td>
						<?php 
						
							$av_ugx=($purchases[$count]['total_amount']*$purchases[$count]['av_rate'])+(($openings[0]['Opening'][$currency['Currency']['id'].'a'])*($openings[0]['Opening'][$currency['Currency']['id'].'r']));
							$av_rate=($purchases[$count]['total_amount'])+($openings[0]['Opening'][$currency['Currency']['id'].'a']);
							
							//New Av Closing rate
							$av_close_rate= ($av_rate!=0)?$av_ugx/$av_rate:0;
							echo $av_close_rate;
						?>
					</td>
					<td>
						<span class="ln">
						<?php 
							$todays_close_ugx=$av_close_rate*$todays_close;
							echo $todays_close_ugx;
							$total_todays_close_ugx+=$todays_close_ugx;
						?>
						</span>
					</td>
					<td>
						<span class="ln">
						<?php 
							$GP = $sales[$count]['total_amount']*($sales[$count]['av_rate']-$av_close_rate);
							echo $GP; 
						?>
						</span>
					</td>
					<!--<td>
						<span class="ln">
						<?php						
							$NP=$GP;						
							echo $NP;
							$total_gross_profits+=$GP;
							$total_profits+=$NP;
						?>
						</span>
					</td>-->
				</tr>
				<?php $total_purchases+=$purchases[$count]['total_amount'];?>
				<?php $total_sales+=$sales[$count]['total_amount'];?>
				
				<?php $total_purchases_ugx+=$purchases[$count]['total_amount']*$purchases[$count]['av_rate'];?>
				<?php $total_sales_ugx+=$sales[$count]['total_amount']*$sales[$count]['av_rate'];?>
				
			<?php endif; ?>
			
		<?php endforeach;?>	
			<?php
				$total_profits+=$additional_profits;
			?>
			<tr>
				<td></td>
				<td><b>=<span class="ln"><?php echo $total_todays_close; ?></span></b></td>
				<td></td>
				<td><b>=<span class="ln"><?php echo $total_todays_close_ugx; ?></span></b></td>
				<td></td>
				<!--<td><b>=<span class="ln"><?php echo $total_profits; ?></span></b></td>-->
			</tr>
	</table>	
	
	<p><b>Total Profit: </b><?php echo $total_profits; ?> UGX, <b>Total expenses: </b><?php echo $expenses;?> UGX, <b>Total Net Profit: </b><?php echo $total_profits-$expenses;?> UGX </b></p>
	
	<p>
		<?php echo ($total_profits-$expenses); ?>
		<?php $cash_at_hand=(($total_sales_ugx-($expenses)+$openings[0]['Opening']['opening_ugx']+$receivable_cash+$additional_profits)-($total_purchases_ugx+$withdrawal_cash));?>
		<div style="font-size:150%"><b>Cash at hand: </b><span class="ln"><?php echo $cash_at_hand; ?></span> UGX</div>
		<div style="font-size:150%"><b>Total Closing Stock: </b><span class="ln"><?php echo $cash_at_hand+$total_todays_close_ugx; ?></span> UGX</div>
	</p>
	<hr/>
	<h6 style="background:#2e335b;color:#ddd;text-align:center">Other Summary</h6>
	<div class="well">
		<div style="vertical-align:middle;margin-left: 4%;">
			<div class="row">
				<div class="span4">					
					<p><?php echo 'Withdrawal Cash:<span class="ln">'.$withdrawal_cash.'</span>';?></p>
					<p><?php echo 'Receivable Cash:<span class="ln">'.$receivable_cash.'</span>';?></p>
					<p><?php echo 'Expenses:<span class="ln">'.$expenses.'</span>';?></p>	
					<p><?php echo 'Additional Profits:<span class="ln">'.$additional_profits.'</span>';?></p>	
					
				</div>
				<div class="span4">	
					<p><?php echo 'Total Sales UGX:<span class="ln">'.$total_sales_ugx.'</span>';?></p>
					<p><?php echo 'Total Purchases UGX:<span class="ln">'.$total_purchases_ugx.'</span>';?></p>					
				</div>
				<div class="span4">	
					<p><?php echo 'Total Profits:<span class="ln">'.$total_profits.'</span>';?></p>
					<p><?php echo 'Opening UGX:<span class="ln">'.$openings[0]['Opening']['opening_ugx'].'</span>';?></p>					
				</div>
			</div><hr/>
			<div class="row">
				<div class="span4">					
					<p><?php echo 'Cash at bank Foreign:<span class="ln">'.$cash_at_bank_foreign.'</span>';?></p>
					<p><?php echo 'Cash at bank Ugx:<span class="ln">'.$cash_at_bank_ugx.'</span>';?></p>
					
				</div>
				<div class="span4">	
					<p><?php echo 'Debtors:<span class="ln">'.$debtors.'</span>';?></p>
					<p><?php echo 'Creditors:<span class="ln">'.$creditors.'</span>';?></p>					
				</div>
				<div class="span4">	
										
				</div>
			</div>
			<p><div style="font-size:150%"><b>Final Cash at hand: </b><span class="ln"><?php echo ($cash_at_hand+$total_todays_close_ugx+$creditors)-($cash_at_bank_foreign+$cash_at_bank_ugx+$debtors); ?></span> UGX</div></p>
			
		</div>
	</div>
	<hr/>
	<?php if($openings[0]['Opening']['status']){
			echo '<b style="color:red;">This is a saved opening of </b>'.date((' l jS F Y'),strtotime($openings[0]['Opening']['date']));
			exit;
		}
		
	?>
	
	<div class="well">
		<?php echo $this->Form->create('Balancing',array('action'=>'save_opening',$user_id,array('class'=>'form-price-additions no-ajax'))); ?>
				
				<span>Receivable Cash(UGX):</span>
				<input class="span2 receivable_cash" size="16" type="number" name="data[Opening_old][receivable_cash]" value="<?php echo $receivable_cash; ?>" />
				<span>Withdrawal Cash(UGX):</span>
				<input class="span2 withdrawal_cash" size="16" type="number" name="data[Opening_old][withdrawal_cash]" value="<?php echo $withdrawal_cash; ?>" />
				<span>Additional Profits(UGX):</span>
				<input class="span2 additional_profits" size="16" type="number" name="data[Opening_old][additional_profits]" value="<?php echo $additional_profits; ?>" />
				<span class="btn btn-small apply-additions">Apply</span>
				<input type="hidden" name="data[Opening_old][user_id]" value="<?php echo $user_id; ?>" />
				<input type="hidden" name="data[Opening_old][total_todays_close_ugx]" value="<?php echo $total_todays_close_ugx; ?>" />
				<hr/>
				<center>
				<div class="btn btn-small">
					<label style="font-size:120%;"><b>Select the Next opening day:</b></label>
					<div class="input-append date" id='dp_next' data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
						<input class="span2" size="16" style="width:175px;" type="text" id='dp_today_selected' name="data[Opening][date]" value="<?php echo date('Y-m-d'); ?>" />
						<span class="add-on"><i class="icon-th"></i></span>
					</div>
				</div>
				</center>
		
		<span style="float:right;"><?php echo $this->Form->end(__('SAVE')); ?></span>	
	</div><br/><br/>
	
	<script>
		$(document).ready(function(){
			$('#dp_next').datepicker({
				format: 'yyyy-mm-dd'
			});
			
			$('.apply-additions').click(function(){
				console.log($('.receivable_cash').val());
				var receivable_cash=$('.receivable_cash').val();
				var withdrawal_cash=$('.withdrawal_cash').val();
				var additional_profits=$('.additional_profits').val();
				data={'date_today':$('#dp_today_selected').val()};
				
				$.ajax({type: "POST",url: '<?php echo $this->webroot.'balancings/show_individually';?>/'+receivable_cash+'/'+withdrawal_cash+'/'+additional_profits+'/<?php echo $user_id;?>',data: data,dataType: "html",
					success: function(data) {$('.dynamic-content').html(data);} 
				});
			});
		});
	</script>
	
<div>