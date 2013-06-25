<style>
	select, textarea, input, .uneditable-input {
		height: 25px;
		padding: -1px -1px;
		margin-bottom: 10px;
		font-size: 11px;
	}
	.well{
		border-radius: 14px;border: px solid #ddd;display: block !important;box-shadow: 4px 4px #DDD;
		width:80%;
	}
	form div {
		clear: both;
		margin-bottom: -23px;
		padding: .5em;
		vertical-align: text-top;
	}
	
	form .submit input[type=submit] {
		border-color: #eee;
		text-shadow: rgba(0, 0, 0, 0.5) 0px -1px 0px;
		padding: 0px 10px;
		float: right;
	}
	
	table tr td {
		border-bottom: 1px solid #f3f4f5;
	}
	table tr:nth-child(even) {
		background: none;
	}
</style>
<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="soldReceipts form" style="border-left:none;width:82%">
<?php echo $this->Form->create('PurchasedReceipt'); ?>
	<fieldset>
		<legend><?php echo __('Add Purchase Receipt'); ?></legend>
		
		<?php if($super_admin):?>
			<table class="well">
				<tr>
					<td valign="center" colspan="3">
						<?php echo $this->Form->input('user_id'); ?>
					</td>
				</tr>			
			<table>
		<?php endif; ?>
		
		<table class="well">
			<tr>
				<td valign="center">
					<br/><br/>
					<span class="btn btn-info generate-rid"><i class="icon-white icon-refresh"></i> Generate Receipt number</span>
				</td>
				<td>
					<div class="input select required"><label for="PurchasedReceiptNumber">Receipt number</label>
						<input type="text" name="data[PurchasedReceipt][id]" value=1 class="purchased_receipt_number" />
					</div>
				</td>
			</tr>			
		<table>
		
		<table class="well">
				<tr>
					<td>
					<?php echo $this->Form->input('currency_id',array('class'=>'my_currencies')); ?>
					<div class="my_oh_my"><?php echo $this->Form->input('other_currency_id',array('class'=>'other_name','options'=>$other_currencies)); ?></div>
					</td>					
					<td><?php echo $this->Form->input('purchased_purpose_id',array('label'=>'Source of funds')); ?></td>	
				</tr>
				<tr>
					<td><?php echo $this->Form->input('customer_name',array('style'=>'font-size: 10px;')); ?></td>
					<td><?php echo $this->Form->input('amount',array('class'=>'p_amount','style'=>'font-size: 10px;')); ?></td>
				</tr>
				<tr>
					<td><?php echo $this->Form->input('rate',array('class'=>'p_rate','style'=>'font-size: 10px;')); ?></td>
					<td><?php echo $this->Form->input('amount_ugx',array('class'=>'p_amount_ugx','readonly'=>'true','style'=>'font-size: 10px;')); ?></td>					
				</tr>
				
				<tr>
					<td><?php echo $this->Form->input('passport_number',array('style'=>'font-size: 10px;')); ?></td>				
					<td><?php echo $this->Form->input('nationality',array('style'=>'font-size: 10px;')); ?></td>
				</tr>
				<tr>
					<td colspan="<?php echo ($super_admin)? 1:2 ?>"><?php echo $this->Form->input('address',array('style'=>'font-size: 10px;height:'.(($super_admin)? "20px;":"30px;"),'type'=>'textarea')); ?></td>
						
					<?php if($super_admin): ?>		
							<td colspan="3">
							<label>Date:</label>
							<div class="input-append date" id='dp_x' data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
								<input style="width:210px;" class="span2" size="16" type="text" id='dp_today_selected' value="<?php echo date('Y-m-d'); ?>" name="data[PurchasedReceipt][date]"/>
								<span class="add-on"><i class="icon-th"></i></span>
							</div>					
							<?php //echo $this->Form->input('date'); ?></td>
							
							<script>
								$(document).ready(function(){
									$('#dp_x').datepicker({
										format: 'yyyy-mm-dd'
									});
								});
							</script>	
					<?php endif;?>
				</tr>
		</table>		
	</fieldset>
	<div id="test-group" class="input-prepend btn-group" data-toggle="buttons-radio" data-toggle-name="testOption">
		<input type="hidden" name="data[PurchasedReceipt][print]" value="print"/>
		<button type="button" class="btn active" data-toggle-value="print">Print</button>
		<button type="button" class="btn" data-toggle-value="dont_print">Dont' print</button>
	</div>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
</div>

<script>
	$(document).ready(function(){
		
		$('#test-group button').click(function(){
			$('#test-group input').val($(this).data("toggle-value"));
		});
		
		$('.generate-rid').click(function(){
			$.ajax({
				url: "<?php echo $this->webroot;?>m/get_receipt_number.php",
				data: {'company_id':<?php echo Configure::read('foxId');?>,'receipt_type':1},
				success: function(data){
					$('.purchased_receipt_number').val(data);
				}
			});
		});
		$('.p_amount, .p_amount_ugx, .p_rate').change(function(){
			var amount=$('.p_amount').val();
			var rate =$('.p_rate').val();
			if(amount>0 && rate>0)
				$('.p_amount_ugx').val(amount*rate);
		});
		
		var my_oh_my=$('.my_oh_my').html();
		var OtherNameValue=$('.OtherNameValue').val();
		$('.my_oh_my').html('');
		$('.my_oh_my').hide();
		
		$('.my_currencies').change(function(){
			
			if($(this).val()=='c8'){
				$('.my_oh_my').show();
				$('.my_oh_my').html(my_oh_my);
				$('.OtherNameValue').val(OtherNameValue);
			}else{
				my_oh_my=$('.my_oh_my').html();
				OtherNameValue=$('.OtherNameValue').val();
				$('.my_oh_my').html('');
				$('.my_oh_my').hide();
			}			
		});
	});
</script>