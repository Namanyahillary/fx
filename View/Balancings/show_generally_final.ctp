<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>

<div class="well well-new">	
	<?php if(count($openings)): ?>
	<p>
		<div style="font-size:150%"><b>Initial Position: </b><span class="ln"><?php echo $fox['Fox']['initial_position']; ?></span> UGX</div><hr/>
		<div style="font-size:100%"><b>Total Profits: </b><span class="ln"><?php echo $openings[0][0]['total_profits']; ?></span> UGX</div><br/>
		<div style="font-size:100%"><b>Total Expenses: </b><span class="ln"><?php echo $openings[0][0]['total_expenses']; ?></span> UGX</div><br/>
		<div style="font-size:100%"><b>Total Net Profits: </b><span class="ln"><?php echo ($openings[0][0]['total_profits']-$openings[0][0]['total_expenses']); ?></span> UGX</div><hr/>
		<div style="font-size:150%"><b>Current Position: </b><span class="ln"><?php echo ($fox['Fox']['initial_position'])+($openings[0][0]['total_profits']-$openings[0][0]['total_expenses']); ?></span> UGX</div>
	</p>
	<?php else: ?>
		No openings found in the system.
	<?php endif; ?>
</div>