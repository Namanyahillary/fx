<div class="btn-group" style="width: auto;">
	<a href="<?php echo $this->params->webroot.'users/view/'.($users_Id) ?>" data-original-title="my profile" class="btn btn-large tip-bottom use-ajax" >
		<?php echo $this->Html->image("pic/" . ($profile_image), array('width' => '50px', 'height' => '40px', 'alt' => 'Profile Picture')); ?>
	</a>
</div>