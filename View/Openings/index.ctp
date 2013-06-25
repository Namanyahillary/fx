<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="openings">
	<h2><?php echo __('Openings'); ?></h2>
	<div class="actions" style="float:right;">
		<h3><?php echo __(''); ?></h3>
		<ul>
			<li><?php echo $this->Html->link(__('New Opening'), array('action' => 'add')); ?> </li>
		</ul>
	</div>
	<table cellpadding="0" cellspacing="0" class="well">
	<tr>
			<?php if($super_admin):?> <th></th> <?php endif; ?>
			<th></th>
			<th><?php echo $this->Paginator->sort('user_id',Cachier); ?></th>
			<th><?php echo $this->Paginator->sort('date'); ?></th>
			<th><?php echo $this->Paginator->sort('opening_ugx'); ?></th>
			<th><?php echo $this->Paginator->sort('c1a','USD opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c1r','USD opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c2a','Euro opening amont'); ?></th>
			<th><?php echo $this->Paginator->sort('c2r','Euro opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c3a','GBP opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c3r','GBP opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c4a','Kshs opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c4r','Kshs opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c5a','Tzsh opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c5r','Tzsh opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c6a','SAR opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c6r','SAR opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c7a','SP opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c7r','SP opening rate'); ?></th>
			<th><?php echo $this->Paginator->sort('c8a','Others opening amount'); ?></th>
			<th><?php echo $this->Paginator->sort('c8r','Others opening rate'); ?></th>
			<?php foreach($other_currencies as $other_currency):?>
				<th><?php echo $other_currency['OtherCurrency']['name'].' opening amount'; ?></th>
				<th><?php echo $other_currency['OtherCurrency']['name'].' opening rate'; ?></th>
			<?php endforeach;?>
			<th class="actions"><?php echo __(''); ?></th>
	</tr>
	<?php foreach ($openings as $opening): ?>
	<tr>
		<?php if($super_admin):?>
		<td>
			<div class="btn-group">
				<button class="btn dropdown-toggle" data-toggle="dropdown">
				  <i class="icon icon-certificate"></i>
				  <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<?php if(!$opening['Opening']['status']):?>													
							<li>
								<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $opening['Opening']['id'],$opening['Opening']['user_id'])); ?>
							</li>						
						<li class="divider"></li>
					<?php endif;?>
					<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $opening['Opening']['id']),array('class'=>'action-delete confirm-first','data-confirm-text'=>'Are you sure you want to delete it?')); ?></li>					
				</ul>
			</div>
		</td>
		<?php endif; ?>	
		
		<td>
			<?php if($opening['Opening']['status']): ?>
				<?php echo $this->Html->image('test-pass-icon.png',array('style'=>'max-width: 15px;')); ?>
			<?php else: ?>
				<?php echo $this->Html->image('test-fail-icon.png',array('style'=>'max-width: 15px;')); ?>
			<?php endif; ?>&nbsp;
		</td>
		<td>
			<?php echo $this->Html->link($opening['User']['name'], array('controller' => 'users', 'action' => 'view', $opening['User']['id'])); ?>
		</td>
		<td><?php echo h($opening['Opening']['date']); ?>&nbsp;</td>
		<td class="ln"><?php echo h($opening['Opening']['opening_ugx']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c1a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c1r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c2a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c2r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c3a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c3r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c4a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c4r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c5a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c5r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c6a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c6r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c7a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c7r']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c8a']); ?>&nbsp;</td>
		<td><?php echo h($opening['Opening']['c8r']); ?>&nbsp;</td>
		
		<?php
			$data=json_decode($opening['Opening']['other_currencies']);
		?>
		
		<?php foreach($other_currencies as $other_currency):?>
			<?php $_amount=$_rate=0;?>
			<?php
				foreach($data as $_other_currencies){
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
			<?php
				echo '<td>'.($_amount).'</td>';
				echo '<td>'.($_rate).'</td>';
			?>
		<?php endforeach;?>
		
		<?php if($super_admin):?>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $opening['Opening']['id'],$opening['Opening']['user_id'])); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $opening['Opening']['id']),array('class'=>'action-delete confirm-first','data-confirm-text'=>'Are you sure you want to delete it?')); ?>
		</td>
		<?php endif; ?>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
