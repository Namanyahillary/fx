<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="openings form well">
<?php echo $this->Form->create('Opening'); ?>
	<fieldset>
		<legend><?php echo __('Add Opening'); ?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('opening_ugx',array('value'=>0,'required'=>''));
		echo $this->Form->input('c1a',array('label'=>'USD opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c1r',array('label'=>'USD opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c2a',array('label'=>'Euro opening amont','value'=>0,'required'=>''));
		echo $this->Form->input('c2r',array('label'=>'Euro opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c3a',array('label'=>'GBP opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c3r',array('label'=>'GBP opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c4a',array('label'=>'Kshs opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c4r',array('label'=>'Kshs opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c5a',array('label'=>'Tzsh opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c5r',array('label'=>'Tzsh opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c6a',array('label'=>'SAR opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c6r',array('label'=>'SAR opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c7a',array('label'=>'SP opening amount','value'=>0,'required'=>''));
		echo $this->Form->input('c7r',array('label'=>'SP opening rate','value'=>0,'required'=>''));
		echo $this->Form->input('c8a',array('label'=>'Others opening amount(in UGX)','value'=>0,'required'=>''));
		echo $this->Form->input('c8r',array('label'=>'Others opening rate','value'=>(Configure::read('others')),'readonly'=>true,'required'=>''));
		foreach($other_currencies as $other_currency){
			echo $this->Form->input($other_currency['OtherCurrency']['name'].'_r',array('type'=>'number','label'=>($other_currency['OtherCurrency']['name']).' opening rate','value'=>0,'required'=>'','name'=>'data[OtherCurrency]['.$other_currency['OtherCurrency']['id'].'_r]'));
			echo $this->Form->input($other_currency['OtherCurrency']['name'].'_a',array('type'=>'number','label'=>($other_currency['OtherCurrency']['name']).' opening amount','value'=>0,'required'=>'','name'=>'data[OtherCurrency]['.$other_currency['OtherCurrency']['id'].'_a]'));
		}
		echo $this->Form->input('date',array('required'=>''));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>