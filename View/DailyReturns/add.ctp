<style>
<!--
	.daily-returns tbody tr:first-child td {background:#2e335b;color:#fff;}
	.daily-returns{width:50%;}
	table tr td {border-bottom: 1px solid #f3f4f5;}
	table tr:nth-child(even) {background: none;}
	form div {clear: both;margin-bottom: -20px;padding: .5em;vertical-align: text-top;}
-->
</style>
<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="dailyReturns">
<?php echo $this->Form->create('DailyReturn'); ?>
	<fieldset>
		<legend><?php echo __('Add Daily Return'); ?></legend>
		<center>
		<table class="well daily-returns">
			<tbody>
				<tr>
					<td><b></b></td><td><b>Buying</b></td><td><b>Selling</b></td>
				<tr>
				<?php foreach($currencies as $currency): ?>
					<tr>
						<td style="vertical-align: middle;!important;background:#2e335b;color:#fff;"><?php echo $currency['Currency']['description'];?></td>
						<td><?php echo $this->Form->input('',array('name'=>'data[DailyBuyingReturn]['.($currency['Currency']['id']).']','label'=>'','type'=>'number','value'=>0)); ?></td>
						<td><?php echo $this->Form->input('',array('name'=>'data[DailySellingReturn]['.($currency['Currency']['id']).']','label'=>'','type'=>'number','value'=>0)); ?></td>	
					</tr>
				<?php endforeach; ?>
			</tbody>
		<table>
		</center>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>