<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="openings view">
<h2><?php  echo __('Opening'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($opening['User']['name'], array('controller' => 'users', 'action' => 'view', $opening['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Opening Ugx'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['opening_ugx']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C1a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c1a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C1r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c1r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C2a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c2a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C2r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c2r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C3a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c3a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C3r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c3r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C4a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c4a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C4r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c4r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C5a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c5a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C5r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c5r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C6a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c6a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C6r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c6r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C7a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c7a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C7r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c7r']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C8a'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c8a']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('C8r'); ?></dt>
		<dd>
			<?php echo h($opening['Opening']['c8r']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Opening'), array('action' => 'edit', $opening['Opening']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Opening'), array('action' => 'delete', $opening['Opening']['id']), null, __('Are you sure you want to delete # %s?', $opening['Opening']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Openings'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Opening'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
