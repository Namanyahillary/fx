<?php
	$url='';
	$is_home=1;
	if(!(($this->params->url=='pages/home') || ($this->params->url==''))){
		$url=$this->params->webroot.'pages/home';
		$is_home=0;
	}
?>

<div class="navbar navbar-inverse navbar-fixed-top" fixed_managed="1" style="top: 0;">
<div class="navbar-inner">
  <div class="container">
	<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">Menu</a>
	<a class="brand" href="index.html">IFOB</a>
	<div id="primary-nav" class="nav-collapse">
	  <ul class="nav">
		<li><a lass="anchorLink" href="<?php echo $this->params->webroot; ?>dashboards"> Home</a></li>
	  </ul>
	</div><!--/.nav-collapse -->
  </div><!--/container-->
</div><!--/navbar-inner-->
</div><!--/navbar-->