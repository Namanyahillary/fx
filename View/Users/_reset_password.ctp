<?php echo $this->Html->css(array('style_login'));?>
<div class="container-fluid">
<div class="row-fluid">
			
	<div class="row-fluid">
		<div class="login-box">
			<div class="icons">
				<a href="<?php echo $this->params->webroot; ?>"><i class="icon-home"></i></a>
				<a href="#"><i class="icon-cog"></i></a>
			</div>
			<h2>Reset Password</h2>
			<div class="flash-message">
				<?php //echo $this->Session->flash('auth');?>
				<?php echo $this->Session->flash(); ?>
			</div>
			<?php echo $this->Form->create('User', array('action'=>'reset_password')); ?>				
				<fieldset>
					<label style="margin-left:3%">Enter your registered username:</label>
					<div class="input-prepend" title="Username">
						<span class="add-on"><i class="icon-user"></i></span>							
						<input class="input-large span10" name="data[User][username]" id="username" type="text" placeholder="type username" required=''>
					</div>
					<div class="error-message" style="margin-left:2%">
						NB: The new password will be sent to your email.
					</div>
					<div class="clearfix"></div>
					<div class="button-login">	
						<button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> ok</button>
					</div>
					<div class="clearfix"></div>
			
			<hr>
			<?php echo $this->Html->Link("Sign up",array('controller'=>'users','action'=>'register'),array('class'=>'btn btn-success')); ?>
			<div style="float:right;">
				<h3><?php echo $this->Html->Link("Login",array('controller'=>'users','action'=>'login')); ?></h3>
			</div>				
		</fieldset></form></div><!--/span-->
	</div><!--/row-->
	
		</div><!--/fluid-row-->
		
</div><!--/.fluid-container-->
