<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div class="openings well">
<?php echo $this->Form->create('Opening'); ?>
	<fieldset>
		<legend><?php echo __('Edit Opening'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('opening_ugx',array('required'=>''));
		echo $this->Form->input('c1a',array('label'=>'USD opening amount','required'=>''));
		echo $this->Form->input('c1r',array('label'=>'USD opening rate','required'=>''));
		echo $this->Form->input('c2a',array('label'=>'Euro opening amont','required'=>''));
		echo $this->Form->input('c2r',array('label'=>'Euro opening rate','required'=>''));
		echo $this->Form->input('c3a',array('label'=>'GBP opening amount','required'=>''));
		echo $this->Form->input('c3r',array('label'=>'GBP opening rate','required'=>''));
		echo $this->Form->input('c4a',array('label'=>'Kshs opening amount','required'=>''));
		echo $this->Form->input('c4r',array('label'=>'Kshs opening rate','required'=>''));
		echo $this->Form->input('c5a',array('label'=>'Tzsh opening amount','required'=>''));
		echo $this->Form->input('c5r',array('label'=>'Tzsh opening rate','required'=>''));
		echo $this->Form->input('c6a',array('label'=>'SAR opening amount','required'=>''));
		echo $this->Form->input('c6r',array('label'=>'SAR opening rate','required'=>''));
		echo $this->Form->input('c7a',array('label'=>'SP opening amount','required'=>''));
		echo $this->Form->input('c7r',array('label'=>'SP opening rate','required'=>''));
		echo $this->Form->input('c8a',array('label'=>'Others opening amount(in UGX)','required'=>'','value'=>($this->Form->value('c8a'))*(Configure::read('others'))));
		echo $this->Form->input('c8r',array('label'=>'Others opening rate','value'=>(Configure::read('others')),'readonly'=>'true','required'=>''));
		
		$data=json_decode($this->Form->value('other_currencies'));
		foreach($data as $other_currencies){
			foreach($other_currencies as $other_currency){				
				echo '<div class="input number"><label>'.($other_currency->CNAME).' opening rate</label><input name="data[OtherCurrency]['.($other_currency->CID).'_r]" value="'.($other_currency->CRATE).'" type="number"></div>';
				echo '<div class="input number"><label >'.($other_currency->CNAME).' opening amount</label><input name="data[OtherCurrency]['.($other_currency->CID).'_a]" value="'.($other_currency->CAMOUNT).'" type="number"></div>';
			
			}
		}
		
		echo $this->Form->input('date',array('required'=>''));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>