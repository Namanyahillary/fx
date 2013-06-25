<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="otherCurrencies index well">
	<h2><?php echo __('Other Currencies'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th class="actions"><?php echo __(''); ?></th>
	</tr>
	<?php foreach ($otherCurrencies as $otherCurrency): ?>
	<tr>
		<td><?php echo h($otherCurrency['OtherCurrency']['id']); ?>&nbsp;</td>
		<td><?php echo h($otherCurrency['OtherCurrency']['name']); ?>&nbsp;</td>
		<td><?php echo h($otherCurrency['OtherCurrency']['description']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $otherCurrency['OtherCurrency']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $otherCurrency['OtherCurrency']['id'])); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $otherCurrency['OtherCurrency']['id']),array('class'=>'action-delete confirm-first','data-confirm-text'=>'Are you sure you want to delete it? Please dont delete this currency if it has been used in any receipt saved!!')); ?>
		</td>
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