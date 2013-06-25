<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div style="float:right;margin-right:2.3%;">
	<span class="btn btn-small" onclick="do_receipt();"><i class="icon icon-print"></i> Print</span>	
</div>

<div class="soldReceipts printable well well-new">
	<style>
	<!--
		.my_data table tr td{
			border:1px solid #eee;
		}
	-->
	</style>
	<h2><?php echo __('Sales Receipts'); ?>
		<?php if(isset($large_cash)):?>
			<?php echo '(Large cash)'; ?>
		<?php endif; ?>
	</h2>
	<?php if(isset($from) && isset($to)):?>
		<h6><?php echo 'from '.$from.', to '.$to.' <span class="non_printable">('.$this->Time->timeAgoInWords($from, array('accuracy' => array('day' => 'day'),'end' => '1 year')).')</span>';?></h6>
	<?php endif; ?>
	<a href="<?php echo $this->webroot;?>sold_receipts/add" onclick="return false;" style="float:right;" class="non_printable btn btn-small" >
		<i class="icon icon-plus-sign"></i> New Sales Receipt
	</a>
	
	<?php if(isset($large_cash)):?>
		<span class="non_printable" style="color:maroon;"><b><?php echo ('Average Dollar Rate Used: $'.$dollar_av_rate);?></b></span>
	<?php endif; ?>
	
	<div class="my_data">
		<table cellpadding="0" cellspacing="0" style="width:100%;text-align:center;">
		<tr>
				<th class="actions non_printable"><?php echo __(''); ?></th>
				<th class="non_printable"><?php echo $this->Paginator->sort('id','Receipt number'); ?></th>
				<th>Amount</th>
				<th>Rate</th>
				<th><?php echo $this->Paginator->sort('amount_ugx'); ?></th>
				<th><?php echo $this->Paginator->sort('currency_id'); ?></th>
				<th><?php echo $this->Paginator->sort('instrument'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_name'); ?></th>
				<th><?php echo $this->Paginator->sort('date'); ?></th>			
				<th class="non_printable">Created by</th>			
		</tr>
		<?php $total_ugx=0;?>
		<?php foreach ($soldReceipts as $soldReceipt): ?>
		<tr style="border-left:4px solid #F9F9F9;border-right:4px solid #F9F9F9;">
			<td class="non_printable">
				<div class="btn-group">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
					  <i class="icon icon-certificate"></i>
					  <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a href="<?php echo $this->webroot;?>sold_receipts/print_receipt/<?php echo h($soldReceipt['SoldReceipt']['id']); ?>" onclick="return false;" class="print-receipt no-ajax" ><i class="icon icon-print"></i> Print</a></li>
						
						<?php if($super_admin): ?>
							<li class="divider"></li>
							<?php if($soldReceipt['SoldReceipt']['is_uploaded']==1): ?>
								<li><a href="<?php echo $this->webroot;?>sold_receipts/should_upload/<?php echo h($soldReceipt['SoldReceipt']['id']); ?>/0" onclick="return false;" >Uploadable</a></li>
							<?php elseif($soldReceipt['SoldReceipt']['is_uploaded']==0): ?>
								<li><a href="<?php echo $this->webroot;?>sold_receipts/should_upload/<?php echo h($soldReceipt['SoldReceipt']['id']); ?>/1" onclick="return false;" >Dont upload</a></li>
							<?php endif; ?>
						<?php endif; ?>
						
						<li class="divider"></li>
						<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $soldReceipt['SoldReceipt']['id']),array('class'=>'action-view')); ?></li>
						<?php if($super_admin): ?>
							<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $soldReceipt['SoldReceipt']['id']),array('class'=>'action-edit')); ?></li>						
							<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $soldReceipt['SoldReceipt']['id']),array('class'=>'action-delete confirm-first','data-confirm-text'=>'Are you sure you want to delete it?')); ?></li>
						<?php endif;?>						
					</ul>
				</div>		
			</td>
			<td class="non_printable"><?php echo h($soldReceipt['SoldReceipt']['id']); ?>&nbsp;</td>
			
			<td>
				<?php if($soldReceipt['Currency']['id']=='c8'): ?>
					<?php echo h($soldReceipt['SoldReceipt']['orig_amount']); ?>
				<?php else: ?>
					<?php echo h($soldReceipt['SoldReceipt']['amount']); ?>
				<?php endif; ?>
				&nbsp;			
			</td>
			<td>
				<?php if($soldReceipt['Currency']['id']=='c8'): ?>
					<?php echo h($soldReceipt['SoldReceipt']['orig_rate']); ?>
				<?php else: ?>
					<?php echo h($soldReceipt['SoldReceipt']['rate']); ?>
				<?php endif; ?>
				&nbsp;			
			</td>
			<td><?php echo h($soldReceipt['SoldReceipt']['amount_ugx']);$total_ugx+=($soldReceipt['SoldReceipt']['amount_ugx']); ?>&nbsp;</td>
			<td>
				<?php if($soldReceipt['Currency']['id']=='c8'): ?>
					<?php echo $soldReceipt['SoldReceipt']['other_name']; ?>
				<?php else: ?>
					<?php echo $soldReceipt['Currency']['description']; ?>
				<?php endif; ?>
			</td>
			<td><?php echo h($soldReceipt['SoldReceipt']['instrument']); ?>&nbsp;</td>
			<td><?php echo h($soldReceipt['SoldReceipt']['customer_name']); ?>&nbsp;</td>
			<td><?php echo h($soldReceipt['SoldReceipt']['date']); ?>&nbsp;</td>
			<td class="non_printable"><?php echo h($soldReceipt['SoldReceipt']['name']); ?>&nbsp;</td>
			
		</tr>
	<?php endforeach; ?>
		<tr>
			<td colspan="10" style="background:#2c2c2c;color:#fff;">
				<b>Total (UGX for the above records):</b> <span style="margin-left:30px;" class="ln"><?php echo $total_ugx; ?></span>
			</td>
		</tr>
		</table>
	</div>
	<p class="non_printable">
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging non_printable">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php echo $this->element('sql_dump'); ?>
<script>
	$('.print-receipt').click(function(){
		$.get($(this).attr('href'),function(data){
			//alert(data);
		});
	});
	
	function do_receipt(){
		$('.non_printable').remove();
		var x=window.open("","");
		x.document.write($('.printable').html());
		x.window.print();
	}
</script>
