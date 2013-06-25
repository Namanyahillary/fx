<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="expenses form well">
<?php echo $this->Form->create('Expense'); ?>
	<fieldset>
		<legend><?php echo __('Add Expense'); ?></legend>
		<?php if($super_admin):?>
			<?php echo $this->Form->input('user_id'); ?>
		<?php endif; ?>
	<?php
		echo $this->Form->input('item_id');
		echo $this->Form->input('amount');
	?>
	<label>Date:</label>
	<div class="input-append date" id='dp_x' data-date="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd">
		<input style="width:210px;" class="span2" size="16" type="text" id='dp_today_selected' value="<?php echo date('Y-m-d'); ?>" name="data[Expense][date]"/>
		<span class="add-on"><i class="icon-th"></i></span>
	</div>					
	
	<script>
		$(document).ready(function(){
			$('#dp_x').datepicker({
				format: 'yyyy-mm-dd'
			});
		});
	</script>	
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Expenses'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>
