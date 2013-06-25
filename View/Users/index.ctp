<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<?php if($super_admin): ?>
<div class="actions">
	<h3><?php echo __(''); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
	</ul>
</div>
<?php endif; ?><br/>
<p>
<div class="users well">
	<h2><?php echo __('Users'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th class="actions"><?php echo __(''); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('email'); ?></th>
			<th><?php echo $this->Paginator->sort('role'); ?></th>			
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td class="actions">		
			<div class="btn-group" style="margin-left:10%;">
				<button class="btn dropdown-toggle" data-toggle="dropdown">
				  <i class="icon icon-certificate"></i>&nbsp;<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link('Individual balancing',array('controller'=>'balancings','action'=>'show_individually',0,0,0,$user['User']['id']),array('class'=>'use-ajax','style'=>'margin-left:10px;')); ?></li>
					<li><?php echo $this->Html->link('View',array('action' => 'view', $user['User']['id']),array('class'=>'use-ajax','style'=>'margin-left:10px;')); ?></li>
					<li><?php echo $this->Html->link('Edit',array('action' => 'settings', $user['User']['id']),array('class'=>'use-ajax','style'=>'margin-left:10px;')); ?></li>
					<li class="divider"></li>					
					<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $user['User']['id']), array('class'=>'confirm-first use-ajax','style'=>'margin-left:10px;','data-confirm-text'=>__('Are you sure you want to delete # %s?', $user['User']['id']))); ?></li>
				</ul>												
			</div>			
		</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td>
			<?php if($user['User']['role']=='regular'): ?>
				<?php echo 'cashier';?>
			<?php else:?>
				<?php echo h($user['User']['role']); ?>
			<?php endif;?>
		&nbsp;</td>		
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
</p>